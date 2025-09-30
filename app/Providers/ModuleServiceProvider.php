<?php

namespace App\Providers;

use Illuminate\Support\Arr;
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

            foreach ($this->discoverModuleProviders($moduleDirectory, $moduleName) as $serviceProvider) {
                if (class_exists($serviceProvider)) {
                    $this->app->register($serviceProvider);
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
     * Discover service providers declared within the module definition file.
     */
    protected function discoverModuleProviders(string $moduleDirectory, string $moduleName): array
    {
        $definitionPath = $moduleDirectory . DIRECTORY_SEPARATOR . 'module.json';

        if (File::exists($definitionPath)) {
            $definition = json_decode(File::get($definitionPath), true) ?: [];
            $providers = Arr::wrap($definition['providers'] ?? []);

            if (! empty($providers)) {
                return $providers;
            }
        }

        return [sprintf('Modules\\%s\\Providers\\%sServiceProvider', $moduleName, $moduleName)];
    }
}
