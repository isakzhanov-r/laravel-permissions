<?php

namespace IsakzhanovR\UserPermission\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use IsakzhanovR\UserPermission\Helpers\Cacheable;
use IsakzhanovR\UserPermission\Helpers\Configable;
use IsakzhanovR\UserPermission\Helpers\Modelable;
use IsakzhanovR\UserPermission\Models\Permission;

/**
 * Trait HasPermission.
 *
 * @property Collection|Permission[] $permissions
 */
trait HasPermission
{
    /**
     * Many-to-Many polymorph relations with Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permissions()
    {
        return $this->morphToMany(Configable::model('permission'),
            'permissible',
            Configable::table('permissible'));
    }

    /**
     * @param $permission
     */
    public function attachPermission($permission): void
    {
        $permission = Modelable::findPermission($permission);

        $this->permissions()->attach($permission->id);
    }

    /**
     * @param mixed ...$permissions
     */
    public function attachPermissions(...$permissions): void
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * @param $permission
     */
    public function detachPermission($permission): void
    {
        $permission = Modelable::findPermission($permission);

        $this->permissions()->detach($permission->id);
    }

    /**
     * @param mixed ...$permissions
     */
    public function detachPermissions(...$permissions): void
    {
        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    /**
     * @param array $permissions_ids
     */
    public function syncPermissions(array $permissions_ids): void
    {
        $this->permissions()->sync($permissions_ids);
    }

    /**
     * @param $permission
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        $permission = Modelable::findPermission($permission);

        $have_permissions = $this->getPermissions();

        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () use ($permission, $have_permissions) {
                if ($have_permissions->contains('slug', $permission->slug)) {
                    return true;
                }

                return false;
            }, $permission);
    }

    /**
     * @param mixed ...$permissions
     *
     * @return bool
     */
    public function hasPermissions(...$permissions): bool
    {
        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () use ($permissions) {

                foreach (Arr::flatten($permissions) as $role) {
                    if (!$this->hasPermission($role)) {
                        return false;
                    }
                }

                return true;
            }, $permissions);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions(): \Illuminate\Support\Collection
    {
        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () {
                $collection = $this->rolesPermission();

                return $collection->merge($this->permissions);
            }, 'all');
    }

    /**
     * @param $name
     *
     * @return string
     */
    private function cachePermissionName($name): string
    {
        $primaryKey = $this->primaryKey;

        return Cacheable::prefix($name, $this->$primaryKey, $this->getTable());
    }

    private function rolesPermission()
    {

        if (in_array(HasRoles::class, class_uses($this))) {
            return $this->roles->transform(function ($role) {
                return $role->permissions()->get();
            })
                ->flatten()
                ->unique('id');
        }

        return collect();
    }
}
