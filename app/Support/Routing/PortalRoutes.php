<?php

namespace App\Support\Routing;

use Closure;
use Illuminate\Support\Facades\Route;

/**
 * Definisi TUNGGAL grup route per portal (prefix + name + middleware).
 */
final class PortalRoutes
{
    /** Katalog publik lintas EO, tanpa login (NFR-USA-02). */
    public static function publicSite(Closure|string $routes): void
    {
        Route::middleware('web')
            ->prefix('katalog')
            ->name('public.')
            ->group($routes);
    }

    /** Pembeli terautentikasi (FR-BUY-02). */
    public static function buyer(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'role:buyer'])
            ->prefix('akun')
            ->name('buyer.')
            ->group($routes);
    }

    /** Staf Event Organizer (tenant) (FR-EO-01). */
    public static function eo(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'role:eo'])
            ->prefix('eo/{tenant}')
            ->name('eo.')
            ->group($routes);
    }

    /** Pengelola platform. */
    public static function admin(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'role:admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group($routes);
    }
}
