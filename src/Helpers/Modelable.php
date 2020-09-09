<?php


namespace IsakzhanovR\UserPermission\Helpers;


use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\UserPermission\Exceptions\UnknownKeyException;

final class Modelable
{
    /**
     * @param $permission
     *
     * @return \App\User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\IsakzhanovR\UserPermission\Models\Permission|\IsakzhanovR\UserPermission\Models\Role|object
     */
    public static function findPermission($permission)
    {
        return self::find('permission', $permission);
    }

    /**
     * @param $role
     *
     * @return \App\User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\IsakzhanovR\UserPermission\Models\Permission|\IsakzhanovR\UserPermission\Models\Role|object
     */
    public static function findRole($role)
    {
        return self::find('role', $role);
    }

    /**
     * @param string $config_model
     * @param $value
     *
     * @return bool
     * @throws \Exception
     */
    public static function exist(string $config_model, $value)
    {
        $model = Configable::model($config_model);

        return $model::query()
            ->where('id', $value)
            ->orWhere('slug', $value)
            ->exists();
    }

    /**
     * @param string $config_model
     * @param $value
     *
     * @return \App\User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\IsakzhanovR\UserPermission\Models\Permission|\IsakzhanovR\UserPermission\Models\Role
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private static function find(string $config_model, $value): Model
    {
        $model = Configable::model($config_model);

        if ($value instanceof $model) {
            return $value;
        }

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
