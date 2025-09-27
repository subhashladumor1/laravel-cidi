<?php

namespace Subhashladumor1\LaravelCidi\commands;

use Illuminate\Support\Facades\File;

class CidiWorkflowCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cidi:workflow 
                            {--force : Overwrite existing files}
                            {--type=* : Workflow types to generate (ci,deploy)}';

    /**
     * The console command description.
     */
    protected $description = 'Generate GitHub Actions CI/CD workflow files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Generating GitHub Actions workflow files...');

        $types = $this->option('type') ?: ['ci', 'deploy'];

        foreach ($types as $type) {
            $this->generateWorkflow($type);
        }

        $this->success('GitHub Actions workflow files generated successfully!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Review the generated workflow files');
        $this->line('2. Configure your repository secrets');
        $this->line('3. Push your changes to trigger the workflows');

        return self::SUCCESS;
    }

    /**
     * Generate workflow file.
     */
    private function generateWorkflow(string $type): void
    {
        $workflowDir = base_path('.github/workflows');
        $workflowFile = $workflowDir . "/laravel-{$type}.yml";
        $force = $this->option('force');

        if (File::exists($workflowFile) && !$force) {
            $this->warning("Workflow file laravel-{$type}.yml already exists. Use --force to overwrite.");
            return;
        }

        $stubFile = "laravel-{$type}.yml.stub";
        $workflowContent = $this->getStubContent($stubFile);
        $replacements = $this->getWorkflowReplacements($type);

        $workflowContent = $this->replacePlaceholders($workflowContent, $replacements);

        if ($this->writeFile($workflowFile, $workflowContent)) {
            $this->success("Workflow file laravel-{$type}.yml generated successfully!");
        } else {
            $this->error("Failed to generate laravel-{$type}.yml workflow file.");
        }
    }

    /**
     * Get replacements for workflow files.
     */
    private function getWorkflowReplacements(string $type): array
    {
        $replacements = [
            'PHP_VERSION' => $this->config('php_version', '8.3'),
            'APP_NAME' => strtolower(config('app.name', 'laravel')),
        ];

        if ($type === 'ci') {
            $replacements = array_merge($replacements, [
                'DUSK_ENABLED' => $this->config('testing.dusk_enabled', false) ? 'true' : 'false',
                'COVERAGE_ENABLED' => $this->config('testing.phpunit_coverage', false) ? 'true' : 'false',
                'PARALLEL_TESTS' => $this->config('testing.parallel_tests', false) ? 'true' : 'false',
            ]);
        }

        if ($type === 'deploy') {
            $replacements = array_merge($replacements, [
                'STAGING_ENABLED' => $this->config('cicd.staging.enabled', true) ? 'true' : 'false',
                'PRODUCTION_ENABLED' => $this->config('cicd.production.enabled', true) ? 'true' : 'false',
                'REGISTRY_ENABLED' => $this->config('cicd.registry.enabled', false) ? 'true' : 'false',
                'REGISTRY_TYPE' => $this->config('cicd.registry.type', 'dockerhub'),
                'REGISTRY_USERNAME' => $this->config('cicd.registry.username', ''),
                'REGISTRY_REPOSITORY' => $this->config('cicd.registry.repository', ''),
                'SLACK_ENABLED' => $this->config('cicd.notifications.slack.enabled', false) ? 'true' : 'false',
                'DISCORD_ENABLED' => $this->config('cicd.notifications.discord.enabled', false) ? 'true' : 'false',
                'AUTO_MIGRATIONS' => $this->config('advanced.auto_migrations', true) ? 'true' : 'false',
                'CACHE_CLEAR' => $this->config('advanced.cache_clear', true) ? 'true' : 'false',
            ]);
        }

        return $replacements;
    }
}
