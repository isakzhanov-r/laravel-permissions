<?php

namespace IsakzhanovR\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\Permissions\Helpers\Configable;
use IsakzhanovR\Permissions\Repositories\Contracts\PermissibleContract;
use IsakzhanovR\Permissions\Traits\HasPermission;
use IsakzhanovR\Permissions\Traits\SetAttribute;

class Role extends Model implements PermissibleContract
{
    use SetAttribute, HasPermission;

    public function __construct(array $attributes = [])
    {
        $this->table = Configable::table('roles');
        parent::__construct($attributes);
    }
}
