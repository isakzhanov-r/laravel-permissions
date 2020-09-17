<?php

namespace Tests\Database\Seeders;

use IsakzhanovR\Permissions\Models\Permission;
use IsakzhanovR\Permissions\Models\Role;
use Tests\Models\User;

class AttachPermissionsSeeder
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
        $role_admin = Role::query()
            ->where('slug', 'admin')->first();

        $role_admin->attachPermissions(Permission::query()->get());

        /**
         * @var $role_manager Role
         */
        $role_manager = Role::query()
            ->where('slug', 'manager')->first();

        $this->permissionForManager($role_manager);

        /**
         * @var $role_seo_manager Role
         */
        $role_seo_manager = Role::query()
            ->where('slug', 'seo-manager')->first();

        $this->permissionForSeoManager($role_seo_manager);

        $seo_manager = User::query()
            ->where('email', 'zaria.paucek@example.com')->first();

        $seo_manager->attachPermission('delete-post');

    }

    private function permissionForManager(Role $role)
    {
        $role->attachPermissions('show-user', 'create-user', 'update-user');
    }

    private function permissionForSeoManager(Role $role)
    {
        $role->attachPermissions('show-post', 'create-post', 'update-post');
    }

}
