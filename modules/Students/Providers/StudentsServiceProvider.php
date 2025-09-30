<?php

namespace Modules\Students\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class StudentsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom($moduleBasePath . 'Resources/views', 'students');
        $this->loadTranslationsFrom($moduleBasePath . 'Resources/lang', 'students');

        $this->registerViewComponents();
    }

    /**
     * Register reusable Blade components within the module.
     */
    protected function registerViewComponents(): void
    {
        $componentsPath = __DIR__ . '/../Resources/views/components';

        if (! File::isDirectory($componentsPath)) {
            return;
        }

        foreach (File::allFiles($componentsPath) as $component) {
            $view = str_replace('.blade.php', '', $component->getRelativePathname());
            $alias = 'students-' . str_replace(DIRECTORY_SEPARATOR, '-', $view);

            Blade::component('students::components.' . str_replace(DIRECTORY_SEPARATOR, '.', $view), $alias);
        }
    }
}
