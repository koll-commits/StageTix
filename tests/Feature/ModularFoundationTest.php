<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Modules\Admin\AdminServiceProvider;
use App\Modules\Catalog\CatalogServiceProvider;
use App\Modules\Gate\GateServiceProvider;
use App\Modules\Ordering\OrderingServiceProvider;
use App\Modules\Payments\PaymentsServiceProvider;
use App\Modules\Reporting\ReportingServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModularFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function user(?string $role, string $status = 'active'): User
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $user->forceFill([
            'role' => $role,
            'status' => $status,
        ])->save();

        return $user;
    }

    private function tenantFor(User $user, string $role = 'owner', string $status = 'active'): Tenant
    {
        $tenant = Tenant::factory()->create(['slug' => 'demo', 'status' => $status]);

        $user->tenantRoles()->forceCreate([
            'user_id' => $user->id,
            'eo_id' => $tenant->id,
            'role' => $role,
        ]);

        return $tenant;
    }

    public function test_customer_route_is_public(): void
    {
        $this->get(route('customer.home'))->assertOk();
    }

    public function test_guest_is_redirected_to_login_on_eo_and_admin(): void
    {
        $this->get(route('eo.dashboard', ['eo' => 'demo']))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_eo_operator_can_open_eo_dashboard(): void
    {
        $this->withoutVite();

        $user = $this->user('eo');
        $tenant = $this->tenantFor($user);

        $this->actingAs($user);
        $this->get(route('eo.dashboard', ['eo' => $tenant->slug]))->assertOk();
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

        $admin = $this->user('admin');
        $this->actingAs($admin);
        $tenant = Tenant::factory()->create(['slug' => 'demo']);
        $this->get(route('eo.dashboard', ['eo' => $tenant->slug]))->assertForbidden();
    }

    public function test_user_without_role_is_forbidden_on_internal_contexts(): void
    {
        $this->actingAs($this->user(null));
        $this->get(route('admin.dashboard'))->assertForbidden();

        $tenant = Tenant::factory()->create(['slug' => 'demo']);
        $this->get(route('eo.dashboard', ['eo' => $tenant->slug]))->assertForbidden();
    }

    public function test_suspended_operator_is_forbidden(): void
    {
        $user = $this->user('eo', status: 'suspended');
        $tenant = $this->tenantFor($user);
        $this->actingAs($user);
        $this->get(route('eo.dashboard', ['eo' => $tenant->slug]))->assertForbidden();
    }

    public function test_suspended_tenant_is_forbidden_even_for_member(): void
    {
        $user = $this->user('eo');
        $tenant = Tenant::factory()->create(['slug' => 'suspended-eo', 'status' => 'suspended']);
        $user->tenantRoles()->forceCreate([
            'user_id' => $user->id,
            'eo_id' => $tenant->id,
            'role' => 'owner',
        ]);
        $this->actingAs($user);
        $this->get(route('eo.dashboard', ['eo' => $tenant->slug]))->assertForbidden();
    }

    public function test_all_six_module_providers_are_registered(): void
    {
        foreach ([
            CatalogServiceProvider::class,
            GateServiceProvider::class,
            PaymentsServiceProvider::class,
            ReportingServiceProvider::class,
            AdminServiceProvider::class,
            OrderingServiceProvider::class,
        ] as $provider) {
            $this->assertTrue(
                app()->providerIsLoaded($provider),
                "Provider tidak terdaftar: {$provider}"
            );
        }
    }
}
