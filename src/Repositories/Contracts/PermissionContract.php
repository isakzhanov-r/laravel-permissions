<?php

namespace IsakzhanovR\UserPermission\Repositories\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface PermissionContract
{
    public function roles(): BelongsToMany;
}
