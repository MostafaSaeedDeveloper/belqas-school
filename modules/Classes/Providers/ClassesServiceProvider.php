<?php

namespace Modules\Classes\Providers;

use Illuminate\Support\ServiceProvider;

class ClassesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'classes');
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/vendor/classes'),
        ], 'classes-views');
    }
}
