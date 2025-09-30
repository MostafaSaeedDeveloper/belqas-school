<?php

namespace Modules\Subjects\Providers;

use Illuminate\Support\ServiceProvider;

class SubjectsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'subjects');
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/vendor/subjects'),
        ], 'subjects-views');
    }
}
