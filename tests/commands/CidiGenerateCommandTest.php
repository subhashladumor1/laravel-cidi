<?php

namespace Subhashladumor1\LaravelCidi\Tests\commands;

use Illuminate\Support\Facades\File;
use Subhashladumor1\LaravelCidi\Tests\TestCase;

class CidiGenerateCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clean up any existing files
        if (File::exists(base_path('Dockerfile'))) {
            File::delete(base_path('Dockerfile'));
        }
        
        if (File::exists(base_path('docker-compose.yml'))) {
            File::delete(base_path('docker-compose.yml'));
        }
        
        if (File::exists(base_path('.dockerignore'))) {
            File::delete(base_path('.dockerignore'));
        }
        
        if (File::exists(base_path('.github/workflows'))) {
            File::deleteDirectory(base_path('.github'));
        }
    }

    /** @test */
    public function it_can_generate_all_files_successfully()
    {
        $this->artisan('cidi:generate all')
            ->expectsOutput('🚀 Generating Laravel CIDI configuration files...')
            ->expectsOutput('📦 Generating all configuration files...')
            ->expectsOutput('🐳 Generating Docker configuration...')
            ->expectsOutput('🚀 Generating GitHub Actions workflows...')
            ->expectsOutput('🎉 Your Laravel project is now ready for Docker and CI/CD!')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('Dockerfile')));
        $this->assertTrue(File::exists(base_path('docker-compose.yml')));
        $this->assertTrue(File::exists(base_path('.dockerignore')));
        $this->assertTrue(File::exists(base_path('.github/workflows/laravel-ci.yml')));
        $this->assertTrue(File::exists(base_path('.github/workflows/laravel-deploy.yml')));
    }

    /** @test */
    public function it_can_generate_docker_only()
    {
        $this->artisan('cidi:generate docker')
            ->expectsOutput('🚀 Generating Laravel CIDI configuration files...')
            ->expectsOutput('🐳 Generating Docker configuration...')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('Dockerfile')));
        $this->assertTrue(File::exists(base_path('docker-compose.yml')));
        $this->assertTrue(File::exists(base_path('.dockerignore')));
        $this->assertFalse(File::exists(base_path('.github/workflows/laravel-ci.yml')));
    }

    /** @test */
    public function it_can_generate_workflow_only()
    {
        $this->artisan('cidi:generate workflow')
            ->expectsOutput('🚀 Generating Laravel CIDI configuration files...')
            ->expectsOutput('🚀 Generating GitHub Actions workflows...')
            ->assertExitCode(0);

        $this->assertFalse(File::exists(base_path('Dockerfile')));
        $this->assertTrue(File::exists(base_path('.github/workflows/laravel-ci.yml')));
        $this->assertTrue(File::exists(base_path('.github/workflows/laravel-deploy.yml')));
    }

    /** @test */
    public function it_handles_invalid_type()
    {
        $this->artisan('cidi:generate invalid')
            ->expectsOutput('❌ Invalid type: invalid. Available types: all, docker, workflow')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_can_generate_with_force_option()
    {
        // First generation
        $this->artisan('cidi:generate all')->assertExitCode(0);
        
        // Second generation with force
        $this->artisan('cidi:generate all --force')
            ->expectsOutput('🚀 Generating Laravel CIDI configuration files...')
            ->expectsOutput('📦 Generating all configuration files...')
            ->expectsOutput('🐳 Generating Docker configuration...')
            ->expectsOutput('🚀 Generating GitHub Actions workflows...')
            ->expectsOutput('🎉 Your Laravel project is now ready for Docker and CI/CD!')
            ->assertExitCode(0);
    }
}

