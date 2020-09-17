<?php

namespace Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use IsakzhanovR\Permissions\Traits\HasPermissions;
use IsakzhanovR\Permissions\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasPermissions;

    protected $fillable = ['name', 'email', 'password'];
}
