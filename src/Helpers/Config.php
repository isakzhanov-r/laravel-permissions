<?php

namespace IsakzhanovR\UserPermission\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config as IlluminateConfig;

class Config
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

    public static function table(string $key): string
    {
        return self::get('tables.' . $key);
    }

    public static function tables(): Collection
    {
        $tables = self::get('tables', []);

        return Collection::make($tables)->filter(function ($value, $key) {
            return $key !== 'users';
        });
    }

    public static function foreignKey(string $key): string
    {
        return self::get('foreign_key.' . $key);
    }

    public static function isCache(): bool
    {
        return (bool) self::get('cache', true);
    }

    private static function get(string $key, $default = null)
    {
        if ($settings = IlluminateConfig::get('user_permission.' . $key, $default)) {
            return $settings;
        }

        throw new \Exception('Unknown path to value key: ' . $key, 500);
    }
}
