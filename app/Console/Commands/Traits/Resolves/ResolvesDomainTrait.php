<?php

namespace App\Console\Commands\Traits\Resolves;
use Illuminate\Support\Str;

trait ResolvesDomainTrait
{
    protected string $domainName;
    protected string $domainPath;

    protected function initializeDomainContext(): void
    {
        $this->resolveDomainName();
        $this->resolveDomainPath();
        $this->domainExists();
    }

    protected function resolveDomainName(): void
    {
        $this->domainName = Str::plural($this->validateInput($this->argument('domain')));
        $this->debug("Domain Name: {$this->domainName}");
    }

    protected function resolveDomainPath(): void
    {
        $customPath = $this->option('domain-custom-path');

        $this->domainPath = $customPath
            ? $this->validatePath($customPath, $this->domainName)
            : app_path("Domains/{$this->domainName}");

        $this->debug("Domain Path: {$this->domainPath}");
    }

}
