<?php

namespace IsakzhanovR\Permissions\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface PermissibleContract
{
    public function permissions(): MorphToMany;

    public function attachPermission($permission): void;

    public function detachPermission($permission): void;

    public function syncPermissions(array $permission_ids): void;

    public function hasPermission($permission): bool;

    public function matchPermissions(string $permission): bool;
}
