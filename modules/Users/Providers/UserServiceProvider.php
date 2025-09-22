<?php

namespace Modules\Users\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $moduleBasePath = __DIR__ . '/../';

        $this->loadRoutesFrom($moduleBasePath . 'Routes/web.php');
        $this->loadViewsFrom($moduleBasePath . 'Resources/views', 'users');
        $this->loadTranslationsFrom($moduleBasePath . 'Resources/lang', 'users');

        $this->registerViewComponents();
    }

    /**
     * Register reusable blade components used within the module.
     */
    protected function registerViewComponents(): void
    {
        $componentsPath = __DIR__ . '/../Resources/views/components';

        if (! File::isDirectory($componentsPath)) {
            return;
        }

        foreach (File::allFiles($componentsPath) as $component) {
            $view = str_replace('.blade.php', '', $component->getRelativePathname());
            $alias = 'users-' . str_replace(DIRECTORY_SEPARATOR, '-', $view);

            Blade::component('users::components.' . str_replace(DIRECTORY_SEPARATOR, '.', $view), $alias);
        }
    }
}
