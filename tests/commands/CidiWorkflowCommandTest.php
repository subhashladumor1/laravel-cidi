<?php

namespace Subhashladumor1\LaravelCidi\Tests\commands;

use Illuminate\Support\Facades\File;
use Subhashladumor1\LaravelCidi\Tests\TestCase;

class CidiWorkflowCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clean up any existing files
        if (File::exists(base_path('.github/workflows'))) {
            File::deleteDirectory(base_path('.github'));
        }
    }

    /** @test */
    public function it_can_generate_workflow_files_successfully()
    {
        $this->artisan('cidi:workflow')
            ->expectsOutput('🚀 Generating GitHub Actions workflow files...')
            ->expectsOutput('✅ Workflow file laravel-ci.yml generated successfully!')
            ->expectsOutput('✅ Workflow file laravel-deploy.yml generated successfully!')
            ->expectsOutput('✅ GitHub Actions workflow files generated successfully!')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('.github/workflows/laravel-ci.yml')));
        $this->assertTrue(File::exists(base_path('.github/workflows/laravel-deploy.yml')));
    }

    /** @test */
    public function it_can_generate_specific_workflow_types()
    {
        $this->artisan('cidi:workflow --type=ci')
            ->expectsOutput('🚀 Generating GitHub Actions workflow files...')
            ->expectsOutput('✅ Workflow file laravel-ci.yml generated successfully!')
            ->expectsOutput('✅ GitHub Actions workflow files generated successfully!')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('.github/workflows/laravel-ci.yml')));
        $this->assertFalse(File::exists(base_path('.github/workflows/laravel-deploy.yml')));
    }

    /** @test */
    public function it_generates_ci_workflow_with_correct_content()
    {
        $this->artisan('cidi:workflow --type=ci')->assertExitCode(0);

        $ciContent = File::get(base_path('.github/workflows/laravel-ci.yml'));
        
        $this->assertStringContainsString('name: Laravel CI', $ciContent);
        $this->assertStringContainsString('runs-on: ubuntu-latest', $ciContent);
        $this->assertStringContainsString('php-version:', $ciContent);
        $this->assertStringContainsString('composer install', $ciContent);
        $this->assertStringContainsString('npm ci', $ciContent);
    }

    /** @test */
    public function it_generates_deploy_workflow_with_correct_content()
    {
        $this->artisan('cidi:workflow --type=deploy')->assertExitCode(0);

        $deployContent = File::get(base_path('.github/workflows/laravel-deploy.yml'));
        
        $this->assertStringContainsString('name: Laravel Deploy', $deployContent);
        $this->assertStringContainsString('deploy-staging:', $deployContent);
        $this->assertStringContainsString('deploy-production:', $deployContent);
        $this->assertStringContainsString('appleboy/ssh-action', $deployContent);
    }

    /** @test */
    public function it_can_generate_with_force_option()
    {
        // First generation
        $this->artisan('cidi:workflow')->assertExitCode(0);
        
        // Second generation with force
        $this->artisan('cidi:workflow --force')
            ->expectsOutput('🚀 Generating GitHub Actions workflow files...')
            ->expectsOutput('✅ Workflow file laravel-ci.yml generated successfully!')
            ->expectsOutput('✅ Workflow file laravel-deploy.yml generated successfully!')
            ->expectsOutput('✅ GitHub Actions workflow files generated successfully!')
            ->assertExitCode(0);
    }
}

