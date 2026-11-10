<?php

use App\Modules\Catalog\Http\Controllers\TenantEventController;
use App\Support\Routing\PortalRoutes;
use Illuminate\Support\Facades\Route;

/**
 * Route portal EO milik modul Catalog. {event} ter-scope di bawah {eo} (scopeBindings
 * dari PortalRoutes) dan difilter global scope TenantContext.
 */
PortalRoutes::eo(function (): void {
    Route::get('/events', [TenantEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [TenantEventController::class, 'show'])->name('events.show');
    Route::patch('/events/{event}', [TenantEventController::class, 'update'])->name('events.update');
});
