<?php

namespace Subhashladumor1\LaravelCidi\commands;

use Illuminate\Support\Facades\File;

class CidiInstallCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cidi:install 
                            {--force : Overwrite existing files}
                            {--migrate : Run database migrations after installation}
                            {--seed : Run database seeders after installation}
                            {--command=* : Custom commands to run after installation}';

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

        // Run optional post-installation tasks
        $this->runPostInstallationTasks();

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
        $envDockerPath = base_path('.env.docker');
        $force = $this->option('force');

        if (File::exists($envDockerPath) && !$force) {
            $this->warning('.env.docker already exists. Use --force to overwrite.');
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
            $this->success('.env.docker file created successfully!');
        } else {
            $this->error('Failed to create .env.docker file.');
        }
    }

    /**
     * Run post-installation tasks based on options.
     */
    private function runPostInstallationTasks(): void
    {
        $this->line('');
        $this->info('🔧 Running post-installation tasks...');

        // Run migrations if requested
        if ($this->option('migrate')) {
            $this->runMigrations();
        }

        // Run seeders if requested
        if ($this->option('seed')) {
            $this->runSeeders();
        }

        // Run custom commands if provided
        $customCommands = $this->option('command');
        if (!empty($customCommands)) {
            $this->runCustomCommands($customCommands);
        }
    }

    /**
     * Run database migrations.
     */
    private function runMigrations(): void
    {        
        try {
            
            $this->line('');
            $this->warn('⚠️  WARNING: This process will ERASE all existing data.');
            $this->line("\033[33mIf you have previously run this command or migrated tables,\033[0m");
            $this->line("\033[33mall of your current data will be lost.\033[0m");
            if ($this->confirm('📦 Do you want to run migration?')) {
                $this->line('');
                $this->warn('⛔ Dropping all existing tables...');
                $this->callSilent('db:wipe');
                $this->info('✅ Database wiped successfully.');
                $this->line('📊 Running database migrations...');
                $this->call('migrate', ['--force' => true]);
            }
            $this->success('Database migrations completed successfully!');

        } catch (\Exception $e) {
            $this->error('Failed to run migrations: ' . $e->getMessage());
        }
    }

    /**
     * Run database seeders.
     */
    private function runSeeders(): void
    {
        $this->line('🌱 Running database seeders...');
        
        try {
            $this->call('db:seed', ['--force' => true]);
            $this->success('Database seeding completed successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to run seeders: ' . $e->getMessage());
        }
    }

    /**
     * Run custom commands.
     */
    private function runCustomCommands(array $commands): void
    {
        $this->line('⚙️  Running custom commands...');
        
        foreach ($commands as $command) {
            $this->line("Running: {$command}");
            
            try {
                // Parse command and arguments
                $parts = explode(' ', $command);
                $commandName = array_shift($parts);
                $arguments = [];
                $options = [];
                
                // Simple argument parsing
                foreach ($parts as $part) {
                    if (str_starts_with($part, '--')) {
                        $option = substr($part, 2);
                        if (str_contains($option, '=')) {
                            [$key, $value] = explode('=', $option, 2);
                            $options[$key] = $value;
                        } else {
                            $options[$option] = true;
                        }
                    } else {
                        $arguments[] = $part;
                    }
                }
                
                $this->call($commandName, array_merge($arguments, $options));
                $this->success("Command '{$command}' completed successfully!");
            } catch (\Exception $e) {
                $this->error("Failed to run command '{$command}': " . $e->getMessage());
            }
        }
    }
}

