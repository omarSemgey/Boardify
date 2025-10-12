<?php

namespace App\Console\Commands\Traits\Resolves;

trait ResolvesDomainDirectoriesTrait
{
    protected array $domainDirectories;

    protected function initializeDomainDirectoriesContext(): void
    {
        $this->resolveDomainDirectories();
    }
    
    protected function resolveDomainDirectories(): void
    {
        $this->domainDirectories = config('{$this->domainConfigPath}.domainscaffolder.directories', $this->getDefaultDomainDirectories());

        if($this->debugMode){
            $domainDirectoryNames =  implode(', ', $this->domainDirectories);
    
            $this->debug("Modules: {$domainDirectoryNames}");
        }

        if(config('domainscaffolder.directories') === null){
            $this->info("The configuration 'domainscaffolder.directories' is not set, falling back to default directories.");
        }
    }

    protected function getDefaultDomainDirectories(): array
    {
        return [
            'Migrations',
            'Models',
            'Policies',
            'Providers',
            'Controllers',
            'DTOs',
            'Requests',
            'Services',
            'Helpers',
            'Contracts/Repositories',
            'Repositories',
            'Routes/Api',
            'Tests/Feature',
            'Tests/Unit',
        ];
    }

}
