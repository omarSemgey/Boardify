<?php

namespace App\Console\Commands\Traits\Resolves;

trait ResolveDomainConfigTrait
{
    protected string $domainConfigPath;

    protected function initializeDomainConfigContext(): void
    {
        $this->resolveDomainConfigPath();
    }

    protected function resolveDomainConfigPath(): void
    {
        $customPath = $this->option('config-custom-path');

        $this->domainConfigPath = $customPath
            ? $this->validatePath($customPath, "domainscaffolder.php")
            : app_path("../config/domainscaffolder.php");

        $this->debug("Domain Config Path: {$this->domainConfigPath}");
    }
}
