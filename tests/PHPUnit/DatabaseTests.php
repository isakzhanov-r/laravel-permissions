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
        $user = User::query()
            ->where('email', 'xkoepp@example.com')->first();

        $this->assertEquals('Hallie Bins', $user->name);
    }

    public function testRoles()
    {
        $admin = Role::query()->find(3);

        $this->assertEquals('Seo Manager', $admin->title);
        $this->assertEquals('seo-manager', $admin->slug);
        $this->assertEquals('Seo Manager', $admin->description);
    }

    public function testPermissions()
    {
        $show_post_type = Permission::query()->find(9);

        $this->assertEquals('Show post type', $show_post_type->title);
        $this->assertEquals(Str::slug('Show post type'), $show_post_type->slug);
        $this->assertEquals('Permission to show post type', $show_post_type->description);
    }
}
