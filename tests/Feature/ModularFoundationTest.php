<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Admin\AdminServiceProvider;
use App\Modules\Catalog\CatalogServiceProvider;
use App\Modules\Checkin\CheckinServiceProvider;
use App\Modules\Ordering\OrderingServiceProvider;
use App\Modules\Payments\PaymentsServiceProvider;
use App\Modules\Reporting\ReportingServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\ServiceProvider;
use Tests\TestCase;

class ModularFoundationTest extends TestCase
{
    use RefreshDatabase;

    private function user(?string $role, string $status = 'active'): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => $status,
            'email_verified_at' => now(),
        ]);
    }

    public function test_public_catalog_needs_no_login(): void
    {
        $this->get(route('public.home'))->assertOk();
    }

    public function test_login_page_renders_the_login_form(): void
    {
        $this->get(route('login'))
            ->assertSee('Masuk ke akun StageTix')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee(route('login.store'), false);
    }

    public function test_registration_page_renders_the_registration_form(): void
    {
        $this->get(route('register'))
            ->assertSee('Buat akun StageTix')
            ->assertSee('name="name"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee(route('register.store'), false);
    }

    public function test_valid_registration_creates_a_buyer_account(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Pembeli Baru',
            'email' => 'pembeli@example.test',
            'password' => 'SafePassw0rd!234',
            'password_confirmation' => 'SafePassw0rd!234',
        ])->assertRedirect();

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Pembeli Baru',
            'email' => 'pembeli@example.test',
            'role' => 'buyer',
            'status' => 'active',
        ]);
    }

    public function test_guest_is_redirected_to_login_on_internal_contexts(): void
    {
        $this->get(route('buyer.orders.index'))->assertRedirect(route('login'));
        $this->get(route('eo.dashboard', ['tenant' => 'demo']))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_buyer_can_open_orders_page(): void
    {
        $this->actingAs($this->user('buyer'));

        $this->get(route('buyer.orders.index'))->assertOk();
    }

    public function test_eo_staff_can_open_eo_dashboard(): void
    {
        $this->actingAs($this->user('eo'));

        $this->get(route('eo.dashboard', ['tenant' => 'demo']))->assertOk();
    }

    public function test_admin_can_open_admin_dashboard(): void
    {
        $this->actingAs($this->user('admin'));

        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_wrong_role_is_forbidden_not_empty_page(): void
    {
        $this->actingAs($this->user('eo'));
        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('buyer.orders.index'))->assertForbidden();

        $this->actingAs($this->user('admin'));
        $this->get(route('eo.dashboard', ['tenant' => 'demo']))->assertForbidden();
    }

    public function test_buyer_is_forbidden_on_eo_and_admin(): void
    {
        $this->actingAs($this->user('buyer'));

        $this->get(route('eo.dashboard', ['tenant' => 'demo']))->assertForbidden();
        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_suspended_eo_staff_is_forbidden(): void
    {
        $this->actingAs($this->user('eo', status: 'suspended'));

        $this->get(route('eo.dashboard', ['tenant' => 'demo']))->assertForbidden();
    }

    public function test_all_six_module_providers_are_registered(): void
    {
        $providers = array_map('get_class', app()->getProviders(ServiceProvider::class));

        foreach ([
            AdminServiceProvider::class,
            CatalogServiceProvider::class,
            OrderingServiceProvider::class,
            PaymentsServiceProvider::class,
            CheckinServiceProvider::class,
            ReportingServiceProvider::class,
        ] as $provider) {
            $this->assertContains($provider, $providers, "Provider tidak terdaftar: {$provider}");
        }
    }
}
