<?php

namespace Subhashladumor1\LaravelCidi\commands;

use Illuminate\Support\Facades\File;

class CidiInstallCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cidi:install 
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Install Laravel CIDI package and publish configuration files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Installing Laravel CIDI package...');

        // Publish configuration
        $this->publishConfig();

        // Publish stubs
        $this->publishStubs();

        // Create .env.docker file
        $this->createDockerEnvFile();

        $this->success('Laravel CIDI package installed successfully!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Review and customize config/cidi.php');
        $this->line('2. Run: php artisan cidi:docker');
        $this->line('3. Run: php artisan cidi:workflow');
        $this->line('4. Or run: php artisan cidi:generate all');

        return self::SUCCESS;
    }

    /**
     * Publish configuration file.
     */
    private function publishConfig(): void
    {
        $configPath = config_path('cidi.php');
        $force = $this->option('force');

        if (File::exists($configPath) && !$force) {
            $this->warning('Configuration file already exists. Use --force to overwrite.');
            return;
        }

        $this->call('vendor:publish', [
            '--provider' => 'Subhashladumor1\LaravelCidi\LaravelCidiServiceProvider',
            '--tag' => 'cidi-config',
            '--force' => $force,
        ]);

        $this->success('Configuration file published successfully!');
    }

    /**
     * Publish stub files.
     */
    private function publishStubs(): void
    {
        $stubsPath = resource_path('stubs/cidi');
        $force = $this->option('force');

        if (File::exists($stubsPath) && !$force) {
            $this->warning('Stub files already exist. Use --force to overwrite.');
            return;
        }

        $this->call('vendor:publish', [
            '--provider' => 'Subhashladumor1\LaravelCidi\LaravelCidiServiceProvider',
            '--tag' => 'cidi-stubs',
            '--force' => $force,
        ]);

        $this->success('Stub files published successfully!');
    }

    /**
     * Create .env.docker file.
     */
    private function createDockerEnvFile(): void
    {
        $envDockerPath = base_path('.env');
        $force = $this->option('force');

        if (File::exists($envDockerPath) && !$force) {
            $this->warning('.env already exists. Use --force to overwrite.');
            return;
        }

        $envDockerContent = $this->getStubContent('env.docker.stub');
        $replacements = [
            'APP_NAME' => config('app.name', 'Laravel'),
            'APP_ENV' => 'local',
            'APP_DEBUG' => 'true',
            'APP_URL' => 'http://localhost:8000',
            'DB_CONNECTION' => $this->config('database.type', 'mysql'),
            'DB_HOST' => 'mysql',
            'DB_PORT' => $this->config('database.port', '3306'),
            'DB_DATABASE' => $this->config('database.database', 'laravel'),
            'DB_USERNAME' => $this->config('database.username', 'laravel'),
            'DB_PASSWORD' => $this->config('database.password', 'password'),
            'REDIS_HOST' => 'redis',
            'REDIS_PASSWORD' => null,
            'REDIS_PORT' => $this->config('docker.redis_port', '6379'),
            'CACHE_DRIVER' => 'redis',
            'SESSION_DRIVER' => 'redis',
            'QUEUE_CONNECTION' => 'redis',
        ];

        $envDockerContent = $this->replacePlaceholders($envDockerContent, $replacements);

        if ($this->writeFile($envDockerPath, $envDockerContent)) {
            $this->success('.env file created successfully!');
        } else {
            $this->error('Failed to create .env file.');
        }
    }
}

