<?php

namespace Subhashladumor1\LaravelCidi;

use Illuminate\Support\ServiceProvider;
use Subhashladumor1\LaravelCidi\commands\CidiInstallCommand;
use Subhashladumor1\LaravelCidi\commands\CidiDockerCommand;
use Subhashladumor1\LaravelCidi\commands\CidiWorkflowCommand;
use Subhashladumor1\LaravelCidi\commands\CidiGenerateCommand;

class LaravelCidiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/cidi.php' => config_path('cidi.php'),
            ], 'cidi-config');

            $this->publishes([
                __DIR__.'/resources/stubs' => resource_path('stubs/cidi'),
            ], 'cidi-stubs');

            $this->commands([
                CidiInstallCommand::class,
                CidiDockerCommand::class,
                CidiWorkflowCommand::class,
                CidiGenerateCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/cidi.php',
            'cidi'
        );
    }
}
