<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

/**
 * Konvensi modular monolith (vertical slice): modul memiliki route, view, dan komponen
 * Livewire-nya sendiri; middleware portal tetap terpusat di PortalRoutes.
 */
class ModuleConventionTest extends TestCase
{
    use RefreshDatabase;

    private function user(?string $role, string $status = 'active'): User
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

    public function test_every_module_registers_its_own_view_namespace(): void
    {
        $hints = View::getFinder()->getHints();

        foreach (['Admin', 'Catalog', 'Ordering', 'Payments', 'Gate', 'Reporting'] as $module) {
            $alias = strtolower($module);
            $this->assertArrayHasKey($alias, $hints, "Namespace view '{$alias}::' tidak terdaftar");
        }
    }

    public function test_every_portal_route_carries_expected_middleware(): void
    {
        $portals = [
            'eo.' => ['web', 'auth', 'verified', 'role:eo'],
            'admin.' => ['web', 'auth', 'verified', 'role:admin'],
            'customer.' => ['web'],
        ];

        foreach (Route::getRoutes() as $route) {
            foreach ($portals as $prefix => $required) {
                if (str_starts_with((string) $route->getName(), $prefix)) {
                    foreach ($required as $middleware) {
                        $this->assertContains($middleware, $route->gatherMiddleware(), "{$route->getName()} tanpa {$middleware}");
                    }
                }
            }
        }
    }

    public function test_components_and_layouts_exist_and_render(): void
    {
        $this->assertNotNull(view('components.button'));
        $this->assertNotNull(view('components.input', ['name' => 'test']));
        $this->assertNotNull(view('components.status-badge'));
        $this->assertNotNull(view('components.empty-state'));
        $this->assertNotNull(view('components.layouts.public'));
        $this->assertNotNull(view('components.layouts.eo'));
        $this->assertNotNull(view('components.layouts.admin'));
    }
}

