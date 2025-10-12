<?php

namespace App\Console\Commands;

// ** TRAITS ** // 

// Resolves
use App\Console\Commands\Traits\Resolves\ResolveDomainConfigTrait;
use App\Console\Commands\Traits\Resolves\ResolvesDomainTrait;
use App\Console\Commands\Traits\Resolves\ResolvesDomainDirectoriesTrait;
use App\Console\Commands\Traits\Resolves\ResolvesDomainFilesTrait;
use App\Console\Commands\Traits\Resolves\ResolvesDomainModulesTrait;

// Generates
use App\Console\Commands\Traits\Generates\GeneratesDomainDirectoriesTrait;
use App\Console\Commands\Traits\Generates\GeneratesDomainFilesTrait;
use App\Console\Commands\Traits\Generates\GeneratesDomainModulesTrait;
use App\Console\Commands\Traits\Generates\GeneratesDomainTrait;

// Validates
use App\Console\Commands\Traits\Validates\ValidatesDomainDirectoriesTrait;
use App\Console\Commands\Traits\Validates\ValidatesDomainFilesTrait;
use App\Console\Commands\Traits\Validates\ValidatesDomainModulesTrait;
use App\Console\Commands\Traits\Validates\ValidatesDomainTrait;


// Debug
use App\Console\Commands\Traits\Debug\DebugTrait;
use App\Console\Commands\Traits\Debug\ReportTrait;

// Helpers
use App\Console\Commands\Traits\Helpers\CreatesDirectoriesTrait;
use App\Console\Commands\Traits\Helpers\ValidatesUsersInputTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeDomainCommand extends Command
{
    use 
    // Respolves
    ResolveDomainConfigTrait,ResolvesDomainTrait, ResolvesDomainDirectoriesTrait, ResolvesDomainModulesTrait, ResolvesDomainFilesTrait,
    
    // Generates
    GeneratesDomainTrait, GeneratesDomainDirectoriesTrait, GeneratesDomainModulesTrait, GeneratesDomainFilesTrait,
    
    // Validates
    ValidatesDomainTrait, ValidatesDomainDirectoriesTrait,  ValidatesDomainModulesTrait, ValidatesDomainFilesTrait,
    
    // Debug
    ReportTrait, DebugTrait,
    
    // Helpers
    CreatesDirectoriesTrait, ValidatesUsersInputTrait;
    
    protected $signature = 'make:domain {domain} {--module=*} {--files} {--domain-custom-path=} {--stub-custom-path=} {--config-custom-path=} {--debug}';
    protected $description = 'Generate a new domain with optional modules';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        try {

            $this->initializeDebugContext();
            $this->initializeDomainConfigContext();
            $this->initializeDomainContext();
            $this->initializeDomainDirectoriesContext();
            $this->initializeDomainModulesContext();
            $this->initializeDomainFilesContext();

            $this->handleDomainDirectories();
            $this->handleDomainModules();
            $this->handleDomainFiles();

            $this->announceDomainScaffoldResult();

            return self::SUCCESS;
        } catch (\Throwable $e) {

            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    protected function handleDomainDirectories(): void
    {
        $this->generateDomainDirectories();
    }

    protected function handleDomainModules(): void
    {
        $this->generateDomainModules();
    }

    protected function handleDomainFiles(): void
    {
        if ($this->option('files')) {
            $this->generateDomainFiles();
        }
    }
}