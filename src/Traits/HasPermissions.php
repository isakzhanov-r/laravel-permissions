<?php

namespace IsakzhanovR\Permissions\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use IsakzhanovR\Permissions\Helpers\Cacheable;
use IsakzhanovR\Permissions\Helpers\Configable;
use IsakzhanovR\Permissions\Helpers\Modelable;
use IsakzhanovR\Permissions\Models\Permission;

/**
 * Trait HasPermission.
 *
 * @property Collection|Permission[] $permissions
 */
trait HasPermissions
{
    /**
     * Many-to-Many polymorph relations with Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permissions(): MorphToMany
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

        if ($permission) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * @param  mixed  ...$permissions
     */
    public function attachPermissions(...$permissions): void
    {
        foreach (Arr::flatten($permissions) as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * @param $permission
     */
    public function detachPermission($permission): void
    {
        $permission = Modelable::findPermission($permission);

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * @param  mixed  ...$permissions
     */
    public function detachPermissions(...$permissions): void
    {
        foreach (Arr::flatten($permissions) as $permission) {
            $this->detachPermission($permission);
        }
    }

    /**
     * @param  array  $permissions_ids
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
        if (is_null($permission)) {
            return false;
        }

        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () use ($permission) {
                if ($this->getPermissions()->contains('slug', $permission->slug)) {
                    return true;
                }

                return false;
            }, $permission);
    }

    /**
     * @param  mixed  ...$permissions
     *
     * @return bool
     */
    public function hasPermissions(...$permissions): bool
    {
        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () use ($permissions) {

                foreach (Arr::flatten($permissions) as $permission) {
                    if (! $this->hasPermission($permission)) {
                        return false;
                    }
                }

                return true;
            }, $permissions);
    }

    public function hasAnyPermission(...$permissions): bool
    {
        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () use ($permissions) {

                foreach (Arr::flatten($permissions) as $permission) {
                    if ($this->hasPermission($permission)) {
                        return true;
                    }
                }

                return false;
            }, $permissions);
    }

    public function matchPermissions(string $permission): bool
    {
        $permission = e(trim($permission));

        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () use ($permission) {
                foreach ($this->getPermissions() as $item) {
                    if (Str::is($permission, $item->slug)) {
                        return true;
                    }
                }

                return false;
            }, $permission);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions(): \Illuminate\Support\Collection
    {
        return Cacheable::make($this->cachePermissionName(__FUNCTION__),
            function () {
                $collection = $this->rolesPermission();

                return $collection->merge($this->permissions()->get());
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
            return $this->roles()->get()->transform(function ($role) {
                return $role->permissions()->get();
            })
                ->flatten()
                ->unique('id');
        }

        return collect();
    }
}
