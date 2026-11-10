<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Tenancy\TenantContext;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
           $this->app->scoped(
        TenantContext::class,
        fn (): TenantContext => new TenantContext(),
    );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
