<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $statusesPath = base_path('modules_statuses.json');

        if (! File::exists($statusesPath)) {
            return;
        }

        $statuses = json_decode(File::get($statusesPath), true);

        if (! is_array($statuses)) {
            return;
        }

        foreach ($statuses as $module => $enabled) {
            if (! $enabled) {
                continue;
            }

            $moduleConfigPath = base_path(sprintf('Modules/%s/module.json', $module));

            if (! File::exists($moduleConfigPath)) {
                continue;
            }

            $config = json_decode(File::get($moduleConfigPath), true);

            if (! isset($config['providers']) || ! is_array($config['providers'])) {
                continue;
            }

            foreach ($config['providers'] as $providerClass) {
                if (! is_string($providerClass)) {
                    continue;
                }

                if (! class_exists($providerClass)) {
                    continue;
                }

                $this->app->register($providerClass);
            }
        }
    }
}

