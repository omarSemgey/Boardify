<?php

namespace App\Domains\Boards\Providers;

use Illuminate\Support\ServiceProvider;

class BoardServiceProvider extends ServiceProvider
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
