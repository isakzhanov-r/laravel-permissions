<?php

namespace IsakzhanovR\Permissions\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface PermissionContract
{
    public function permissible(): HasMany;
}
