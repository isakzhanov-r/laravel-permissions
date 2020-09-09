<?php

namespace IsakzhanovR\UserPermission\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use IsakzhanovR\UserPermission\Exceptions\UnknownKeyException;

final class Configable
{
    /**
     * @param string $key
     *
     * @return \Illuminate\Database\Eloquent\Model | \IsakzhanovR\UserPermission\Models\Role | \IsakzhanovR\UserPermission\Models\Permission |\App\User
     * @throws \Exception
     */
    public static function model(string $key)
    {
        return self::get('models.' . $key);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public static function table(string $key): string
    {
        return self::get('tables.' . $key);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function tables(): Collection
    {
        $tables = self::get('tables', []);

        return Collection::make($tables)->filter(function ($value, $key) {
            return $key !== 'users';
        });
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public static function foreignKey(string $key): string
    {
        return self::get('foreign_key.' . $key);
    }

    /**
     * @return bool
     */
    public static function isCache(): bool
    {
        return (bool) self::get('cache', true);
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed
     */
    private static function get(string $key, $default = null)
    {
        if ($settings = Config::get('laravel_user_permission.' . $key, $default)) {
            return $settings;
        }

        throw new UnknownKeyException($key);
    }
}