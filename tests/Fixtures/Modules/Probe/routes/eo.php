<?php

use App\Support\Routing\PortalRoutes;
use Illuminate\Support\Facades\Route;

PortalRoutes::eo(function (): void {
    Route::view('/probe', 'probe::page')->name('probe');
});
