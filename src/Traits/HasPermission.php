<?php

namespace IsakzhanovR\UserPermission\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use IsakzhanovR\UserPermission\Helpers\Config;
use IsakzhanovR\UserPermission\Models\Permission;
use IsakzhanovR\UserPermission\Models\Role;

/**
 * Trait HasPermission.
 *
 * @property Collection|Permission[] $permissions
 */
trait HasPermission
{
    use Cacheable;

    /**
     * Many-to-Many polymorph relations with Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permissions()
    {
        return $this->morphToMany(Config::model('permission'),
            'permissible',
            Config::table('permissible'));
    }

    public function hasPermission(string $permission): bool
    {
        $have_permissions = $this->getPermissions();

        return $this->cache(__FUNCTION__, function () use ($permission, $have_permissions) {
            if ($have_permissions->contains('slug', $permission)) {
                return true;
            }

            return false;
        }, $permission);
    }

    public function hasPermissions(...$permissions): bool
    {
        return $this->cache(__FUNCTION__, function () use ($permissions) {
            foreach (Arr::flatten($permissions) as $role) {
                if (!$this->hasPermission($role)) {
                    return false;
                }
            }

            return true;
        }, $permissions);
    }

    protected function getPermissions(): \Illuminate\Support\Collection
    {
        $collection = collect();

        return $this->cache(__FUNCTION__, function () use ($collection) {
            if (in_array(HasRoles::class, class_uses($this))) {
                $this->roles->each(function (Role $role) use (&$collection) {
                    $collection = $collection->merge($role->permissions);
                });
            }

            $collection = $collection->merge($this->permissions);

            return $collection;
        }, 'all');
    }
}
