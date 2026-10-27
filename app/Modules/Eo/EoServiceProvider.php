<?php

namespace App\Modules\Eo;

use App\Modules\ModuleServiceProvider;

// Modul Eo (alias `eo`): pengelolaan Event Organizer.
final class EoServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        //
    }

    protected function moduleAlias(): string
    {
        return 'eo';
    }
}