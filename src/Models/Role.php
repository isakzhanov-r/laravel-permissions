<?php

namespace IsakzhanovR\UserPermission\Models;

use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\UserPermission\Helpers\Configable;
use IsakzhanovR\UserPermission\Repositories\Contracts\PermissibleContract;
use IsakzhanovR\UserPermission\Traits\HasPermission;
use IsakzhanovR\UserPermission\Traits\SetAttribute;

class Role extends Model implements PermissibleContract
{
    use SetAttribute, HasPermission;

    protected $fillable = [
        'title', 'slug', 'description',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Configable::table('roles');
        parent::__construct($attributes);
    }
}
