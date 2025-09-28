<?php

namespace Subhashladumor1\LaravelCidi\Tests\commands;

use Illuminate\Support\Facades\File;
use Subhashladumor1\LaravelCidi\Tests\TestCase;

class CidiDockerCommandTest extends TestCase
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
    }

    /** @test */
    public function it_can_generate_docker_files_successfully()
    {
        $this->artisan('cidi:docker')
            ->expectsOutput('🐳 Generating Docker configuration files...')
            ->expectsOutput('✅ Dockerfile generated successfully!')
            ->expectsOutput('✅ docker-compose.yml generated successfully!')
            ->expectsOutput('✅ .dockerignore generated successfully!')
            ->expectsOutput('✅ Docker configuration files generated successfully!')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('Dockerfile')));
        $this->assertTrue(File::exists(base_path('docker-compose.yml')));
        $this->assertTrue(File::exists(base_path('.dockerignore')));
    }

    /** @test */
    public function it_generates_dockerfile_with_correct_php_version()
    {
        config(['cidi.php_version' => '8.2']);
        
        $this->artisan('cidi:docker')->assertExitCode(0);

        $dockerfileContent = File::get(base_path('Dockerfile'));
        $this->assertStringContainsString('FROM php:8.2-fpm-alpine', $dockerfileContent);
    }

    /** @test */
    public function it_generates_docker_compose_with_correct_services()
    {
        $this->artisan('cidi:docker')->assertExitCode(0);

        $composeContent = File::get(base_path('docker-compose.yml'));
        
        $this->assertStringContainsString('services:', $composeContent);
        $this->assertStringContainsString('app:', $composeContent);
        $this->assertStringContainsString('nginx:', $composeContent);
        $this->assertStringContainsString('mysql:', $composeContent);
        $this->assertStringContainsString('redis:', $composeContent);
    }

    /** @test */
    public function it_can_generate_with_force_option()
    {
        // First generation
        $this->artisan('cidi:docker')->assertExitCode(0);
        
        // Second generation with force
        $this->artisan('cidi:docker --force')
            ->expectsOutput('🐳 Generating Docker configuration files...')
            ->expectsOutput('✅ Dockerfile generated successfully!')
            ->expectsOutput('✅ docker-compose.yml generated successfully!')
            ->expectsOutput('✅ .dockerignore generated successfully!')
            ->expectsOutput('✅ Docker configuration files generated successfully!')
            ->assertExitCode(0);
    }
}

