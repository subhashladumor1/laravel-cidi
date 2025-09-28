<?php

namespace Subhashladumor1\LaravelCidi\commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BaseCommand extends Command                                   
{
    /**
     * Get the stub file path.
     */
    public function getStubPath(string $stub): string
    {
        return __DIR__ . '/../resources/stubs/' . $stub;
    }

    /**
     * Get the stub content.
     */
    public function getStubContent(string $stub): string
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
    public function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $placeholder => $replacement) {
            $search = "{{" . $placeholder . "}}";
            $content = str_replace($search, $replacement, $content);
        }

        return $content;
    }

    /**
     * Write content to file.
     */
    public function writeFile(string $path, string $content): bool
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
    public function config(string $key, $default = null)
    {
        return config("cidi.{$key}", $default);
    }

    /**
     * Display success message.
     */
    public function success(string $message): void
    {
        $this->info("✅ {$message}");
    }

    /**
     * Display warning message.
     */
    public function warning(string $message): void
    {
        $this->warn("⚠️  {$message}");
    }
}
