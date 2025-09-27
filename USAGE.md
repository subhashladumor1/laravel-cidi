# Usage Examples

This document provides practical examples of how to use Laravel CIDI in different scenarios.

## Basic Setup

### 1. Fresh Laravel Project

```bash
# Create new Laravel project
composer create-project laravel/laravel my-project
cd my-project

# Install Laravel CIDI
composer require subhashladumor1/laravel-cidi

# Install and configure
php artisan cidi:install

# Generate all configuration files
php artisan cidi:generate all

# Start Docker services
docker-compose up -d

# Run migrations
php artisan migrate
```

### 2. Existing Laravel Project

```bash
# Install Laravel CIDI
composer require subhashladumor1/laravel-cidi

# Install package
php artisan cidi:install

# Review and customize configuration
# Edit config/cidi.php as needed

# Generate Docker setup
php artisan cidi:docker

# Generate CI/CD workflows
php artisan cidi:workflow
```

## Configuration Examples

### Development Environment

```php
// config/cidi.php
return [
    'php_version' => '8.3',
    'services' => [
        'horizon' => false,
        'mailhog' => true,
        'meilisearch' => false,
        'minio' => false,
        'redis' => true,
    ],
    'cicd' => [
        'staging' => [
            'enabled' => true,
            'server' => 'staging.example.com',
            'user' => 'deploy',
            'path' => '/var/www/staging',
        ],
        'production' => [
            'enabled' => false, // Disable for development
        ],
    ],
];
```

### Production Environment

```php
// config/cidi.php
return [
    'php_version' => '8.3',
    'services' => [
        'horizon' => true,
        'mailhog' => false,
        'meilisearch' => true,
        'minio' => true,
        'redis' => true,
    ],
    'cicd' => [
        'staging' => [
            'enabled' => true,
            'server' => 'staging.example.com',
            'user' => 'deploy',
            'path' => '/var/www/staging',
        ],
        'production' => [
            'enabled' => true,
            'server' => 'production.example.com',
            'user' => 'deploy',
            'path' => '/var/www/production',
        ],
        'registry' => [
            'enabled' => true,
            'type' => 'ghcr',
            'username' => 'your-username',
            'repository' => 'your-repo',
        ],
        'notifications' => [
            'slack' => [
                'enabled' => true,
                'webhook' => env('SLACK_WEBHOOK'),
            ],
        ],
    ],
];
```

## Docker commands

### Start Services

```bash
# Start all services
docker-compose up -d

# Start specific services
docker-compose up -d app nginx mysql redis

# Start with optional services
docker-compose --profile horizon --profile mailhog up -d
```

### Development Workflow

```bash
# View logs
docker-compose logs -f app

# Execute commands in container
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker

# Access database
docker-compose exec mysql mysql -u laravel -p laravel

# Access Redis
docker-compose exec redis redis-cli
```

### Production Deployment

```bash
# Build production image
docker build -t my-app:latest .

# Run production container
docker run -d \
  --name my-app \
  -p 80:80 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  my-app:latest
```

## CI/CD Examples

### GitHub Secrets Setup

Required secrets for deployment:

```
STAGING_HOST=staging.example.com
STAGING_USER=deploy
STAGING_SSH_KEY=your-private-key
STAGING_PORT=22
STAGING_URL=https://staging.example.com

PRODUCTION_HOST=production.example.com
PRODUCTION_USER=deploy
PRODUCTION_SSH_KEY=your-private-key
PRODUCTION_PORT=22
PRODUCTION_URL=https://production.example.com

SLACK_WEBHOOK=https://hooks.slack.com/services/...
DISCORD_WEBHOOK=https://discord.com/api/webhooks/...
```

### Environment Variables

Add to your `.env` file:

```env
# Laravel CIDI Configuration
CIDI_PHP_VERSION=8.3
CIDI_DB_TYPE=mysql
CIDI_DB_VERSION=8.0

# Services
CIDI_HORIZON_ENABLED=true
CIDI_MAILHOG_ENABLED=false
CIDI_MEILISEARCH_ENABLED=true
CIDI_MINIO_ENABLED=true

# CI/CD
CIDI_STAGING_ENABLED=true
CIDI_PRODUCTION_ENABLED=true
CIDI_REGISTRY_ENABLED=true
CIDI_REGISTRY_TYPE=ghcr
CIDI_REGISTRY_USERNAME=your-username
CIDI_REGISTRY_REPOSITORY=your-repo

# Notifications
CIDI_SLACK_ENABLED=true
CIDI_SLACK_WEBHOOK=https://hooks.slack.com/services/...
CIDI_DISCORD_ENABLED=false
```

## Advanced Usage

### Custom Docker Configuration

```bash
# Generate with specific services
php artisan cidi:docker --services=horizon,mailhog,meilisearch

# Overwrite existing files
php artisan cidi:docker --force
```

### Custom Workflow Generation

```bash
# Generate only CI workflow
php artisan cidi:workflow --type=ci

# Generate only deploy workflow
php artisan cidi:workflow --type=deploy

# Overwrite existing workflows
php artisan cidi:workflow --force
```

### Multi-Environment Setup

```bash
# Development
php artisan cidi:generate all

# Staging
CIDI_PRODUCTION_ENABLED=false php artisan cidi:generate all

# Production
CIDI_STAGING_ENABLED=false php artisan cidi:generate all
```

## Troubleshooting

### Common Issues

1. **Permission Issues**
   ```bash
   sudo chown -R $USER:$USER storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

2. **Docker Build Failures**
   ```bash
   docker-compose down
   docker system prune -a
   docker-compose up --build
   ```

3. **Database Connection Issues**
   ```bash
   # Check if MySQL is running
   docker-compose ps mysql
   
   # Check logs
   docker-compose logs mysql
   ```

4. **Redis Connection Issues**
   ```bash
   # Check if Redis is running
   docker-compose ps redis
   
   # Test connection
   docker-compose exec redis redis-cli ping
   ```

### Debug Mode

```bash
# Enable debug mode
APP_DEBUG=true docker-compose up

# View detailed logs
docker-compose logs -f --tail=100
```

## Best Practices

1. **Always use version control** for generated files
2. **Review generated files** before deploying
3. **Test in staging** before production
4. **Use environment variables** for sensitive data
5. **Monitor logs** during deployment
6. **Keep backups** of your database
7. **Use health checks** for monitoring
8. **Set up notifications** for deployment status

## Support

If you encounter any issues or have questions:

- 📧 Email: subhashladumor1@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/subhashladumor1/laravel-cidi/issues)
- 📖 Documentation: [GitHub Wiki](https://github.com/subhashladumor1/laravel-cidi/wiki)
