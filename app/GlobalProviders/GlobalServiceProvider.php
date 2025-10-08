<?php

namespace App\GlobalProviders;

use App\Domains\Roles\Providers\RoleServiceProvider;
use App\GlobalPolicies\GlobalPolicy;
use App\Domains\Users\Providers\UserServiceProvider;
use App\Domains\Boards\Providers\BoardServiceProvider;
use App\Domains\Notes\Providers\NoteServiceProvider;
use App\Domains\Permissions\Providers\PermissionServiceProvider;
use App\Domains\Types\Providers\TypeServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Log;

class GlobalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(UserServiceProvider::class);
        $this->app->register(BoardServiceProvider::class);
        $this->app->register(RoleServiceProvider::class);
        $this->app->register(TypeServiceProvider::class);
        $this->app->register(NoteServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('global-permission', [GlobalPolicy::class, 'userHasAuthorityOver']);
    }
}
