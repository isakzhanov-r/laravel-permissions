<?php

namespace Tests\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use IsakzhanovR\Permissions\Http\Middleware\Ability;
use IsakzhanovR\Permissions\Http\Middleware\Permission;
use IsakzhanovR\Permissions\Http\Middleware\Role;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'role'       => Role::class,
        'permission' => Permission::class,
        'ability'    => Ability::class,
    ];
}
