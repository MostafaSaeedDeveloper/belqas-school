<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any module service providers.
     */
    public function register(): void
    {
        $statuses = $this->readModuleStatuses();
        $modulesPath = base_path('modules');

        if (! is_dir($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $moduleDirectory) {
            $moduleName = basename($moduleDirectory);

            if (! ($statuses[$moduleName] ?? false)) {
                continue;
            }

            $moduleDefinitionPath = $moduleDirectory . DIRECTORY_SEPARATOR . 'module.json';

            if (! File::exists($moduleDefinitionPath)) {
                continue;
            }

            $moduleDefinition = json_decode(File::get($moduleDefinitionPath), true) ?: [];
            $providers = $moduleDefinition['providers'] ?? [];

            if (! is_array($providers)) {
                continue;
            }

            foreach ($providers as $provider) {
                if (! is_string($provider) || ! class_exists($provider)) {
                    continue;
                }

                $this->app->register($provider);
            }
        }
    }

    /**
     * Read module statuses from the configuration file.
     */
    protected function readModuleStatuses(): array
    {
        $path = base_path('modules_statuses.json');

        if (! file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);

        return json_decode($content, true) ?: [];
    }
}
