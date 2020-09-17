<?php

namespace Tests\Database\Seeders;

class DatabaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public static function run()
    {
        PermissionSeeder::run();
        RoleSeeder::run();
        UserSeeder::run();
        AttachRolesSeeder::run();
        AttachPermissionsSeeder::run();
    }
}
