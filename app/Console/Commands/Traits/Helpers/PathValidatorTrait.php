<?php

namespace App\Console\Commands\Traits\Helpers;

trait PathValidatorTrait
{
    protected function validatePath(string $path, string $suffix): string
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException("Invalid directory: [{$path}]");
        }

        if (!is_writable($path)) {
            throw new \RuntimeException("Directory not writable: [{$path}]");
        }

        return rtrim($path, '/') . '/' . $suffix;
    }
}