<?php

namespace App\Console\Commands\Traits\Generates;


trait GeneratesDomainDirectoriesTrait
{

    protected function generateDomainDirectories(): void
    {
        $dirs = collect($this->domainDirectories);

        $dirs->each(function ($dir) {
            $this->createDirectory("{$this->domainPath}/{$dir}");
        });
    }

}
