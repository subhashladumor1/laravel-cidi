<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PHP Version
    |--------------------------------------------------------------------------
    |
    | The PHP version to use in Docker containers.
    | Supported versions: 8.2, 8.3
    |
    */
    'php_version' => env('CIDI_PHP_VERSION', '8.3'),

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Database type and configuration for Docker setup.
    | Supported types: mysql, postgres
    |
    */
    'database' => [
        'type' => env('CIDI_DB_TYPE', 'mysql'),
        'version' => env('CIDI_DB_VERSION', '8.0'),
        'port' => env('CIDI_DB_PORT', '3306'),
        'database' => env('CIDI_DB_DATABASE', 'laravel'),
        'username' => env('CIDI_DB_USERNAME', 'laravel'),
        'password' => env('CIDI_DB_PASSWORD', 'password'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Optional Services
    |--------------------------------------------------------------------------
    |
    | Enable or disable optional services in Docker setup.
    |
    */
    'services' => [
        'horizon' => env('CIDI_HORIZON_ENABLED', false),
        'mailhog' => env('CIDI_MAILHOG_ENABLED', true),
        'meilisearch' => env('CIDI_MEILISEARCH_ENABLED', false),
        'minio' => env('CIDI_MINIO_ENABLED', false),
        'redis' => env('CIDI_REDIS_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Docker Configuration
    |--------------------------------------------------------------------------
    |
    | Docker-specific configuration options.
    |
    */
    'docker' => [
        'app_port' => env('CIDI_APP_PORT', '8000'),
        'nginx_port' => env('CIDI_NGINX_PORT', '80'),
        'mysql_port' => env('CIDI_MYSQL_PORT', '3306'),
        'redis_port' => env('CIDI_REDIS_PORT', '6379'),
        'mailhog_port' => env('CIDI_MAILHOG_PORT', '8025'),
        'meilisearch_port' => env('CIDI_MEILISEARCH_PORT', '7700'),
        'minio_port' => env('CIDI_MINIO_PORT', '9000'),
        'minio_console_port' => env('CIDI_MINIO_CONSOLE_PORT', '9001'),
    ],

    /*
    |--------------------------------------------------------------------------
    | CI/CD Configuration
    |--------------------------------------------------------------------------
    |
    | GitHub Actions and deployment configuration.
    |
    */
    'cicd' => [
        'staging' => [
            'enabled' => env('CIDI_STAGING_ENABLED', true),
            'server' => env('CIDI_STAGING_SERVER'),
            'user' => env('CIDI_STAGING_USER'),
            'path' => env('CIDI_STAGING_PATH', '/var/www/staging'),
        ],
        'production' => [
            'enabled' => env('CIDI_PRODUCTION_ENABLED', true),
            'server' => env('CIDI_PRODUCTION_SERVER'),
            'user' => env('CIDI_PRODUCTION_USER'),
            'path' => env('CIDI_PRODUCTION_PATH', '/var/www/production'),
        ],
        'registry' => [
            'enabled' => env('CIDI_REGISTRY_ENABLED', false),
            'type' => env('CIDI_REGISTRY_TYPE', 'dockerhub'), // dockerhub, ghcr
            'username' => env('CIDI_REGISTRY_USERNAME'),
            'repository' => env('CIDI_REGISTRY_REPOSITORY'),
        ],
        'notifications' => [
            'slack' => [
                'enabled' => env('CIDI_SLACK_ENABLED', false),
                'webhook' => env('CIDI_SLACK_WEBHOOK'),
            ],
            'discord' => [
                'enabled' => env('CIDI_DISCORD_ENABLED', false),
                'webhook' => env('CIDI_DISCORD_WEBHOOK'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Configuration
    |--------------------------------------------------------------------------
    |
    | Testing and quality assurance configuration.
    |
    */
    'testing' => [
        'dusk_enabled' => env('CIDI_DUSK_ENABLED', false),
        'phpunit_coverage' => env('CIDI_PHPUNIT_COVERAGE', false),
        'parallel_tests' => env('CIDI_PARALLEL_TESTS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Features
    |--------------------------------------------------------------------------
    |
    | Advanced DevOps and deployment features.
    |
    */
    'advanced' => [
        'healthcheck_enabled' => env('CIDI_HEALTHCHECK_ENABLED', true),
        'kubernetes_stubs' => env('CIDI_KUBERNETES_STUBS', false),
        'multi_env_workflow' => env('CIDI_MULTI_ENV_WORKFLOW', true),
        'auto_migrations' => env('CIDI_AUTO_MIGRATIONS', true),
        'cache_clear' => env('CIDI_CACHE_CLEAR', true),
    ],
];
