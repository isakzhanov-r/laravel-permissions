<?php


namespace IsakzhanovR\UserPermission\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use IsakzhanovR\UserPermission\Helpers\Config;
use IsakzhanovR\UserPermission\Models\Role;

/**
 * Trait HasRoles.
 *
 * @property Collection|Role[] $roles
 */
trait HasRoles
{
    use Cacheable;

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Config::model('role'), Config::table('user_roles'), Config::foreignKey('user'), Config::foreignKey('role'));
    }

    /**
     * @param $role
     */
    public function attachRole($role)
    {

    }

    /**
     * @param mixed ...$roles
     */
    public function attachRoles(...$roles)
    {

    }

    /**
     * @param $role
     */
    public function detachRole($role)
    {

    }

    /**
     * @param mixed ...$roles
     */
    public function detachRoles(...$roles)
    {

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
        return $this->cache(__FUNCTION__, function () use ($role) {
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
        return $this->cache(__FUNCTION__, function () use ($roles) {
            foreach (Arr::flatten($roles) as $role) {
                if (!$this->hasRole($role)) {
                    return false;
                }
            }

            return true;
        }, $roles);
    }
}
