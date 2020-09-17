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
        $admin = User::query()
            ->where('email', 'xkoepp@example.com')->first();

        $admin->attachRole(Role::query()->find(1)); // Admin Role in RoleSeeder

        $manager = User::query()
            ->where('email', 'nmonahan@example.org')->first();

        $manager->attachRoles('manager');

        $seo_manager = User::query()
            ->where('email', 'zaria.paucek@example.com')->first();

        $seo_manager->attachRoles(2, 'seo-manager');   // Manger and Seo Manager in RoleSeeder
    }

}
