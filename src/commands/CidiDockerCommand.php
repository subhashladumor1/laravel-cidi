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

        $composeContent = $this->buildDockerComposeContent();
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
     * Build Docker Compose content dynamically based on enabled services.
     */
    private function buildDockerComposeContent(): string
    {
        $content = "version: '3.8'\n\nservices:\n";
        
        // Core services (always included)
        $content .= $this->getAppService();
        $content .= $this->getNginxService();
        $content .= $this->getMysqlService();
        $content .= $this->getRedisService();
        
        // Optional services (conditionally included)
        if ($this->config('services.horizon', false)) {
            $content .= $this->getHorizonService();
        }
        
        if ($this->config('services.mailhog', true)) {
            $content .= $this->getMailhogService();
        }
        
        if ($this->config('services.meilisearch', false)) {
            $content .= $this->getMeilisearchService();
        }
        
        if ($this->config('services.minio', false)) {
            $content .= $this->getMinioService();
        }
        
        // Volumes and networks
        $content .= $this->getVolumesAndNetworks();
        
        return $content;
    }

    /**
     * Get the app service configuration.
     */
    private function getAppService(): string
    {
        return <<<'YAML'
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: {{APP_NAME}}_app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - ./storage:/var/www/html/storage
    networks:
      - {{APP_NAME}}_network
    environment:
      - APP_ENV=local
      - APP_DEBUG=true
    depends_on:
      - mysql
      - redis
    env_file:
      - .env.docker

YAML;
    }

    /**
     * Get the nginx service configuration.
     */
    private function getNginxService(): string
    {
        return <<<'YAML'
  nginx:
    image: nginx:alpine
    container_name: {{APP_NAME}}_nginx
    restart: unless-stopped
    ports:
      - "{{NGINX_PORT}}:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - {{APP_NAME}}_network
    depends_on:
      - app

YAML;
    }

    /**
     * Get the MySQL service configuration.
     */
    private function getMysqlService(): string
    {
        return <<<'YAML'
  mysql:
    image: mysql:{{DB_VERSION}}
    container_name: {{APP_NAME}}_mysql
    restart: unless-stopped
    ports:
      - "{{MYSQL_PORT}}:3306"
    environment:
      MYSQL_DATABASE: {{DB_DATABASE}}
      MYSQL_USER: {{DB_USERNAME}}
      MYSQL_PASSWORD: {{DB_PASSWORD}}
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - {{APP_NAME}}_network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

YAML;
    }

    /**
     * Get the Redis service configuration.
     */
    private function getRedisService(): string
    {
        return <<<'YAML'
  redis:
    image: redis:alpine
    container_name: {{APP_NAME}}_redis
    restart: unless-stopped
    ports:
      - "{{REDIS_PORT}}:6379"
    volumes:
      - redis_data:/data
    networks:
      - {{APP_NAME}}_network
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      timeout: 3s
      retries: 5

YAML;
    }

    /**
     * Get the Horizon service configuration.
     */
    private function getHorizonService(): string
    {
        return <<<'YAML'
  # Optional: Horizon (Queue Worker)
  horizon:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: {{APP_NAME}}_horizon
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
    networks:
      - {{APP_NAME}}_network
    environment:
      - APP_ENV=local
      - APP_DEBUG=true
    env_file:
      - .env.docker
    command: php artisan horizon
    depends_on:
      - mysql
      - redis
    profiles:
      - horizon

YAML;
    }

    /**
     * Get the Mailhog service configuration.
     */
    private function getMailhogService(): string
    {
        return <<<'YAML'
  # Optional: Mailhog (Email Testing)
  mailhog:
    image: mailhog/mailhog:latest
    container_name: {{APP_NAME}}_mailhog
    restart: unless-stopped
    ports:
      - "{{MAILHOG_PORT}}:8025"
      - "1025:1025"
    networks:
      - {{APP_NAME}}_network
    profiles:
      - mailhog

YAML;
    }

    /**
     * Get the Meilisearch service configuration.
     */
    private function getMeilisearchService(): string
    {
        return <<<'YAML'
  # Optional: Meilisearch (Search Engine)
  meilisearch:
    image: getmeili/meilisearch:latest
    container_name: {{APP_NAME}}_meilisearch
    restart: unless-stopped
    ports:
      - "{{MEILISEARCH_PORT}}:7700"
    environment:
      MEILI_MASTER_KEY: masterKey
      MEILI_ENV: development
    volumes:
      - meilisearch_data:/meili_data
    networks:
      - {{APP_NAME}}_network
    profiles:
      - meilisearch

YAML;
    }

    /**
     * Get the MinIO service configuration.
     */
    private function getMinioService(): string
    {
        return <<<'YAML'
  # Optional: MinIO (S3 Compatible Storage)
  minio:
    image: minio/minio:latest
    container_name: {{APP_NAME}}_minio
    restart: unless-stopped
    ports:
      - "{{MINIO_PORT}}:9000"
      - "{{MINIO_CONSOLE_PORT}}:9001"
    environment:
      MINIO_ROOT_USER: minioadmin
      MINIO_ROOT_PASSWORD: minioadmin
    volumes:
      - minio_data:/data
    networks:
      - {{APP_NAME}}_network
    command: server /data --console-address ":9001"
    profiles:
      - minio

YAML;
    }

    /**
     * Get volumes and networks configuration.
     */
    private function getVolumesAndNetworks(): string
    {
        $volumes = "volumes:\n  mysql_data:\n  redis_data:\n";
        
        if ($this->config('services.meilisearch', false)) {
            $volumes .= "  meilisearch_data:\n";
        }
        
        if ($this->config('services.minio', false)) {
            $volumes .= "  minio_data:\n";
        }
        
        $volumes .= "\nnetworks:\n  {{APP_NAME}}_network:\n    driver: bridge\n";
        
        return $volumes;
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

        // Add optional services with default values for all placeholders
        $replacements['HORIZON_ENABLED'] = $this->config('services.horizon', false) ? 'true' : 'false';

        $replacements['MAILHOG_ENABLED'] = $this->config('services.mailhog', true) ? 'true' : 'false';
        $replacements['MAILHOG_PORT'] = $this->config('docker.mailhog_port', '8025');

        $replacements['MEILISEARCH_ENABLED'] = $this->config('services.meilisearch', false) ? 'true' : 'false';
        $replacements['MEILISEARCH_PORT'] = $this->config('docker.meilisearch_port', '7700');

        $replacements['MINIO_ENABLED'] = $this->config('services.minio', false) ? 'true' : 'false';
        $replacements['MINIO_PORT'] = $this->config('docker.minio_port', '9000');
        $replacements['MINIO_CONSOLE_PORT'] = $this->config('docker.minio_console_port', '9001');

        return $replacements;
    }
}

