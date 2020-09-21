<?php

namespace Tests\PHPUnit;

use IsakzhanovR\Permissions\Models\Permission;
use IsakzhanovR\Permissions\Models\Role;
use Tests\Models\User;
use Tests\TestCase;

class PermissibleMethodsTests extends TestCase
{
    public function testHasPermission()
    {
        $user = User::find(1);
        $role = Role::find(1);

        $this->assertTrue($user->hasPermission('create-post'));
        $this->assertTrue($role->hasPermission('create-post'));
    }

    public function testHasPermissions()
    {
        $user = User::find(1);

        $this->assertTrue($user->hasPermissions('create-post', 'update-post', Permission::find(5), 7));
    }

    public function testMatchPermissions()
    {
        $user   = User::find(1);
        $user_3 = User::find(3);

        $this->assertTrue($user->matchPermissions('*post'));
        $this->assertFalse($user_3->matchPermissions('*post'));
    }

    public function testAttachPermission()
    {
        $user  = User::find(1);
        $count = $user->permissions()->get()->count();

        $user->attachPermissions(1, 2, 3, 4);
        $this->assertEquals($count + 4, $user->permissions()->get()->count());
    }

    public function testDetachPermission()
    {
        $role  = Role::first();
        $count = $role->permissions()->get()->count();

        $role->detachPermissions(1, 2, 3, 4);
        $this->assertEquals($count - 4, $role->permissions()->get()->count());
    }

    public function testSyncPermissions()
    {
        $role = Role::find(1);

        $role->syncPermissions([1, 2, 3, 4]);

        $this->assertEquals(4, $role->permissions()->get()->count());
    }
}
