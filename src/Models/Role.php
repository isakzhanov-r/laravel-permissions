<?php

namespace IsakzhanovR\UserPermission\Models;

use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\UserPermission\Helpers\Config;
use IsakzhanovR\UserPermission\Repositories\Contracts\RoleContract;
use IsakzhanovR\UserPermission\Traits\HasPermission;
use IsakzhanovR\UserPermission\Traits\SetAttribute;

class Role extends Model implements RoleContract
{
    use SetAttribute, HasPermission;

    protected $fillable = [
        'title', 'slug', 'description',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Config::table('roles');
        parent::__construct($attributes);
    }
}
