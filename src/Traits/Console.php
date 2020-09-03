<?php


namespace IsakzhanovR\UserPermission\Traits;


use Illuminate\Support\Str;
use IsakzhanovR\UserPermission\Support\Config;

trait Console
{
    protected function roleExist(): bool
    {
        $model = Config::model('role');

        return $model::query()
            ->where('id', $this->name())
            ->orWhere('slug', $this->slug())
            ->exists();
    }

    protected function permissionExist(): bool
    {
        $model = Config::model('permission');

        return $model::query()
            ->where('id', $this->name())
            ->orWhere('slug', $this->slug())
            ->exists();
    }

    protected function name()
    {
        return $this->argument('name');
    }

    protected function slug()
    {
        return Str::slug($this->name());
    }
}
