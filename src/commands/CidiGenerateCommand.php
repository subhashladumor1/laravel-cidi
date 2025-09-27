<?php

namespace Subhashladumor1\LaravelCidi\commands;

class CidiGenerateCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cidi:generate 
                            {type=all : Type of generation (all, docker, workflow)}
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Generate all Docker and CI/CD configuration files at once';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type');
        $force = $this->option('force');

        $this->info('🚀 Generating Laravel CIDI configuration files...');

        switch ($type) {
            case 'all':
                $this->generateAll($force);
                break;
            case 'docker':
                $this->generateDocker($force);
                break;
            case 'workflow':
                $this->generateWorkflow($force);
                break;
            default:
                $this->error("Invalid type: {$type}. Available types: all, docker, workflow");
                return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Generate all configuration files.
     */
    private function generateAll(bool $force): void
    {
        $this->info('📦 Generating all configuration files...');

        // Generate Docker files
        $this->generateDocker($force);

        // Generate workflow files
        $this->generateWorkflow($force);

        $this->success('All configuration files generated successfully!');
        $this->line('');
        $this->line('🎉 Your Laravel project is now ready for Docker and CI/CD!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Review the generated files');
        $this->line('2. Configure your environment variables');
        $this->line('3. Run: docker-compose up -d');
        $this->line('4. Configure your repository secrets for CI/CD');
    }

    /**
     * Generate Docker configuration files.
     */
    private function generateDocker(bool $force): void
    {
        $this->info('🐳 Generating Docker configuration...');
        
        $this->call('cidi:docker', [
            '--force' => $force,
        ]);
    }

    /**
     * Generate workflow files.
     */
    private function generateWorkflow(bool $force): void
    {
        $this->info('🚀 Generating GitHub Actions workflows...');
        
        $this->call('cidi:workflow', [
            '--force' => $force,
        ]);
    }
}
