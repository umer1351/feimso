<?php

namespace Tests\Feature;

use App\Admin\DocRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::createAccount(['id' => 'adm', 'email' => 'adm@test.local', 'role' => 'admin', 'password' => Hash::make('secret-pass-1')]);
    }

    public function test_guests_are_sent_to_login_and_customers_are_forbidden(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->actingAs(User::createAccount(['id' => 'cus', 'email' => 'c@test.local']));
        $this->get('/admin')->assertForbidden();
        $this->get('/admin/r/users')->assertForbidden();
    }

    public function test_only_admins_can_sign_in(): void
    {
        User::createAccount(['id' => 'cus', 'email' => 'c@test.local', 'password' => Hash::make('secret-pass-1')]);
        $this->post('/admin/login', ['email' => 'c@test.local', 'password' => 'secret-pass-1'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->admin();
        $this->post('/admin/login', ['email' => 'adm@test.local', 'password' => 'secret-pass-1'])->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_every_resource_list_renders(): void
    {
        $this->actingAs($this->admin());
        foreach (array_keys(config('admin.resources')) as $name) {
            $this->get("/admin/r/$name")->assertOk();
        }
        foreach (array_keys(config('admin_settings')) as $tab) {
            $this->get("/admin/settings/$tab")->assertOk();
        }
        $this->get('/admin')->assertOk();
    }

    public function test_creating_a_store_fills_geo_and_derived_fields(): void
    {
        $this->actingAs($this->admin());
        $repo = app(DocRepository::class);
        $repo->save('vendor_categories', 'cat1', ['title' => 'Pizza', 'section_id' => 's1']);

        $this->post('/admin/r/vendors', [
            'title' => 'Test Store', 'author' => 'adm', 'section_id' => 's1', 'zoneId' => 'z1',
            'categoryID' => ['cat1'], 'location' => 'Dubai', 'position_lat' => '25.2', 'position_lng' => '55.27',
            'workingHours' => '[]', 'reststatus' => '1',
        ])->assertRedirect('/admin/r/vendors');

        $row = DB::table('vendors')->where('title', 'Test Store')->first();
        $doc = $repo->find('vendors', $row->id);
        $this->assertSame($row->id, $doc['id']);
        $this->assertSame(['Pizza'], $doc['categoryTitle']);
        $this->assertSame(25.2, $doc['latitude']);
        $this->assertSame('thrr', substr($doc['g']['geohash'], 0, 4));
        $this->assertSame('geo', $doc['coordinates']['__fs']);
        $this->assertArrayHasKey('createdAt', $doc);
    }

    public function test_validation_errors_are_reported(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/r/vendor_products', ['name' => '', 'price' => 'abc'])
            ->assertSessionHasErrors();
        $this->assertSame(0, DB::table('vendor_products')->count());
    }

    public function test_blank_secret_keeps_the_saved_value(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/settings/payments', ['stripeSettings__stripeSecret' => 'sk_test_1', 'stripeSettings__isEnabled' => '1']);
        $this->post('/admin/settings/payments', ['stripeSettings__stripeSecret' => '']);
        $doc = app(DocRepository::class)->find('settings', 'stripeSettings');
        $this->assertSame('sk_test_1', $doc['stripeSecret']);

        $page = $this->get('/admin/settings/payments')->getContent();
        $this->assertStringNotContainsString('sk_test_1', $page, 'secrets are never echoed into the page');
    }

    public function test_wallet_adjustment_updates_balance_and_ledger(): void
    {
        $this->actingAs($this->admin());
        $user = User::createAccount(['id' => 'cus', 'email' => 'c@test.local']);
        $this->post('/admin/users/cus/wallet', ['amount' => '10.25', 'note' => 'gift'])->assertSessionHasNoErrors();
        $this->post('/admin/users/cus/wallet', ['amount' => '-3'])->assertSessionHasNoErrors();

        $this->assertEqualsWithDelta(7.25, (float) $user->fresh()->wallet_amount, 0.001);
        $this->assertSame(2, DB::table('wallet')->where('user_id', 'cus')->count());
    }

    public function test_admin_cannot_delete_or_demote_themselves(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        $this->delete('/admin/r/users/adm')->assertStatus(422);
        $this->put('/admin/r/users/adm', ['firstName' => 'A', 'email' => 'adm@test.local', 'role' => 'customer']);
        $this->assertSame('admin', $admin->fresh()->role);
    }
}
