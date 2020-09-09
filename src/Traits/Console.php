<?php


namespace IsakzhanovR\UserPermission\Traits;


use Illuminate\Support\Str;
use IsakzhanovR\UserPermission\Helpers\Configable;

/**
 * Trait Console
 *
 * @package IsakzhanovR\UserPermission\Traits
 */
trait Console
{
    /**
     * @return bool
     * @throws \Exception
     */
    protected function roleExist(): bool
    {
        $model = Configable::model('role');

        return $model::query()
            ->where('id', $this->name())
            ->orWhere('slug', $this->slug())
            ->exists();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function permissionExist(): bool
    {
        $model = Configable::model('permission');

        return $model::query()
            ->where('id', $this->name())
            ->orWhere('slug', $this->slug())
            ->exists();
    }

    /**
     * @return mixed
     */
    protected function name()
    {
        return $this->argument('name');
    }

    /**
     * @return string
     */
    protected function slug()
    {
        return Str::slug($this->name());
    }
}
