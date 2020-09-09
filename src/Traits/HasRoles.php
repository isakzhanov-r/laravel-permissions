<?php


namespace IsakzhanovR\UserPermission\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use IsakzhanovR\UserPermission\Helpers\Cacheable;
use IsakzhanovR\UserPermission\Helpers\Configable;
use IsakzhanovR\UserPermission\Helpers\Modelable;
use IsakzhanovR\UserPermission\Models\Role;

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
    public function roles()
    {
        return $this->belongsToMany(Configable::model('role'), Configable::table('user_roles'), Configable::foreignKey('user'), Configable::foreignKey('role'));
    }

    /**
     * @param $role
     */
    public function attachRole($role)
    {
        $role = Modelable::findRole($role);

        $this->roles()->attach($role->id);
    }

    /**
     * @param mixed ...$roles
     */
    public function attachRoles(...$roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * @param $role
     */
    public function detachRole($role)
    {
        $role = Modelable::findRole($role);

        $this->roles()->detach($role->id);
    }

    /**
     * @param mixed ...$roles
     */
    public function detachRoles(...$roles)
    {
        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }

    /**
     * @param array $roles_ids
     */
    public function syncRoles(array $roles_ids)
    {
        $this->roles()->sync($roles_ids);
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return Cacheable::make($this->cacheRoleName(__FUNCTION__), function () use ($role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }

            return false;
        }, $role);
    }

    /**
     * @param mixed ...$roles
     *
     * @return bool
     */
    public function hasRoles(...$roles): bool
    {
        return Cacheable::make($this->cacheRoleName(__FUNCTION__), function () use ($roles) {
            foreach (Arr::flatten($roles) as $role) {
                if (!$this->hasRole($role)) {
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
