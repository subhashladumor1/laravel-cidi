# Laravel CIDI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/subhashladumor1/laravel-cidi.svg?style=flat-square)](https://packagist.org/packages/subhashladumor1/laravel-cidi)
[![Total Downloads](https://img.shields.io/packagist/dt/subhashladumor1/laravel-cidi.svg?style=flat-square)](https://packagist.org/packages/subhashladumor1/laravel-cidi)
[![Build Status](https://img.shields.io/github/workflow/status/subhashladumor1/laravel-cidi/tests?label=tests&style=flat-square)](https://github.com/subhashladumor1/laravel-cidi/actions)
[![License](https://img.shields.io/packagist/l/subhashladumor1/laravel-cidi.svg?style=flat-square)](https://packagist.org/packages/subhashladumor1/laravel-cidi)

A powerful Laravel package that auto-generates Docker setup and CI/CD workflows for any Laravel project. Simplify Laravel deployment, pipeline, DevOps, and cloud integration with ready Dockerfile, docker-compose, and GitHub Actions. Fast, scalable, and time-saving for modern Laravel apps.

## 🚀 Features

- **🐳 Docker Setup**: Auto-generate Dockerfile, docker-compose.yml, and .dockerignore
- **🚀 CI/CD Workflows**: Generate GitHub Actions workflows for testing and deployment
- **⚙️ Configurable**: Extensive configuration options for different environments
- **🔧 Modular**: Enable/disable optional services (Horizon, Mailhog, Meilisearch, MinIO)
- **📦 Production Ready**: Optimized for production with health checks and best practices
- **🧪 Tested**: Comprehensive test suite with 100% code coverage
- **📚 Well Documented**: Clear documentation and examples

## 📋 Requirements

- PHP 8.2+
- Laravel 9.0+ | 10.0+ | 11.0+
- Docker (for Docker features)
- Composer

## 📦 Installation

You can install the package via Composer:

```bash
composer require subhashladumor1/laravel-cidi
```

## ⚙️ Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Subhashladumor1\LaravelCidi\LaravelCidiServiceProvider" --tag="cidi-config"
```

This will create `config/cidi.php` with all available options.

## 🚀 Quick Start

### 1. Install the package

```bash
php artisan cidi:install
```

This command will:
- Publish the configuration file
- Publish stub files
- Create `.env.docker` file

### 2. Generate Docker setup

```bash
php artisan cidi:docker
```

This will generate:
- `Dockerfile` with PHP 8.3, Composer, Node.js, and extensions
- `docker-compose.yml` with app, nginx, mysql, redis services
- `.dockerignore` file

### 3. Generate CI/CD workflows

```bash
php artisan cidi:workflow
```

This will generate:
- `.github/workflows/laravel-ci.yml` - CI workflow for testing
- `.github/workflows/laravel-deploy.yml` - Deployment workflow

### 4. Generate everything at once

```bash
php artisan cidi:generate all
```

## 🐳 Docker Services

### Core Services
- **app**: Laravel application with PHP-FPM
- **nginx**: Web server
- **mysql**: Database (MySQL 8.0)
- **redis**: Cache and session storage

### Optional Services
- **horizon**: Queue worker (Laravel Horizon)
- **mailhog**: Email testing
- **meilisearch**: Search engine
- **minio**: S3-compatible storage

## 🚀 CI/CD Features

### CI Workflow (`laravel-ci.yml`)
- PHP 8.3 setup with extensions
- Composer dependency installation
- NPM dependency installation and build
- PHPUnit testing with coverage
- Laravel Dusk testing (optional)
- Security auditing
- Docker image building

### Deploy Workflow (`laravel-deploy.yml`)
- Staging and production deployments
- SSH-based deployment
- Docker registry support (Docker Hub, GHCR)
- Slack/Discord notifications
- Health checks
- Automatic migrations and cache clearing

## ⚙️ Configuration Options

### PHP Version
```php
'php_version' => '8.3', // 8.2 or 8.3
```

### Database Configuration
```php
'database' => [
    'type' => 'mysql', // mysql or postgres
    'version' => '8.0',
    'port' => '3306',
    'database' => 'laravel',
    'username' => 'laravel',
    'password' => 'password',
],
```

### Optional Services
```php
'services' => [
    'horizon' => false,
    'mailhog' => true,
    'meilisearch' => false,
    'minio' => false,
    'redis' => true,
],
```

### CI/CD Configuration
```php
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
```

## 🎯 Usage Examples

### Basic Docker Setup
```bash
# Install package
php artisan cidi:install

# Generate Docker files
php artisan cidi:docker

# Start services
docker-compose up -d

# Run migrations
php artisan migrate
```

### Advanced Configuration
```bash
# Generate with specific services
php artisan cidi:docker --services=horizon,mailhog

# Generate with force (overwrite existing files)
php artisan cidi:docker --force

# Generate specific workflow types
php artisan cidi:workflow --type=ci
php artisan cidi:workflow --type=deploy
```

### Environment Variables
Add these to your `.env` file:

```env
# PHP Version
CIDI_PHP_VERSION=8.3

# Database
CIDI_DB_TYPE=mysql
CIDI_DB_VERSION=8.0

# Services
CIDI_HORIZON_ENABLED=true
CIDI_MAILHOG_ENABLED=true
CIDI_MEILISEARCH_ENABLED=false
CIDI_MINIO_ENABLED=false

# CI/CD
CIDI_STAGING_SERVER=staging.example.com
CIDI_STAGING_USER=deploy
CIDI_PRODUCTION_SERVER=production.example.com
CIDI_PRODUCTION_USER=deploy

# Notifications
CIDI_SLACK_ENABLED=true
CIDI_SLACK_WEBHOOK=https://hooks.slack.com/services/...
```

## 🧪 Testing

Run the tests with:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

## 📚 Available commands

| Command | Description |
|---------|-------------|
| `php artisan cidi:install` | Install package and publish config files |
| `php artisan cidi:docker` | Generate Docker configuration files |
| `php artisan cidi:workflow` | Generate GitHub Actions workflows |
| `php artisan cidi:generate all` | Generate all configuration files |

## 🔧 Advanced Features

### Multi-Environment Workflows
- Separate staging and production deployments
- Environment-specific configurations
- Conditional deployments based on branches

### Health Checks
- Docker container health checks
- Application health endpoints
- Post-deployment verification

### Notifications
- Slack webhook integration
- Discord webhook integration
- Deployment status notifications

### Security
- Security audit in CI pipeline
- Code quality checks
- Dependency vulnerability scanning

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📝 Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## 🐛 Bug Reports

If you discover a bug, please open an issue with:
- Description of the issue
- Steps to reproduce
- Expected behavior
- Actual behavior
- Environment details

## 💡 Feature Requests

We welcome feature requests! Please open an issue with:
- Clear description of the feature
- Use case and benefits
- Possible implementation approach

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## 🙏 Credits

- [Subhash Ladumor](https://github.com/subhashladumor1)
- [All Contributors](../../contributors)

## 📞 Support

- 📧 Email: subhashladumor1@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/subhashladumor1/laravel-cidi/issues)
- 📖 Documentation: [GitHub Wiki](https://github.com/subhashladumor1/laravel-cidi/wiki)

---

Made with ❤️ by [Subhash Ladumor](https://github.com/subhashladumor1)