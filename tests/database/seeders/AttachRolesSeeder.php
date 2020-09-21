<?php

namespace Tests\Database\Seeders;

use IsakzhanovR\Permissions\Models\Role;
use Tests\Models\User;

class AttachRolesSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $class = new self();
        $class->factory();
    }

    private function factory()
    {
        $admin = User::find(1);

        $admin->attachRole(Role::query()->find(1)); // Admin Role in RoleSeeder

        $seo_manager = User::find(2);

        $seo_manager->attachRoles(2, 'seo-manager');   // Manger and Seo Manager in RoleSeeder

        $manager = User::find(3);

        $manager->attachRoles('manager');
    }

}
