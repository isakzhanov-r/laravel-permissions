<?php

namespace Tests\PHPUnit;

use Illuminate\Support\Str;
use IsakzhanovR\Permissions\Models\Permission;
use IsakzhanovR\Permissions\Models\Role;
use Tests\Models\User;
use Tests\TestCase;

class DatabaseTests extends TestCase
{
    public function testUsers()
    {
        $users = User::query()->get();
        $user  = $users->first();

        $this->assertEquals(3, $users->count());
        $this->assertEquals('Hallie Bins', $user->name);
        $this->assertEquals('xkoepp@example.com', $user->email);
    }

    public function testRoles()
    {
        $roles = Role::query()->get();
        $role  = $roles->last();

        $this->assertEquals(3, $roles->count());
        $this->assertEquals('Seo Manager', $role->title);
        $this->assertEquals('seo-manager', $role->slug);
        $this->assertEquals('Seo Manager', $role->description);
    }

    public function testPermissions()
    {
        $permissions    = Permission::query()->get();
        $show_post_type = $permissions->where('slug', Str::slug('Show post type'))->first();

        $this->assertEquals(11, $permissions->count());
        $this->assertEquals('Show post type', $show_post_type->title);
        $this->assertEquals('Permission to show post type', $show_post_type->description);
    }
}
