<?php

namespace App\Console\Commands\Traits\Validates;

trait ValidatesDomainTrait
{

    protected function domainExists(): void
    {
        if ($this->files->exists($this->domainPath)) {
            throw new \RuntimeException("Domain '{$this->domainName}' already exists.");
        }
    }
}
