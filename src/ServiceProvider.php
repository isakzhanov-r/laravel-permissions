<?php


namespace IsakzhanovR\UserPermission;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use IsakzhanovR\UserPermission\Console\CreatePermission;
use IsakzhanovR\UserPermission\Console\CreateRole;
use IsakzhanovR\UserPermission\Console\CreateUser;
use IsakzhanovR\UserPermission\Helpers\Configable;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__ . '/../config/settings.php' => \config_path('laravel_user_permission.php'),
        ], 'config');

        $this->loadPermissions();
        $this->loadCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'laravel_user_permission');
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
            CreateRole::class,
            CreatePermission::class,
            CreateUser::class,
        ]);
    }
}
