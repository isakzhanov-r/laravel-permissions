<?php

namespace IsakzhanovR\Permissions\Traits;

use Illuminate\Support\Str;
use IsakzhanovR\Permissions\Helpers\Modelable;

/**
 * Trait Console
 *
 * @package IsakzhanovR\Permissions\Traits
 */
trait Console
{
    /**
     * @throws \Exception
     * @return bool
     */
    protected function roleExist(): bool
    {
        return Modelable::exist('role', $this->slug());
    }

    /**
     * @throws \Exception
     * @return bool
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
