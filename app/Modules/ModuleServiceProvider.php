<?php 

namespace App\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;
use ReflectionClass;

abstract class ModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = '';

    public function boot(): void
    {
        $this->detectModuleName();

        $path = $this->modulePath();
        $alias = Str::kebab($this->moduleName);

        if (is_dir($path.'/resources/views')) {
            $this->loadViewsFrom($path.'/resources/views', $alias);
        }

        if (class_exists(Livewire::class) && is_dir($path.'/resources/views/livewire')) {
            Livewire::addNamespace(
                namespace: $alias,
                viewPath: $path.'/resources/views/livewire',
            );
        }

        if (is_dir($path.'/routes')) {
            foreach (glob($path.'/routes/*.php') ?: [] as $routeFile) {
                $this->loadRoutesFrom($routeFile);
            }
        }
    }

    protected function modulePath(): string
    {
        return dirname((string) (new ReflectionClass($this))->getFileName());
    }

    protected function detectModuleName(): void
    {
        if (! empty($this->moduleName)) {
            return;
        }

        $class = static::class;
        $parts = explode('\\', $class);

        if (count($parts) >= 3 && $parts[0] === 'App' && $parts[1] === 'Modules') {
            $this->moduleName = $parts[2];
        }
    }
}
