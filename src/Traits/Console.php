<?php


namespace IsakzhanovR\UserPermission\Traits;


use Illuminate\Support\Str;
use IsakzhanovR\UserPermission\Helpers\Modelable;

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
        return Modelable::exist('role', $this->slug());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function permissionExist(): bool
    {
        return Modelable::exist('permission', $this->slug());
    }

    /**
     * @return mixed
     */
    protected function name()
    {
        return e($this->argument('name'));
    }

    /**
     * @return string
     */
    protected function slug()
    {
        return Str::slug($this->name());
    }
}
