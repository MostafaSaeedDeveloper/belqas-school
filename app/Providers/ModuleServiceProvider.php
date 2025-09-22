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

            foreach ($this->discoverProviders($moduleDirectory, $moduleName) as $provider) {
                if (class_exists($provider)) {
                    $this->app->register($provider);
                }
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

    /**
     * Discover service providers declared for the given module.
     */
    protected function discoverProviders(string $moduleDirectory, string $moduleName): array
    {
        $providers = [];
        $moduleConfigPath = $moduleDirectory . '/module.json';

        if (file_exists($moduleConfigPath)) {
            $configuration = json_decode(file_get_contents($moduleConfigPath), true);

            if (is_array($configuration)) {
                $providers = array_filter($configuration['providers'] ?? []);
            }
        }

        if (empty($providers)) {
            $providers[] = sprintf('Modules\\%s\\Providers\\%sServiceProvider', $moduleName, $moduleName);
        }

        return array_unique($providers);
    }
}
