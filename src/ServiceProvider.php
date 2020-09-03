<?php


namespace IsakzhanovR\UserPermission;


use IsakzhanovR\UserPermission\Console\CreatePermission;
use IsakzhanovR\UserPermission\Console\CreateRole;
use IsakzhanovR\UserPermission\Console\CreateUser;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__ . '/../config/user_permission.php' => \config_path('user_permission.php'),
        ], 'config');

        $this->loadGate();
        $this->loadCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/user_permission.php', 'user_permission');
    }

    protected function loadGate()
    {

    }

    protected function loadDirective()
    {

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
