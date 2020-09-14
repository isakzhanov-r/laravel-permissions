<?php

namespace IsakzhanovR\Permissions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use IsakzhanovR\Permissions\Commands\CreatePermission;
use IsakzhanovR\Permissions\Commands\CreateRole;
use IsakzhanovR\Permissions\Commands\Migration;
use IsakzhanovR\Permissions\Helpers\Configable;

use function config_path;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('laravel_permissions.php'),
        ], 'config');

        $this->loadPermissions();
        $this->loadCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'laravel_permissions');
    }

    protected function loadPermissions()
    {
        if (Schema::hasTable(Configable::table('permissions'))) {
            $permissions = Configable::model('permission');
            $permissions::all()
                ->load('permissible')
                ->each(function ($permission) {
                    $this->defineGate($permission);
                });
        }
    }

    protected function defineGate($permission)
    {
        $permission->permissible->map(function ($pivot) use ($permission) {
            Gate::define($permission->slug, function () use ($permission, $pivot) {
                return $pivot->permissible->hasPermission($permission);
            });
        });
    }

    protected function loadCommands()
    {
        $this->commands([
            Migration::class,
            CreateRole::class,
            CreatePermission::class,
        ]);
    }
}
