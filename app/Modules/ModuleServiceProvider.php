<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use ReflectionClass;

/**
 * Basis provider modul (modular monolith, vertical slice).
 *
 * - routes/{portal}.php        -> dimuat otomatis; isinya wajib dibungkus PortalRoutes
 * - resources/views            -> view('{alias}::...')
 * - resources/views/livewire   -> <livewire:{alias}::nama-komponen />
 * - Http/Controllers, Services, Data, Actions -> kelas PHP biasa (PSR-4 App\Modules\...)
 *
 * Shared kernel (TIDAK dipindah ke modul): model Eloquent, layout/komponen UI, middleware.
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Alias modul untuk namespace view & Livewire, mis. 'catalog'.
     */
    abstract protected function moduleAlias(): string;

    public function boot(): void
    {
        $path = $this->modulePath();

        $this->loadViewsFrom($path.'/resources/views', $this->moduleAlias());

        Livewire::addNamespace(
            namespace: $this->moduleAlias(),
            viewPath: $path.'/resources/views/livewire',
        );

        foreach (glob($path.'/routes/*.php') ?: [] as $routeFile) {
            $this->loadRoutesFrom($routeFile);
        }
    }

    /**
     * Folder modul = folder tempat kelas provider turunan berada.
     */
    protected function modulePath(): string
    {
        return dirname((string) (new ReflectionClass($this))->getFileName());
    }
}
