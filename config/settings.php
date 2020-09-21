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
        'user'        => 'App\\User',
        'role'        => IsakzhanovR\Permissions\Models\Role::class,
        'permission'  => IsakzhanovR\Permissions\Models\Permission::class,
        'permissible' => IsakzhanovR\Permissions\Models\Permissible::class,
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

    'cache' => true,
];
