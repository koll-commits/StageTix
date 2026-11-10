<?php

use App\Http\Controllers\Eo\BalanceController;
use App\Http\Controllers\Eo\CheckinController;
use App\Http\Controllers\Eo\DashboardController;
use App\Http\Controllers\Eo\EventController;
use App\Http\Controllers\Eo\ReportController;
use App\Http\Controllers\Eo\TicketAreaController;
use App\Http\Controllers\Eo\TicketCategoryController;
use App\Http\Middleware\ResolveEoTenant;
use Illuminate\Support\Facades\Route;

// Prefix eo/{eo}, nama eo., dan role:eo dipasang oleh PortalRoutes::eo().

Route::middleware(ResolveEoTenant::class)->group(function (): void {
    Route::get('/checkin', [CheckinController::class, 'index'])->name('checkin');
});

Route::middleware(ResolveEoTenant::class.':manage')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('events', EventController::class)->only(['create', 'store', 'edit']);
    Route::post('/events/{event}/areas', [TicketAreaController::class, 'store'])->name('areas.store');
    Route::post('/events/{event}/categories', [TicketCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [TicketCategoryController::class, 'update'])->name('categories.update');
    Route::patch('/categories/{category}/sold-out', [TicketCategoryController::class, 'toggleSoldOut'])->name('categories.sold-out');

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports');
    Route::get('/saldo', [BalanceController::class, 'index'])->name('balance');
});
