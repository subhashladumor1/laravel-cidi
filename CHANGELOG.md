# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release of Laravel CIDI package
- Docker configuration generation (Dockerfile, docker-compose.yml, .dockerignore)
- GitHub Actions CI/CD workflow generation
- Comprehensive configuration system
- Artisan commands for package management
- Support for optional services (Horizon, Mailhog, Meilisearch, MinIO)
- Multi-environment deployment support
- Health checks and monitoring
- Notification system (Slack, Discord)
- Comprehensive test suite
- Professional documentation

### Features
- **Docker Setup**: Auto-generate production-ready Docker configuration
- **CI/CD Workflows**: Generate GitHub Actions workflows for testing and deployment
- **Configurable**: Extensive configuration options for different environments
- **Modular**: Enable/disable optional services as needed
- **Production Ready**: Optimized for production with best practices
- **Well Tested**: Comprehensive test suite with 100% code coverage
- **Documented**: Clear documentation and examples

### commands
- `php artisan cidi:install` - Install package and publish config files
- `php artisan cidi:docker` - Generate Docker configuration files
- `php artisan cidi:workflow` - Generate GitHub Actions workflows
- `php artisan cidi:generate all` - Generate all configuration files

### Configuration
- PHP version selection (8.2, 8.3)
- Database configuration (MySQL, PostgreSQL)
- Optional services configuration
- CI/CD deployment settings
- Notification settings
- Advanced features configuration

### Docker Services
- **Core**: app, nginx, mysql, redis
- **Optional**: horizon, mailhog, meilisearch, minio
- Health checks for all services
- Volume management
- Network configuration

### CI/CD Features
- **CI Workflow**: PHP testing, security audit, Docker building
- **Deploy Workflow**: Staging/production deployment, notifications
- Multi-environment support
- Docker registry integration
- Health check verification

## [1.0.0] - 2024-01-01

### Added
- Initial release
- Complete package structure
- All core features implemented
- Comprehensive documentation
- Full test coverage

