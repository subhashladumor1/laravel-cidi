<?php

namespace Subhashladumor1\LaravelCidi\commands;

use Illuminate\Support\Facades\File;

class CidiDockerCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cidi:docker 
                            {--force : Overwrite existing files}
                            {--services=* : Specific services to include}';

    /**
     * The console command description.
     */
    protected $description = 'Generate Docker configuration files (Dockerfile, docker-compose.yml, .dockerignore)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🐳 Generating Docker configuration files...');

        // Generate Dockerfile
        $this->generateDockerfile();

        // Generate docker-compose.yml
        $this->generateDockerCompose();

        // Generate .dockerignore
        $this->generateDockerIgnore();

        $this->success('Docker configuration files generated successfully!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Review the generated files');
        $this->line('2. Run: docker-compose up -d');
        $this->line('3. Run: php artisan migrate');

        return self::SUCCESS;
    }

    /**
     * Generate Dockerfile.
     */
    private function generateDockerfile(): void
    {
        $dockerfilePath = base_path('Dockerfile');
        $force = $this->option('force');

        if (File::exists($dockerfilePath) && !$force) {
            $this->warning('Dockerfile already exists. Use --force to overwrite.');
            return;
        }

        $dockerfileContent = $this->getStubContent('Dockerfile.stub');
        $replacements = [
            'PHP_VERSION' => $this->config('php_version', '8.3'),
        ];

        $dockerfileContent = $this->replacePlaceholders($dockerfileContent, $replacements);

        if ($this->writeFile($dockerfilePath, $dockerfileContent)) {
            $this->success('Dockerfile generated successfully!');
        } else {
            $this->error('Failed to generate Dockerfile.');
        }
    }

    /**
     * Generate docker-compose.yml.
     */
    private function generateDockerCompose(): void
    {
        $composePath = base_path('docker-compose.yml');
        $force = $this->option('force');

        if (File::exists($composePath) && !$force) {
            $this->warning('docker-compose.yml already exists. Use --force to overwrite.');
            return;
        }

        $composeContent = $this->getStubContent('docker-compose.yml.stub');
        $replacements = $this->getDockerComposeReplacements();

        $composeContent = $this->replacePlaceholders($composeContent, $replacements);

        if ($this->writeFile($composePath, $composeContent)) {
            $this->success('docker-compose.yml generated successfully!');
        } else {
            $this->error('Failed to generate docker-compose.yml.');
        }
    }

    /**
     * Generate .dockerignore.
     */
    private function generateDockerIgnore(): void
    {
        $dockerignorePath = base_path('.dockerignore');
        $force = $this->option('force');

        if (File::exists($dockerignorePath) && !$force) {
            $this->warning('.dockerignore already exists. Use --force to overwrite.');
            return;
        }

        $dockerignoreContent = $this->getStubContent('dockerignore.stub');

        if ($this->writeFile($dockerignorePath, $dockerignoreContent)) {
            $this->success('.dockerignore generated successfully!');
        } else {
            $this->error('Failed to generate .dockerignore.');
        }
    }

    /**
     * Get replacements for docker-compose.yml.
     */
    private function getDockerComposeReplacements(): array
    {
        $replacements = [
            'APP_NAME' => strtolower(config('app.name', 'laravel')),
            'PHP_VERSION' => $this->config('php_version', '8.3'),
            'APP_PORT' => $this->config('docker.app_port', '8000'),
            'NGINX_PORT' => $this->config('docker.nginx_port', '80'),
            'MYSQL_PORT' => $this->config('docker.mysql_port', '3306'),
            'REDIS_PORT' => $this->config('docker.redis_port', '6379'),
            'DB_DATABASE' => $this->config('database.database', 'laravel'),
            'DB_USERNAME' => $this->config('database.username', 'laravel'),
            'DB_PASSWORD' => $this->config('database.password', 'password'),
            'DB_VERSION' => $this->config('database.version', '8.0'),
        ];

        // Add optional services
        if ($this->config('services.horizon', false)) {
            $replacements['HORIZON_ENABLED'] = 'true';
        } else {
            $replacements['HORIZON_ENABLED'] = 'false';
        }

        if ($this->config('services.mailhog', true)) {
            $replacements['MAILHOG_ENABLED'] = 'true';
            $replacements['MAILHOG_PORT'] = $this->config('docker.mailhog_port', '8025');
        } else {
            $replacements['MAILHOG_ENABLED'] = 'false';
        }

        if ($this->config('services.meilisearch', false)) {
            $replacements['MEILISEARCH_ENABLED'] = 'true';
            $replacements['MEILISEARCH_PORT'] = $this->config('docker.meilisearch_port', '7700');
        } else {
            $replacements['MEILISEARCH_ENABLED'] = 'false';
        }

        if ($this->config('services.minio', false)) {
            $replacements['MINIO_ENABLED'] = 'true';
            $replacements['MINIO_PORT'] = $this->config('docker.minio_port', '9000');
            $replacements['MINIO_CONSOLE_PORT'] = $this->config('docker.minio_console_port', '9001');
        } else {
            $replacements['MINIO_ENABLED'] = 'false';
        }

        return $replacements;
    }
}

