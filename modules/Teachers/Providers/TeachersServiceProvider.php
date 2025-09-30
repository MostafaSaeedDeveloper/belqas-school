<?php

namespace Modules\Teachers\Providers;

use Illuminate\Support\ServiceProvider;

class TeachersServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom($moduleBasePath . 'Resources/views', 'teachers');
    }
}
