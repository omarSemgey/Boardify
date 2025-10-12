<?php

namespace App\Console\Commands\Traits\Debug;

trait DebugTrait
{
    protected bool $debugMode = false;

    protected function initializeDebugContext(): void
    {
        $this->debugMode = $this->option('debug') ?? false;
    }

    protected function debug(string $message, ?string $context = null): void
    {
        if (!$this->debugMode) {
            return;
        }

        if (!$context) {
            // Get caller info
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            if (isset($backtrace[1])) {
                $caller = $backtrace[1];
                $file = isset($caller['file']) ? basename($caller['file']) : 'unknown file';
                $function = $caller['function'] ?? 'unknown function';
                $context = "{$file}@{$function}";
            }
        }

        $contextTag = $context ? "[{$context}] " : '';
        $this->line("<fg=gray>{$contextTag}{$message}</>");
    }
}
