<?php

namespace Tests\PHPUnit;

use IsakzhanovR\Permissions\Models\Role;
use Tests\Models\User;
use Tests\TestCase;

class RoleableMethodsTests extends TestCase
{
    public function testHasRole()
    {
        $user = User::find(1);

        $this->assertTrue($user->hasRole(Role::find(1)));
        $this->assertFalse($user->hasRole(Role::find(2)));
    }

    public function testHasRoles()
    {
        $user = User::find(2);

        $this->assertTrue($user->hasRoles('seo-manager', Role::find(2)));
        $this->assertFalse($user->hasRoles('admin', Role::find(2), 3));
    }

    public function testAttachRole()
    {
        $user = User::find(1);

        $user->attachRole(Role::find(2));

        $this->assertTrue($user->hasRoles('admin', Role::find(2)));
    }

    public function testDetachRole()
    {
        $user = User::find(1);

        $user->detachRole(Role::find(2));

        $this->assertFalse($user->hasRoles('admin', Role::find(2)));
    }

    public function testSyncRoles()
    {
        $user = User::find(1);

        $user->syncRoles([1, 2]);

        $this->assertTrue($user->hasRoles('admin', Role::find(2)));
        $this->assertFalse($user->hasRoles('admin', Role::find(3)));
    }
}
