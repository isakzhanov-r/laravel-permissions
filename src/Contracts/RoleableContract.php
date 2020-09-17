<?php

namespace IsakzhanovR\Permissions\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface RoleableContract
{
    public function roles(): BelongsToMany;

    public function attachRole($role): void;

    public function detachRole($role): void;

    public function syncRoles(array $role_ids): void;

    public function hasRole($role): bool;
}
