<?php

namespace App\Console\Commands\Traits\Debug;
use Illuminate\Support\Str;

trait ReportTrait
{
    protected function announceDomainScaffoldResult(): void
    {
        $moduleNames = count($this->domainModules)
            ? implode(', ',$this->domainModules)
            : 'No Modules.';

        $filesInfo = $this->option('files') ? 'Base Files.' : 'No Files.';

        $this->components->info("Domain [{$this->domainName}] scaffolded successfully!");
        $this->components->twoColumnDetail('📂 Path', $this->domainPath);
        $this->components->twoColumnDetail('📦 Modules', $moduleNames);
        $this->components->twoColumnDetail('🧩 Files', $filesInfo);
    }
}