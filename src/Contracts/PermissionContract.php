<?php

namespace IsakzhanovR\Permissions\Repositories\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface PermissionContract
{
    public function permissible(): HasMany;
}
