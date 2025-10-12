<?php

namespace App\Domains\Users\Providers;

use App\Domains\Users\Contracts\Repositories\Crud\UserCrudRepositoryInterface;
use App\Domains\Users\Repositories\Crud\UserCrudRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Domains\Users\Models\User;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register bindings or singletons (optional).
     */
    public function register(): void
    {
        $this->app->bind(UserCrudRepositoryInterface::class, UserCrudRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../Migrations'));
        Route::middleware('api')->group(function () {
            require __DIR__ . '/../Routes/Api/Crud/UserCrudApi.php';
            require __DIR__ . '/../Routes/Api/Logic/UserLogicApi.php';
            require __DIR__ . '/../Routes/Api/Auth/UserAuthApi.php';
        });
    }
}
