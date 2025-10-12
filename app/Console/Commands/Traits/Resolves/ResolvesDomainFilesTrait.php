<?php

namespace App\Console\Commands\Traits\Resolves;

trait ResolvesDomainFilesTrait
{
    protected array $domainStubs;
    protected string $domainStubsPath;

    protected function initializeDomainFilesContext(): void
    {
        if ($this->option('files')) {
            $this->resolveStubsPath();
            $this->resolveDomainStubs();
        }
    }

    protected function resolveDomainStubs(): void
    {
        $this->domainStubs = config("{$this->domainConfigPath}.domainscaffolder.subs", $this->getDefaultDomainSubs());

        $domainStubs =  implode(', ', $this->domainStubs);
    
        $this->debug("Modules: {$domainStubs}");

        if(config('domainscaffolder.stubs') === null){
            $this->info("The configuration 'domainscaffolder.stubs' is not set, falling back to default stubs.");
        }
    }

    protected function resolveStubsPath(): void
    {
        $customPath = $this->option('stubs-custom-path');

        $this->domainStubsPath = $customPath
            ? $this->validatePath($customPath, "")
            : app_path("../stubs/domain/");

        $this->debug("Domain Stubs Path: {$this->domainStubsPath}");
    }

    protected function getDefaultDomainSubs(): array
    {
        return [
            'Models' => 'model.stub',
            'Policies' => 'policy.stub',
            'Providers' => 'serviceprovider.stub',
            'Controllers' => 'controller.stub',
            'DTOs' => 'dto.stub',
            'Services' => 'service.stub',
            'Contracts/Repositories' => 'repositoryinterface.stub',
            'Routes/Api' => 'api.stub',
            'Repositories' => 'repository.stub',
        ];
    }

    protected function getStub(): string
    {
        $type = ucfirst($this->argument('type'));
        $stubs = config('stubs.stubs');

        if (!isset($stubs[$type])) {
            $this->error("Stub for [$type] not found in stubs.php");
            exit(1);
        }

        return base_path('stubs/domain/' . $stubs[$type]);
    }
}