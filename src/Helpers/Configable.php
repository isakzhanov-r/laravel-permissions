<?php

namespace IsakzhanovR\Permissions\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use IsakzhanovR\Permissions\Exceptions\UnknownKeyException;

final class Configable
{
    /**
     * @param  string  $key
     *
     * @throws \Exception
     * @return \Illuminate\Database\Eloquent\Model | \IsakzhanovR\Permissions\Models\Role | \IsakzhanovR\Permissions\Models\Permission |\App\User
     */
    public static function model(string $key)
    {
        return self::get('models.' . $key);
    }

    /**
     * @param  string  $key
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

        return Collection::make($tables)->except('users');
    }

    /**
     * @param  string  $key
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
     * @param  string  $key
     * @param  null  $default
     *
     * @return mixed
     */
    private static function get(string $key, $default = null)
    {
        if ($settings = Config::get('laravel_permissions.' . $key, $default)) {
            return $settings;
        }

        throw new UnknownKeyException($key);
    }
}
