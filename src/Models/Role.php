<?php

namespace IsakzhanovR\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\Permissions\Contracts\PermissibleContract;
use IsakzhanovR\Permissions\Helpers\Configable;
use IsakzhanovR\Permissions\Traits\HasPermissions;
use IsakzhanovR\Permissions\Traits\SetAttribute;

class Role extends Model implements PermissibleContract
{
    use SetAttribute, HasPermissions;

    protected $fillable = [
        'title',
        'slug',
        'description',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Configable::table('roles');
        parent::__construct($attributes);
    }
}
