<?php

namespace Modules\Teachers\Providers;

use Illuminate\Support\ServiceProvider;

class TeachersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'teachers');
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/vendor/teachers'),
        ], 'teachers-views');
    }
}
