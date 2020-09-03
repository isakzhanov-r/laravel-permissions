<?php

namespace IsakzhanovR\UserPermission\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config as SupportConfig;

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
        if ($model = SupportConfig::get('user_permission.models.' . $key)) {
            return $model;
        }
        throw new \Exception('Unknown model key: ' . $key, 500);

    }

    public static function table(string $key): string
    {
        if ($table = SupportConfig::get('user_permission.tables.' . $key)) {
            return $table;
        }
        throw new \Exception('Unknown table key: ' . $key, 500);
    }

    public static function tables(): Collection
    {
        $tables = SupportConfig::get('user_permission.tables', []);

        return Collection::make($tables)->filter(function ($value, $key) {
            return $key !== 'users';
        });
    }
}
