<?php 

namespace App\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

abstract class ModuleServiceProvider extends ServiceProvider {
    protected $moduleName = '';
    public function boot(): void {
        $this->detectModuleName();

        $path = app_path('Modules/'.$this->moduleName);
        $alias = Str::kebab($this->moduleName);

        if (is_dir($path.'/resources/views')) {
            $this->loadViewsFrom($path.'/resources/views', $alias);
        }

        if (is_dir($path.'/routes')) {
            foreach (glob($path.'/routes/*.php') ?: [] as $routeFile) {
                $this->loadRoutesFrom($routeFile);
            }
        }

    }

    protected function detectModuleName(): void {
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