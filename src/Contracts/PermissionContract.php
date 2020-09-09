<?php

namespace IsakzhanovR\UserPermission\Repositories\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface PermissionContract
{
    public function permissible(): HasMany;
}
