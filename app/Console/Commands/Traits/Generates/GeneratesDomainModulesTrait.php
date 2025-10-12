<?php

namespace App\Console\Commands\Traits\Generates;
use Illuminate\Support\Str;

trait GeneratesDomainModulesTrait
{
    protected function generateDomainModules(): void
    {
        if (empty($this->domainModules)) {
            $this->debug("No modules specified, skipping module generation.");
            return;
        }

        collect($this->domainModules)
            ->each(function ($module) {
                $this->generateSingleModule($module);
            });
    }

    protected function generateSingleModule(string $module): void
    {
        $this->debug("Generating module: {$module}");

        collect($this->domainDirectories)
            ->each(function ($dir) use ($module) {
                $path = "{$this->domainPath}/{$dir}/{$module}";
                $this->createDirectory($path);
            });
    }
}
