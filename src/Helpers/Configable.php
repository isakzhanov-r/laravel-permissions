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
     * @return \Illuminate\Database\Eloquent\Model
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
        if (Config::has('laravel_permissions.' . $key, $default)) {
            return Config::get('laravel_permissions.' . $key, $default);
        }

        throw new UnknownKeyException($key);
    }
}
