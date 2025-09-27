<?php

namespace Subhashladumor1\LaravelCidi\commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

abstract class BaseCommand extends Command
{
    /**
     * Get the stub file path.
     */
    protected function getStubPath(string $stub): string
    {
        return __DIR__ . '/../resources/stubs/' . $stub;
    }

    /**
     * Get the stub content.
     */
    protected function getStubContent(string $stub): string
    {
        $stubPath = $this->getStubPath($stub);
        
        if (!File::exists($stubPath)) {
            throw new \Exception("Stub file not found: {$stubPath}");
        }

        return File::get($stubPath);
    }

    /**
     * Replace placeholders in stub content.
     */
    protected function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $placeholder => $replacement) {
            $content = str_replace("{{$placeholder}}", $replacement, $content);
        }

        return $content;
    }

    /**
     * Write content to file.
     */
    protected function writeFile(string $path, string $content): bool
    {
        $directory = dirname($path);
        
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        return File::put($path, $content) !== false;
    }

    /**
     * Get configuration value.
     */
    protected function config(string $key, $default = null)
    {
        return config("cidi.{$key}", $default);
    }

    /**
     * Display success message.
     */
    protected function success(string $message): void
    {
        $this->info("✅ {$message}");
    }

    /**
     * Display warning message.
     */
    protected function warning(string $message): void
    {
        $this->warn("⚠️  {$message}");
    }

    /**
     * Display error message.
     */
    protected function error(string $message): void
    {
        $this->error("❌ {$message}");
    }
}
