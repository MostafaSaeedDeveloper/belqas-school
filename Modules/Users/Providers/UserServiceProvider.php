<?php

namespace Modules\Users\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
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
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        $routePath = __DIR__.'/../Routes/web.php';

        if (file_exists($routePath)) {
            Route::middleware('web')->group($routePath);
        }
    }
}

