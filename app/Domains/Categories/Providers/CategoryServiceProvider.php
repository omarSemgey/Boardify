<?php

namespace App\Domains\Notes\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Domains\Users\Models\User;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * Register bindings or singletons (optional).
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../Migrations'));
        $this->loadRoutesFrom(realpath(__DIR__ . '/../Routes/api/api.php'));
    }
}
