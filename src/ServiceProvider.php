<?php

namespace IsakzhanovR\Permissions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use IsakzhanovR\Permissions\Commands\CreatePermission;
use IsakzhanovR\Permissions\Commands\CreateRole;
use IsakzhanovR\Permissions\Commands\Migration;
use IsakzhanovR\Permissions\Helpers\Cacheable;
use IsakzhanovR\Permissions\Helpers\Configable;

use function config_path;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publishMigrations();

        $this->publishConfig();

        $this->loadPermissions();
        $this->loadCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'laravel_permissions');
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('laravel_permissions.php'),
        ], 'config');
    }

    protected function publishMigrations()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    protected function loadPermissions()
    {
        if ($this->existPermissionsTable()) {
            $permissions = Configable::model('permission');
            $permissions::all()
                ->load('permissible')
                ->each(function ($permission) {
                    $this->defineGate($permission);
                });
        }
    }

    protected function loadCommands()
    {
        $this->commands([
            Migration::class,
            CreateRole::class,
            CreatePermission::class,
        ]);
    }

    protected function existPermissionsTable()
    {
        return Cacheable::make(__FUNCTION__, function () {
            return Schema::hasTable(Configable::table('permissions'));
        }, 'boot');

    }

    protected function defineGate($permission)
    {
        $permission->permissible->map(function ($pivot) use ($permission) {
            Gate::define($permission->slug, function () use ($permission, $pivot) {
                return $pivot->permissible->hasPermission($permission);
            });
        });
    }
}
