<?php


namespace IsakzhanovR\UserPermission\Repositories\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface RoleContract
{
    public function permissions(): BelongsToMany;

    public function hasPermission($permission): bool;
}
