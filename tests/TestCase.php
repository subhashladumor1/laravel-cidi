<?php

namespace Subhashladumor1\LaravelCidi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Subhashladumor1\LaravelCidi\LaravelCidiServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Get package providers.
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelCidiServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:YourAppKeyHere');
    }
}

