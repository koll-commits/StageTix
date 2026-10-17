<?php

use App\Modules\Admin\AdminServiceProvider;
use App\Modules\Catalog\CatalogServiceProvider;
use App\Modules\Checkin\CheckinServiceProvider;
use App\Modules\Ordering\OrderingServiceProvider;
use App\Modules\Payments\PaymentsServiceProvider;
use App\Modules\Reporting\ReportingServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,

    // Modular monolith: satu provider per bounded context.
    AdminServiceProvider::class,
    CatalogServiceProvider::class,
    OrderingServiceProvider::class,
    PaymentsServiceProvider::class,
    CheckinServiceProvider::class,
    ReportingServiceProvider::class,
];
