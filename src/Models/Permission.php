<?php


namespace IsakzhanovR\UserPermission\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use IsakzhanovR\UserPermission\Repositories\Contracts\PermissionContract;

class Permission extends Model implements PermissionContract
{
    public function roles(): BelongsToMany
    {
        // TODO: Implement roles() method.
    }
}
