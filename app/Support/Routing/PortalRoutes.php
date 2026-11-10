<?php

namespace App\Support\Routing;

use Closure;
use Illuminate\Support\Facades\Route;

final class PortalRoutes
{
    public static function web(Closure|string $routes): void
    {
        Route::middleware('web')->group($routes);
    }

    public static function customer(Closure|string $routes): void
    {
        Route::middleware('web')
            ->prefix('stagetix')
            ->name('customer.')
            ->group($routes);
    }

    public static function admin(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'role:admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group($routes);
    }

    public static function eo(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'tenant'])
            ->prefix('eo/{eo:slug}')
            ->name('eo.')
            ->scopeBindings()
            ->group($routes);
    }

    public static function tenant(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'tenant'])
            ->prefix('tenant/{tenant:slug}')
            ->name('tenant.')
            ->scopeBindings()
            ->group($routes);
    }
}
