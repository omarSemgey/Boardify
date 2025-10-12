<?php

namespace App\Console\Commands\Traits\Resolves;
use Illuminate\Support\Str;

trait ResolvesDomainModulesTrait
{
    protected array $domainModules;

   protected function initializeDomainModulesContext(): void
    {
        $this->resolveDomainModules();
    }

    protected function resolveDomainModules(): void
    {
        $this->domainModules = collect($this->option('module')) // raw array from CLI
            ->filter() // remove empty strings
            ->flatMap(fn($item) => explode(',', $item)) // split comma-separated
            ->map(fn($module) => $this->validateInput($module)) // clean casing
            ->unique() // remove duplicates
            ->values() // reset keys
            ->all(); // convert back to plain array


            $moduleNames = count($this->domainModules) ? implode(', ', $this->domainModules) : 'No Modules';
            $this->debug("Modules: {$moduleNames}",);
    }
}