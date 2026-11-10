<?php

namespace App\Modules\Gate;

use App\Modules\ModuleServiceProvider;

final class GateServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        //
    }

    protected function moduleAlias(): string
    {
        return 'gate';
    }
}
