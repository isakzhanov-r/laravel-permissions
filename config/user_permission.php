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
        'user'       => App\User::class,
        'role'       => App\User::class,
        'permission' => App\User::class,
    ],

    'tables' => [
        'users'            => 'users',
        'roles'            => 'roles',
        'permissions'      => 'permissions',
        'user_roles'       => 'role_user',
        'permission_roles' => 'permission_role',
        'permission_model' => 'permission_model',
    ],


];
