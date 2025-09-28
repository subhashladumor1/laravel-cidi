# Laravel CIDI 🚀

[![Latest Version on Packagist](https://img.shields.io/packagist/v/subhashladumor1/laravel-cidi.svg?style=flat-square)](https://packagist.org/packages/subhashladumor1/laravel-cidi)
[![Total Downloads](https://img.shields.io/packagist/dt/subhashladumor1/laravel-cidi.svg?style=flat-square)](https://packagist.org/packages/subhashladumor1/laravel-cidi)
[![PHP Version](https://img.shields.io/packagist/php-v/subhashladumor1/laravel-cidi?style=flat-square)](https://packagist.org/packages/subhashladumor1/laravel-cidi)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.0%2B-red?style=flat-square)](https://laravel.com)

> **A powerful Laravel package that auto-generates Docker setup and CI/CD workflows for any Laravel project. Simplify Laravel deployment, pipeline, DevOps, and cloud integration with ready Dockerfile, docker-compose, and GitHub Actions. Fast, scalable, and time-saving for modern Laravel apps.**

## 🚀 Features

### 🐳 **Docker Integration**
- **Auto-generated Dockerfile** with PHP 8.3, Composer, Node.js, and essential extensions
- **Complete docker-compose.yml** with app, nginx, mysql, redis services
- **Smart .dockerignore** file for optimized builds
- **Multi-stage builds** for production optimization
- **Health checks** and monitoring capabilities

### 🚀 **CI/CD Automation**
- **GitHub Actions workflows** for testing and deployment
- **Multi-environment support** (staging, production)
- **Docker registry integration** (Docker Hub, GHCR)
- **Automated testing** with PHPUnit and Laravel Dusk
- **Security auditing** and code quality checks
- **Slack/Discord notifications** for deployment status

### ⚙️ **Advanced Configuration**
- **Modular services** - Enable/disable optional services (Horizon, Mailhog, Meilisearch, MinIO)
- **Environment-specific** configurations
- **Custom commands** support during installation
- **Flexible database** options (MySQL, PostgreSQL)
- **Production-ready** optimizations

## 📋 Requirements

### System Requirements
- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Docker**: 20.10+ (for Docker features)
- **Docker Compose**: 2.0+ (for multi-container setup)

### Optional Requirements
- **Node.js**: 18+ (for frontend assets)
- **NPM/Yarn**: For package management
- **Git**: For version control
- **SSH**: For deployment (if using CI/CD)

## 📦 Installation

```bash
# Install via Composer
composer require subhashladumor1/laravel-cidi

# Publish configuration
php artisan vendor:publish --provider="Subhashladumor1\LaravelCidi\LaravelCidiServiceProvider" --tag="cidi-config"

# Run installation command
php artisan cidi:install
```

## 🚀 Quick Start Guide

### Configuration Setup

```bash
# Publish configuration file
php artisan vendor:publish --provider="Subhashladumor1\LaravelCidi\LaravelCidiServiceProvider" --tag="cidi-config"

# This creates config/cidi.php with all available options
```

### Install Package

```bash
# Basic installation
php artisan cidi:install

# With database migration
php artisan cidi:install --migrate

# With database seeding
php artisan cidi:install --seed

# With custom commands
php artisan cidi:install --command="migrate:fresh" --command="db:seed --class=UserSeeder"

# Force overwrite existing files
php artisan cidi:install --force
```

### Generate Docker Configuration

```bash
# Generate all Docker files
php artisan cidi:docker

# Generate with specific services
php artisan cidi:docker --services=horizon,mailhog,meilisearch

# Force overwrite existing files
php artisan cidi:docker --force
```

### Generate CI/CD Workflows

```bash
# Generate all workflows
php artisan cidi:workflow

# Generate only CI workflow
php artisan cidi:workflow --type=ci

# Generate only deploy workflow
php artisan cidi:workflow --type=deploy

# Force overwrite existing workflows
php artisan cidi:workflow --force
```

### Start Docker Services

```bash
# Start all services
docker-compose up -d

# Start specific services
docker-compose up -d app nginx mysql redis

# Start with optional services
docker-compose --profile horizon --profile mailhog up -d

# View logs
docker-compose logs -f
```

### Verify Installation

```bash
# Check if services are running
docker-compose ps

# Test database connection
docker-compose exec app php artisan migrate:status

# Check application health
curl http://localhost:8080
```

## 🐳 Docker Services

### Core Services

| Service | Description | Port | Image |
|---------|-------------|------|-------|
| **app** | Laravel application with PHP-FPM | 9000 | `php:8.3-fpm-alpine` |
| **nginx** | Web server and reverse proxy | 8080 | `nginx:alpine` |
| **mysql** | Database server | 3306 | `mysql:8.0` |
| **redis** | Cache and session storage | 6379 | `redis:7-alpine` |

### Optional Services

| Service | Description | Port | When to Use |
|---------|-------------|------|-------------|
| **horizon** | Queue worker (Laravel Horizon) | - | Production queue processing |
| **mailhog** | Email testing | 8025 | Development email testing |
| **meilisearch** | Search engine | 7700 | Full-text search functionality |
| **minio** | S3-compatible storage | 9000 | File storage and uploads |


## 🚀 CI/CD Workflows

### CI Workflow (`laravel-ci.yml`)

**Features:**
- PHP 8.3 setup with extensions
- Composer dependency installation
- NPM dependency installation and build
- PHPUnit testing with coverage
- Laravel Dusk testing (optional)
- Security auditing
- Docker image building
- Code quality checks

**Triggers:**
- Pull requests to main branch
- Pushes to main branch
- Manual workflow dispatch

### Deploy Workflow (`laravel-deploy.yml`)

**Features:**
- Staging and production deployments
- SSH-based deployment
- Docker registry support (Docker Hub, GHCR)
- Slack/Discord notifications
- Health checks
- Automatic migrations and cache clearing
- Rollback capabilities

**Environments:**
- **Staging**: Auto-deploy from develop branch
- **Production**: Auto-deploy from main branch

## ⚙️ Configuration

### Basic Configuration

```php
// config/cidi.php
return [
    'php_version' => '8.3', // 8.2 or 8.3
    
    'database' => [
        'type' => 'mysql', // mysql or postgres
        'version' => '8.0',
        'port' => '3306',
        'database' => 'laravel',
        'username' => 'laravel',
        'password' => 'password',
    ],
    
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
            'server' => env('CIDI_STAGING_SERVER'),
            'user' => env('CIDI_STAGING_USER'),
            'path' => '/var/www/staging',
        ],
        'production' => [
            'enabled' => true,
            'server' => env('CIDI_PRODUCTION_SERVER'),
            'user' => env('CIDI_PRODUCTION_USER'),
            'path' => '/var/www/production',
        ],
    ],
];
```

### Environment Variables

Add these to your `.env` file:

```env
# PHP Version
CIDI_PHP_VERSION=8.3

# Database Configuration
CIDI_DB_TYPE=mysql
CIDI_DB_VERSION=8.0
CIDI_DB_PORT=3306
CIDI_DB_DATABASE=laravel
CIDI_DB_USERNAME=laravel
CIDI_DB_PASSWORD=password

# Services Configuration
CIDI_HORIZON_ENABLED=true
CIDI_MAILHOG_ENABLED=true
CIDI_MEILISEARCH_ENABLED=false
CIDI_MINIO_ENABLED=false
CIDI_REDIS_ENABLED=true

# CI/CD Configuration
CIDI_STAGING_SERVER=staging.example.com
CIDI_STAGING_USER=deploy
CIDI_STAGING_PATH=/var/www/staging
CIDI_PRODUCTION_SERVER=production.example.com
CIDI_PRODUCTION_USER=deploy
CIDI_PRODUCTION_PATH=/var/www/production

# Registry Configuration
CIDI_REGISTRY_ENABLED=true
CIDI_REGISTRY_TYPE=ghcr
CIDI_REGISTRY_USERNAME=your-username
CIDI_REGISTRY_REPOSITORY=your-repo

# Notifications
CIDI_SLACK_ENABLED=true
CIDI_SLACK_WEBHOOK=https://hooks.slack.com/services/...
CIDI_DISCORD_ENABLED=false
CIDI_DISCORD_WEBHOOK=https://discord.com/api/webhooks/...
```

## 🔧 Troubleshooting

### Common Docker Issues

#### 1. **Docker Build Context Error**

**Error:**
```
failed to solve: invalid file request public/storage
```

**Solution:**
```bash
# Create the missing directory
mkdir -p public/storage

# Create storage link
php artisan storage:link

# Clean Docker build cache
docker system prune -a

# Rebuild with no cache
docker-compose build --no-cache

# Start services
docker-compose up -d
```

#### 2. **Database Connection Error**

**Error:**
```
SQLSTATE[HY000] [2002] No such file or directory
```

**Solution:**
```bash
# Check if MySQL container is running
docker-compose ps mysql

# Check MySQL logs
docker-compose logs mysql

# Restart MySQL service
docker-compose restart mysql

# Wait for MySQL to be ready
docker-compose exec mysql mysqladmin ping -h localhost

# Test connection
docker-compose exec app php artisan migrate:status
```

#### 3. **Permission Issues**

**Error:**
```
Permission denied on storage directory
```

**Solution:**
```bash
# Fix storage permissions
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Or run with Docker
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

#### 4. **Port Already in Use**

**Error:**
```
Port 8080 is already in use
```

**Solution:**
```bash
# Check what's using the port
lsof -i :8080

# Kill the process
kill -9 <PID>

# Or change the port in docker-compose.yml
# ports:
#   - "8081:80"
```

#### 5. **Docker Build Failures**

**Error:**
```
Build failed with exit code 1
```

**Solution:**
```bash
# Clean everything
docker-compose down
docker system prune -a
docker volume prune

# Rebuild from scratch
docker-compose build --no-cache
docker-compose up -d

# Check build logs
docker-compose logs app
```

### Database Issues

#### 1. **MySQL Connection Refused**

```bash
# Check MySQL status
docker-compose exec mysql mysqladmin ping

# Check MySQL configuration
docker-compose exec mysql cat /etc/mysql/my.cnf

# Restart MySQL
docker-compose restart mysql

# Check logs
docker-compose logs mysql
```

#### 2. **Database Not Found**

```bash
# Create database
docker-compose exec mysql mysql -u root -p -e "CREATE DATABASE laravel;"

# Grant permissions
docker-compose exec mysql mysql -u root -p -e "GRANT ALL PRIVILEGES ON laravel.* TO 'laravel'@'%';"

# Flush privileges
docker-compose exec mysql mysql -u root -p -e "FLUSH PRIVILEGES;"
```

### Redis Issues

#### 1. **Redis Connection Failed**

```bash
# Check Redis status
docker-compose exec redis redis-cli ping

# Check Redis logs
docker-compose logs redis

# Restart Redis
docker-compose restart redis
```

### Application Issues

#### 1. **Laravel Application Not Loading**

```bash
# Check application logs
docker-compose logs app

# Check nginx logs
docker-compose logs nginx

# Restart application
docker-compose restart app nginx

# Clear Laravel caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

#### 2. **Composer Dependencies Issues**

```bash
# Install dependencies
docker-compose exec app composer install

# Update dependencies
docker-compose exec app composer update

# Clear composer cache
docker-compose exec app composer clear-cache
```

### Debug Mode

```bash
# Enable debug mode
APP_DEBUG=true docker-compose up

# View detailed logs
docker-compose logs -f --tail=100

# Check container status
docker-compose ps

# Inspect container
docker-compose exec app php artisan config:show
```

## 📚 API Reference

### Available Commands

| Command | Description | Options |
|---------|-------------|---------|
| `php artisan cidi:install` | Install package and publish config files | `--migrate`, `--seed`, `--force`, `--command` |
| `php artisan cidi:docker` | Generate Docker configuration files | `--services`, `--force` |
| `php artisan cidi:workflow` | Generate GitHub Actions workflows | `--type`, `--force` |
| `php artisan cidi:generate all` | Generate all configuration files | `--force` |

### Command Options

#### `cidi:install` Options

```bash
--migrate              Run database migrations
--seed                 Run database seeders
--force                Overwrite existing files
--command="command"    Run custom commands (can be used multiple times)
```

#### `cidi:docker` Options

```bash
--services="service1,service2"    Enable specific services
--force                          Overwrite existing files
```

#### `cidi:workflow` Options

```bash
--type="ci|deploy"    Generate specific workflow type
--force              Overwrite existing files
```

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.


## 🐛 Bug Reports

If you discover a bug, please open an issue with:

- **Description** of the issue
- **Steps to reproduce** the problem
- **Expected behavior**
- **Actual behavior**
- **Environment details** (OS, PHP version, Laravel version, etc.)
- **Screenshots** (if applicable)

## 💡 Feature Requests

We welcome feature requests! Please open an issue with:

- **Clear description** of the feature
- **Use case** and benefits
- **Possible implementation** approach
- **Additional context** or examples

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## 🙏 Credits

- [Subhash Ladumor](https://github.com/subhashladumor1) - Creator and Maintainer
- [All Contributors](../../contributors) - Thank you for your contributions!


### Get Help

- 🐛 **Issues**: [GitHub Issues](https://github.com/subhashladumor1/laravel-cidi/issues)
- 💬 **Discussions**: [GitHub Discussions](https://github.com/subhashladumor1/laravel-cidi/discussions)

### Community

- ⭐ **Star** the repository if you find it useful
- 🍴 **Fork** the repository to contribute
- 📢 **Share** with your team and colleagues
- 🐛 **Report** bugs and issues
- 💡 **Suggest** new features

---

<div align="center">

**Made with ❤️ by [Subhash Ladumor](https://github.com/subhashladumor1)**

[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/subhashladumor1)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/subhash-ladumor)

</div>
