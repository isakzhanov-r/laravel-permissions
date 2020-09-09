<?php

/**
 * This file is part of Laravel User permission,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package IsakzhanovR\Laravel-user-permission
 */


return [

    'models' => [
        'user'        => App\User::class,
        'role'        => IsakzhanovR\UserPermission\Models\Role::class,
        'permission'  => IsakzhanovR\UserPermission\Models\Permission::class,
        'permissible' => IsakzhanovR\UserPermission\Models\Permissible::class,
    ],

    'tables' => [
        'users'       => 'users',
        'roles'       => 'roles',
        'permissions' => 'permissions',
        'user_roles'  => 'role_user',
        'permissible' => 'permissible',
    ],

    'foreign_key' => [
        'user'       => 'user_id',
        'role'       => 'role_id',
        'permission' => 'permission_id',
    ],
];
