<?php

namespace Tests\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use IsakzhanovR\Permissions\Http\Middleware\Ability;
use IsakzhanovR\Permissions\Http\Middleware\Role;
use IsakzhanovR\Permissions\Models\Permission;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'role'       => Role::class,
        'permission' => Permission::class,
        'ability'    => Ability::class,
    ];
}
