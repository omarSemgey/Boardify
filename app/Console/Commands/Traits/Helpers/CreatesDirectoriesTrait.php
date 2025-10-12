<?php

namespace App\Console\Commands\Traits\Helpers;

trait CreatesDirectoriesTrait
{
    protected function createDirectory(string $path): void
    {
        if ($this->files->exists($path)) {
            return;
        }

        $this->debug("Creating Directory: {$path}");
    
        $this->files->makeDirectory($path, 0755, true);
        $this->files->put("{$path}/.gitkeep", '');
    }
}