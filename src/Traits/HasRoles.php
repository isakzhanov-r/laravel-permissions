<?php

namespace IsakzhanovR\Permissions\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use IsakzhanovR\Permissions\Helpers\Cacheable;
use IsakzhanovR\Permissions\Helpers\Configable;
use IsakzhanovR\Permissions\Helpers\Modelable;
use IsakzhanovR\Permissions\Models\Role;

/**
 * Trait HasRoles.
 *
 * @property Collection|Role[] $roles
 */
trait HasRoles
{
    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Configable::model('role'), Configable::table('user_roles'), Configable::foreignKey('user'), Configable::foreignKey('role'));
    }

    /**
     * @param $role
     */
    public function attachRole($role): void
    {
        $role = Modelable::findRole($role);

        $this->roles()->attach($role->id);
    }

    /**
     * @param  mixed  ...$roles
     */
    public function attachRoles(...$roles): void
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * @param $role
     */
    public function detachRole($role): void
    {
        $role = Modelable::findRole($role);

        $this->roles()->detach($role->id);
    }

    /**
     * @param  mixed  ...$roles
     */
    public function detachRoles(...$roles): void
    {
        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }

    /**
     * @param  array  $roles_ids
     */
    public function syncRoles(array $roles_ids): void
    {
        $this->roles()->sync($roles_ids);
    }

    /**
     * @param  string  $role
     *
     * @return bool
     */
    public function hasRole($role): bool
    {
        $role = Modelable::findRole($role);

        return Cacheable::make($this->cacheRoleName(__FUNCTION__), function () use ($role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }

            return false;
        }, $role);
    }

    /**
     * @param  mixed  ...$roles
     *
     * @return bool
     */
    public function hasRoles(...$roles): bool
    {
        return Cacheable::make($this->cacheRoleName(__FUNCTION__), function () use ($roles) {
            foreach (Arr::flatten($roles) as $role) {
                if (! $this->hasRole($role)) {
                    return false;
                }
            }

            return true;
        }, $roles);
    }

    private function cacheRoleName($name): string
    {
        $primaryKey = $this->primaryKey;

        return Cacheable::prefix($name, $this->$primaryKey, $this->getTable());
    }
}
