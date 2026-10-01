<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * One-off generator: reads the schema inventory in docs/schema/*.json (pulled
 * out of the old Firestore code) and writes
 *   - config/firestore.php                    collection -> table/columns/access map
 *   - database/migrations/*_create_<t>_table   one explicit migration per table
 *
 * After the first run, edit those files by hand; re-running with --force
 * overwrites them.
 */
class FsGenerateSchema extends Command
{
    protected $signature = 'fs:generate-schema {--force : overwrite existing generated files}';
    protected $description = 'Generate config/firestore.php and MySQL migrations from docs/schema/*.json';

    /** Collections that only ever generated ids (collection('tmp').doc().id) — no table. */
    private const ID_ONLY = ['tmp', 'temp'];

    /**
     * Access rules, decided from the inventory's access/writes notes (docs/schema).
     * Anything not listed: public read, admin-only write.
     * NOTE: wallet/order/review writes still come from the browser, exactly as
     * with Firestore. Moving them server-side is a planned hardening step.
     */
    private const RULES = [
        'users'             => ['read' => 'public', 'write' => 'owner', 'owner' => 'id',
                                'protected' => ['role', 'active'],
                                'shared' => ['reviewsCount', 'reviewsSum', 'wallet_amount', 'subscriptionTotalOrders']],
        'wallet'            => ['read' => 'owner', 'write' => 'auth', 'owner' => 'user_id'],
        'referral'          => ['read' => 'public', 'write' => 'owner', 'owner' => 'id'],
        'gift_purchases'    => ['read' => 'auth', 'write' => 'owner', 'owner' => 'userid', 'shared' => ['redeem']],
        'favorite_vendor'   => ['read' => 'owner', 'write' => 'owner', 'owner' => 'user_id'],
        'favorite_item'     => ['read' => 'owner', 'write' => 'owner', 'owner' => 'user_id'],
        'favorite_service'  => ['read' => 'owner', 'write' => 'owner', 'owner' => 'user_id'],
        'favorite_provider' => ['read' => 'owner', 'write' => 'owner', 'owner' => 'user_id'],
        'vendor_orders'     => ['read' => 'owner', 'write' => 'owner', 'owner' => 'authorID'],
        'provider_orders'   => ['read' => 'owner', 'write' => 'owner', 'owner' => 'authorID'],
        'parcel_orders'     => ['read' => 'owner', 'write' => 'owner', 'owner' => 'authorID'],
        'rental_orders'     => ['read' => 'owner', 'write' => 'owner', 'owner' => 'authorID'],
        'booked_table'      => ['read' => 'owner', 'write' => 'owner', 'owner' => 'authorID'],
        'items_review'      => ['read' => 'public', 'write' => 'owner', 'owner' => 'CustomerId'],
        'foods_review'      => ['read' => 'public', 'write' => 'owner', 'owner' => 'CustomerId'],
        'vendors'           => ['shared' => ['reviewsCount', 'reviewsSum']],
        'vendor_products'   => ['shared' => ['reviewsCount', 'reviewsSum', 'reviewAttributes', 'quantity', 'item_attribute']],
        'providers_services' => ['shared' => ['reviewsCount', 'reviewsSum']],
        'providers_workers' => ['shared' => ['reviewsCount', 'reviewsSum']],
    ];

    public function handle(): int
    {
        $collections = [];
        foreach (glob(base_path('docs/schema/*.json')) as $file) {
            $json = json_decode(file_get_contents($file), true);
            if (!is_array($json)) {
                $this->error("Invalid JSON: $file");
                return self::FAILURE;
            }
            foreach ($json as $name => $spec) {
                if (!in_array($name, self::ID_ONLY, true) && !str_starts_with($name, '_')) {
                    $collections[$name] = $spec;
                }
            }
        }
        ksort($collections);

        $config = [];
        foreach ($collections as $name => $spec) {
            $config[$name] = $this->mapCollection($name, $spec);
        }

        $this->writeConfig($config);
        $this->writeMigrations($config);
        $this->info(count($config) . ' collections generated.');
        return self::SUCCESS;
    }

    private function mapCollection(string $name, array $spec): array
    {
        $columns = [];
        $indexes = [];
        foreach ($spec['fields'] ?? [] as $field => $f) {
            // MySQL column names are case-insensitive: 'Id' would clash with the id primary key.
            $taken = array_map('strtolower', array_merge(['id', 'extra', '_created', '_updated'], array_keys($columns)));
            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]{0,63}$/', $field) || in_array(strtolower($field), $taken, true)) {
                continue; // stays in `extra`
            }
            $f = is_array($f) ? $f : ['type' => (string) $f];
            $queried = !empty($f['queried']) || !empty($f['ordered']);
            $type = $this->mapType((string) ($f['type'] ?? 'json'), $queried);
            $columns[$field] = $type;
            if ($queried && in_array($type, ['string', 'int', 'float', 'bool', 'timestamp'], true)) {
                $indexes[] = $field;
            }
        }

        if ($name === 'users') {
            // Login lives on the same row as the profile.
            foreach (['email' => 'string', 'phoneNumber' => 'string', 'role' => 'string', 'active' => 'bool'] as $k => $t) {
                $columns[$k] = $columns[$k] ?? $t;
                if ($t !== 'bool' && !in_array($k, $indexes, true)) {
                    $indexes[] = $k;
                }
            }
            $columns['password'] = 'string';
            $columns['remember_token'] = 'string';
            $columns['email_verified_at'] = 'timestamp';
        }

        $rule = (self::RULES[$name] ?? []) + ['read' => 'public', 'write' => 'admin', 'owner' => null, 'protected' => [], 'shared' => []];

        return [
            'table' => $name,
            'read' => $rule['read'],
            'write' => $rule['write'],
            'owner' => $rule['owner'],
            'hidden' => $name === 'users' ? ['password', 'remember_token', 'email_verified_at'] : [],
            'protected' => $rule['protected'],
            'shared' => $rule['shared'],
            'columns' => $columns,
            'indexes' => array_slice($indexes, 0, 40),
        ];
    }

    private function mapType(string $t, bool $queried): string
    {
        $t = strtolower(trim($t));
        return match (true) {
            $t === 'string' => $queried ? 'string' : 'text',
            in_array($t, ['int', 'integer'], true) => 'int',
            in_array($t, ['float', 'double', 'number'], true) => 'float',
            in_array($t, ['bool', 'boolean'], true) => 'bool',
            $t === 'timestamp' => 'timestamp',
            default => 'json', // map, array, geopoint, and anything mixed ("string|int")
        };
    }

    private function writeConfig(array $config): void
    {
        $path = config_path('firestore.php');
        if (is_file($path) && !$this->option('force')) {
            $this->warn('config/firestore.php exists, skipped (use --force)');
            return;
        }
        $body = var_export(['collections' => $config], true);
        $body = preg_replace(['/array \(/', '/\)(,?)$/m', '/=> \n\s+\[/'], ['[', ']$1', '=> ['], $body);
        $php = "<?php\n\n/*\n| Firestore collection -> MySQL table map, used by App\\Firestore\\Engine.\n|\n| columns: field => string (indexed VARCHAR) | text | int | float | bool | timestamp | json\n| read:    public | auth | owner | admin      write: none | auth | owner | admin\n| owner:   field holding the owning user's id ('id' = the doc id itself)\n| Fields not listed are kept in each row's `extra` JSON column.\n|\n| Generated by `php artisan fs:generate-schema`, then maintained by hand.\n*/\n\nreturn $body;\n";
        file_put_contents($path, $php);
        $this->line('wrote config/firestore.php');
    }

    private function writeMigrations(array $config): void
    {
        $i = 0;
        foreach ($config as $name => $c) {
            $table = $c['table'];
            $existing = glob(database_path("migrations/*_create_{$table}_table.php"));
            if ($existing && !$this->option('force')) {
                continue;
            }
            foreach ($existing as $old) {
                unlink($old);
            }
            $file = database_path(sprintf('migrations/2026_10_02_%06d_create_%s_table.php', 100 + $i++, $table));
            file_put_contents($file, $this->migration($table, $c));
            $this->line('wrote ' . basename($file));
        }
    }

    private function migration(string $table, array $c): string
    {
        $lines = ["            \$table->string('id', 191)->collation('utf8mb4_bin')->primary();"];
        foreach ($c['columns'] as $field => $type) {
            $col = match ($type) {
                'string' => "\$table->string('$field', 191)",
                'text' => "\$table->text('$field')",
                'int' => "\$table->bigInteger('$field')",
                'float' => "\$table->double('$field')",
                'bool' => "\$table->boolean('$field')",
                'timestamp' => "\$table->dateTime('$field', 6)",
                default => "\$table->json('$field')",
            };
            $lines[] = "            {$col}->nullable();";
        }
        $lines[] = "            \$table->json('extra')->nullable()->comment('fields without a dedicated column');";
        $lines[] = "            \$table->dateTime('_created', 6)->nullable();";
        $lines[] = "            \$table->dateTime('_updated', 6)->nullable();";
        foreach ($c['indexes'] as $field) {
            $unique = $table === 'users' && $field === 'email' ? 'unique' : 'index';
            $lines[] = "            \$table->$unique('$field');";
        }
        $cols = implode("\n", $lines);
        $label = Str::headline($table);

        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// $label (was the Firestore collection '$table').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('$table', function (Blueprint \$table) {
$cols
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('$table');
    }
};

PHP;
    }
}
