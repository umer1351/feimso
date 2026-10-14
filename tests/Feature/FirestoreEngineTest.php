<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * The /fs/batch endpoint must behave like Firestore for the queries the views
 * make. Uses a throwaway `fs_test_items` collection so the semantics are
 * tested independently of the real schema.
 */
class FirestoreEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // A TEMPORARY table: plain CREATE/DROP TABLE would implicitly commit the
        // RefreshDatabase transaction in MySQL.
        DB::statement('DROP TEMPORARY TABLE IF EXISTS fs_test_items');
        Schema::create('fs_test_items', function (Blueprint $t) {
            $t->temporary();
            $t->string('id', 191)->collation('utf8mb4_bin')->primary();
            $t->string('name', 191)->nullable();
            $t->bigInteger('rank')->nullable();
            $t->double('price')->nullable();
            $t->boolean('publish')->nullable();
            $t->dateTime('createdAt', 6)->nullable();
            $t->json('author')->nullable();
            $t->json('tags')->nullable();
            $t->string('ownerId', 191)->nullable();
            $t->json('extra')->nullable();
            $t->dateTime('_created', 6)->nullable();
            $t->dateTime('_updated', 6)->nullable();
        });
        config()->set('firestore.collections.items', [
            'table' => 'fs_test_items',
            'read' => 'public',
            'write' => 'auth',
            'columns' => [
                'name' => 'string', 'rank' => 'int', 'price' => 'float', 'publish' => 'bool',
                'createdAt' => 'timestamp', 'author' => 'json', 'tags' => 'json', 'ownerId' => 'string',
            ],
        ]);
        $this->actingAs($this->user('writer'));
    }

    private function user(string $id, string $role = 'customer'): User
    {
        return User::find($id) ?? User::createAccount(['id' => $id, 'email' => "$id@test.local", 'role' => $role]);
    }

    private function batch(array $requests): array
    {
        $res = $this->postJson('/fs/batch', ['requests' => $requests]);
        $res->assertOk();
        return $res->json('results');
    }

    private function set(string $id, array $data, bool $merge = false, string $c = 'items'): array
    {
        return $this->batch([['t' => 'write', 'ops' => [['op' => 'set', 'collection' => $c, 'id' => $id, 'data' => $data, 'merge' => $merge]]]])[0];
    }

    private function query(array $q, string $c = 'items'): array
    {
        $r = $this->batch([['t' => 'query', 'c' => $c, 'q' => $q]])[0];
        $this->assertTrue($r['ok'], $r['message'] ?? '');
        return $r['docs'];
    }

    private function ids(array $docs): array
    {
        return array_column($docs, 'id');
    }

    private function seedItems(): void
    {
        $this->set('a', ['name' => 'Apple', 'rank' => 3, 'price' => 1.5, 'publish' => true, 'author' => ['id' => 'u1'], 'tags' => ['red', 'fruit']]);
        $this->set('b', ['name' => 'Banana', 'rank' => 1, 'price' => 0.5, 'publish' => true, 'author' => ['id' => 'u2'], 'tags' => ['yellow', 'fruit']]);
        $this->set('c', ['name' => 'Carrot', 'rank' => 2, 'publish' => false, 'author' => ['id' => 'u1'], 'tags' => ['veg']]);
        $this->set('d', ['name' => 'Date', 'publish' => true]); // no rank
    }

    public function test_round_trips_types_and_unknown_fields(): void
    {
        $ts = ['__fs' => 'ts', 's' => 1700000000, 'n' => 123000000];
        $this->set('x', [
            'name' => 'X', 'rank' => 7, 'price' => 2.25, 'publish' => true, 'createdAt' => $ts,
            'author' => ['id' => 'u1', 'loc' => ['__fs' => 'geo', 'lat' => 1.5, 'lng' => 2.5]],
            'surprise' => ['nested' => [1, 2]],   // no column -> kept in `extra`
            'price_as_text' => '10.00',
        ]);
        $doc = $this->batch([['t' => 'get', 'c' => 'items', 'id' => 'x']])[0]['doc'];

        $this->assertSame('X', $doc['data']['name']);
        $this->assertSame(7, $doc['data']['rank']);
        $this->assertSame(2.25, $doc['data']['price']);
        $this->assertTrue($doc['data']['publish']);
        $this->assertSame($ts, $doc['data']['createdAt']);
        $this->assertEquals(['__fs' => 'geo', 'lat' => 1.5, 'lng' => 2.5], $doc['data']['author']['loc']); // MySQL, like Firestore, doesn't keep map key order
        $this->assertSame(['nested' => [1, 2]], $doc['data']['surprise']);
        $this->assertSame('10.00', $doc['data']['price_as_text']);
    }

    public function test_value_that_does_not_fit_its_column_is_kept_verbatim(): void
    {
        $this->set('x', ['rank' => 'not-a-number']);
        $doc = $this->batch([['t' => 'get', 'c' => 'items', 'id' => 'x']])[0]['doc'];
        $this->assertSame('not-a-number', $doc['data']['rank']);
    }

    public function test_missing_doc_returns_null(): void
    {
        $this->assertNull($this->batch([['t' => 'get', 'c' => 'items', 'id' => 'nope']])[0]['doc']);
    }

    public function test_filters(): void
    {
        $this->seedItems();
        $this->assertSame(['a', 'b', 'd'], $this->ids($this->query(['filters' => [['publish', '==', true]]])));
        $this->assertSame(['a', 'c'], $this->ids($this->query(['filters' => [['author.id', '==', 'u1']]])));
        $this->assertSame(['a', 'b'], $this->ids($this->query(['filters' => [['tags', 'array-contains', 'fruit']]])));
        $this->assertSame(['b', 'c'], $this->ids($this->query(['filters' => [['name', 'in', ['Banana', 'Carrot']]]])));
        $this->assertSame(['a', 'c'], $this->ids($this->query(['filters' => [['__name__', 'in', ['a', 'c']]]])));
        $this->assertSame(['a'], $this->ids($this->query(['filters' => [['id', '==', 'a']]])), "where('id') means the doc id");
        // != and range filters skip docs where the field is missing (d has no rank)
        $this->assertSame(['b', 'c'], $this->ids($this->query(['filters' => [['rank', '!=', 3]]])));
        $this->assertSame(['c', 'a'], $this->ids($this->query(['filters' => [['rank', '>=', 2]]])), 'inequality implies orderBy(rank)');
    }

    public function test_order_by_excludes_missing_and_breaks_ties_by_id(): void
    {
        $this->seedItems();
        $this->assertSame(['b', 'c', 'a'], $this->ids($this->query(['orderBy' => [['rank', 'asc']]])));
        $this->assertSame(['a', 'c', 'b'], $this->ids($this->query(['orderBy' => [['rank', 'desc']]])));
        $this->assertSame(['a', 'b', 'c', 'd'], $this->ids($this->query([])));
        $this->assertSame(['a', 'b'], $this->ids($this->query(['limit' => 2])));
        $this->assertSame(['c', 'a'], $this->ids($this->query(['orderBy' => [['rank', 'asc']], 'limit' => 2, 'limitToLast' => true])));
    }

    public function test_cursors(): void
    {
        $this->seedItems();
        $byRank = ['orderBy' => [['rank', 'asc']]];
        $this->assertSame(['c', 'a'], $this->ids($this->query($byRank + ['startAfter' => ['doc' => 'b']])));
        $this->assertSame(['c', 'a'], $this->ids($this->query($byRank + ['startAt' => ['values' => [2]]])));
        $this->assertSame(['a'], $this->ids($this->query($byRank + ['startAfter' => ['values' => [2]]])));
        $this->assertSame(['b', 'c'], $this->ids($this->query($byRank + ['endAt' => ['values' => [2]]])));
        $this->assertSame(['b'], $this->ids($this->query($byRank + ['endBefore' => ['doc' => 'c']])));
        // paging without orderBy goes by id
        $this->assertSame(['c', 'd'], $this->ids($this->query(['startAfter' => ['doc' => 'b']])));
        // GeoFirestore-style: orderBy a nested JSON path with startAt/endAt
        $nested = ['orderBy' => [['author.id', 'asc']], 'startAt' => ['values' => ['u2']], 'endAt' => ['values' => ['u2~']]];
        $this->assertSame(['b'], $this->ids($this->query($nested)));
    }

    public function test_set_merge_update_and_delete(): void
    {
        $this->set('m', ['name' => 'M', 'author' => ['id' => 'u1', 'name' => 'Ann']]);
        $this->set('m', ['author' => ['name' => 'Bob']], true);
        $doc = $this->batch([['t' => 'get', 'c' => 'items', 'id' => 'm']])[0]['doc']['data'];
        $this->assertSame(['id' => 'u1', 'name' => 'Bob'], $doc['author'], 'merge is deep');
        $this->assertSame('M', $doc['name']);

        $r = $this->batch([['t' => 'write', 'ops' => [['op' => 'update', 'collection' => 'items', 'id' => 'm', 'data' => [
            'author.name' => 'Cy', 'name' => ['__fs' => 'del'], 'createdAt' => ['__fs' => 'now'],
        ]]]]])[0];
        $this->assertTrue($r['ok']);
        $doc = $this->batch([['t' => 'get', 'c' => 'items', 'id' => 'm']])[0]['doc']['data'];
        $this->assertSame('Cy', $doc['author']['name']);
        $this->assertArrayNotHasKey('name', $doc);
        $this->assertEqualsWithDelta(time(), $doc['createdAt']['s'], 5);

        $this->set('m', ['name' => 'Fresh']); // plain set replaces
        $doc = $this->batch([['t' => 'get', 'c' => 'items', 'id' => 'm']])[0]['doc']['data'];
        $this->assertSame(['name' => 'Fresh', 'id' => 'm'], $doc); // id is always exposed, like eMart docs

        $r = $this->batch([['t' => 'write', 'ops' => [['op' => 'update', 'collection' => 'items', 'id' => 'ghost', 'data' => ['name' => 'x']]]]])[0];
        $this->assertSame('not-found', $r['code']);

        $this->batch([['t' => 'write', 'ops' => [['op' => 'delete', 'collection' => 'items', 'id' => 'm']]]]);
        $this->assertNull($this->batch([['t' => 'get', 'c' => 'items', 'id' => 'm']])[0]['doc']);
    }

    public function test_requests_in_a_batch_fail_independently(): void
    {
        $results = $this->batch([
            ['t' => 'get', 'c' => 'no_such_collection', 'id' => 'x'],
            ['t' => 'query', 'c' => 'items', 'q' => ['filters' => [["name'); DROP TABLE x;--", '==', 1]]]],
            ['t' => 'get', 'c' => 'items', 'id' => 'x'],
        ]);
        $this->assertFalse($results[0]['ok']);
        $this->assertSame('invalid-argument', $results[1]['code'], 'field paths are validated, not interpolated');
        $this->assertTrue($results[2]['ok']);
    }

    public function test_guests_cannot_write(): void
    {
        auth()->logout();
        $this->assertSame('permission-denied', $this->set('g', ['name' => 'x'])['code']);
    }

    public function test_users_rules(): void
    {
        $me = $this->user('me');
        $this->user('other');
        $this->actingAs($me);

        // own profile: yes, but role/password are locked
        $r = $this->set('me', ['firstName' => 'Me', 'role' => 'admin', 'password' => 'x'], false, 'users');
        $this->assertTrue($r['ok']);
        $me->refresh();
        $this->assertSame('customer', $me->role);
        $this->assertNull($me->password);
        $this->assertSame('Me', $me->firstName);

        // someone else's profile: only the shared fields
        $deny = $this->batch([['t' => 'write', 'ops' => [['op' => 'update', 'collection' => 'users', 'id' => 'other', 'data' => ['firstName' => 'Hacked']]]]])[0];
        $this->assertSame('permission-denied', $deny['code']);
        $ok = $this->batch([['t' => 'write', 'ops' => [['op' => 'update', 'collection' => 'users', 'id' => 'other', 'data' => ['reviewsCount' => 4, 'reviewsSum' => 18]]]]])[0];
        $this->assertTrue($ok['ok']);

        // password hash and remember token never leave the server
        $doc = $this->batch([['t' => 'get', 'c' => 'users', 'id' => 'other']])[0]['doc']['data'];
        $this->assertArrayNotHasKey('password', $doc);
        $this->assertArrayNotHasKey('remember_token', $doc);
        $this->assertSame(4.0, (float) $doc['reviewsCount']);
    }

    public function test_owner_collections_only_show_own_rows(): void
    {
        $this->actingAs($this->user('u1'));
        $this->set('f1', ['user_id' => 'u1', 'product_id' => 'p1'], false, 'favorite_item');
        $this->actingAs($this->user('u2'));
        $this->set('f2', ['user_id' => 'u2', 'product_id' => 'p1'], false, 'favorite_item');
        $this->assertSame('permission-denied', $this->set('f3', ['user_id' => 'u1', 'product_id' => 'p9'], false, 'favorite_item')['code']);

        $this->assertSame(['f2'], $this->ids($this->query(['filters' => [['product_id', '==', 'p1']]], 'favorite_item')));
    }

    public function test_auth_endpoints(): void
    {
        auth()->logout();
        $this->postJson('/fs/auth/register', ['email' => 'New@Test.local', 'password' => 'secret12'])
            ->assertOk()->assertJsonPath('user.email', 'new@test.local');
        $uid = $this->postJson('/fs/auth/me')->json('user.uid');
        $this->assertSame(28, strlen($uid));
        $this->assertSame('customer', User::find($uid)->role);

        $this->postJson('/fs/auth/register', ['email' => 'new@test.local', 'password' => 'secret12'])
            ->assertStatus(400)->assertJsonPath('code', 'auth/email-already-in-use');

        $this->postJson('/fs/auth/logout')->assertOk();
        $this->postJson('/fs/auth/login', ['email' => 'new@test.local', 'password' => 'wrong'])
            ->assertStatus(400)->assertJsonPath('code', 'auth/wrong-password');
        $this->postJson('/fs/auth/login', ['email' => 'nobody@test.local', 'password' => 'x'])
            ->assertJsonPath('code', 'auth/user-not-found');
        $this->postJson('/fs/auth/login', ['email' => 'new@test.local', 'password' => 'secret12'])
            ->assertOk()->assertJsonPath('user.uid', $uid);
    }
}
