<?php

namespace Subhashladumor1\LaravelCidi\Tests\commands;

use Illuminate\Support\Facades\File;
use Subhashladumor1\LaravelCidi\Tests\TestCase;

class CidiInstallCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clean up any existing files
        if (File::exists(config_path('cidi.php'))) {
            File::delete(config_path('cidi.php'));
        }
        
        if (File::exists(resource_path('stubs/cidi'))) {
            File::deleteDirectory(resource_path('stubs/cidi'));
        }
        
        if (File::exists(base_path('.env.docker'))) {
            File::delete(base_path('.env.docker'));
        }
    }

    /** @test */
    public function it_can_install_package_successfully()
    {
        $this->artisan('cidi:install')
            ->expectsOutput('🚀 Installing Laravel CIDI package...')
            ->expectsOutput('✅ Configuration file published successfully!')
            ->expectsOutput('✅ Stub files published successfully!')
            ->expectsOutput('✅ .env.docker file created successfully!')
            ->expectsOutput('✅ Laravel CIDI package installed successfully!')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(config_path('cidi.php')));
        $this->assertTrue(File::exists(resource_path('stubs/cidi')));
        $this->assertTrue(File::exists(base_path('.env.docker')));
    }

    /** @test */
    public function it_can_install_with_force_option()
    {
        // First installation
        $this->artisan('cidi:install')->assertExitCode(0);
        
        // Second installation with force
        $this->artisan('cidi:install --force')
            ->expectsOutput('🚀 Installing Laravel CIDI package...')
            ->expectsOutput('✅ Configuration file published successfully!')
            ->expectsOutput('✅ Stub files published successfully!')
            ->expectsOutput('✅ .env.docker file created successfully!')
            ->expectsOutput('✅ Laravel CIDI package installed successfully!')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_creates_env_docker_file_with_correct_content()
    {
        $this->artisan('cidi:install')->assertExitCode(0);

        $envDockerContent = File::get(base_path('.env.docker'));
        
        $this->assertStringContainsString('APP_NAME=Laravel', $envDockerContent);
        $this->assertStringContainsString('DB_CONNECTION=mysql', $envDockerContent);
        $this->assertStringContainsString('DB_HOST=mysql', $envDockerContent);
        $this->assertStringContainsString('REDIS_HOST=redis', $envDockerContent);
    }
}

