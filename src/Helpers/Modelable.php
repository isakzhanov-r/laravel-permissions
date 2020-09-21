<?php

namespace IsakzhanovR\Permissions\Helpers;

use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\Permissions\Exceptions\UnknownKeyException;

final class Modelable
{
    /**
     * @param $permission
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findPermission($permission)
    {
        return self::find('permission', $permission);
    }

    /**
     * @param $role
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findRole($role)
    {
        return self::find('role', $role);
    }

    /**
     * @param  string  $config_model
     * @param $value
     *
     * @throws \Exception
     * @return bool
     */
    public static function exist(string $config_model, $value)
    {
        $model = Configable::model($config_model);

        $value = trim($value);

        return $model::query()
            ->where('id', $value)
            ->orWhere('slug', $value)
            ->exists();
    }

    /**
     * @param  string  $config_model
     * @param $value
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Illuminate\Database\Eloquent\Model
     */
    private static function find(string $config_model, $value): Model
    {
        $model = Configable::model($config_model);

        if ($value instanceof $model) {
            return $value;
        }

        $value = trim($value);

        $item = $model::query()
            ->where('id', $value)
            ->orWhere('slug', $value)
            ->first();

        if (is_null($item)) {
            throw new UnknownKeyException($value, $model);
        }

        return $item;
    }
}
