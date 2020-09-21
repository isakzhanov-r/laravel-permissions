<?php

namespace Tests\PHPUnit;

use Illuminate\Support\Facades\Auth;
use Tests\Models\User;
use Tests\TestCase;

class MiddlewareRoleTests extends TestCase
{
    public function testAdmin()
    {
        $user = User::find(1); // admin
        Auth::login($user);

        $admin   = $this->call('GET', 'role/admin');
        $manager = $this->call('GET', 'role/manager');
        $denies  = $this->call('GET', 'role/denies');

        $admin->assertStatus(200);
        $this->assertEquals('successfully', $admin->getContent());

        $manager->assertStatus(403);
        $manager->assertSeeText('User has not got a role');

        $denies->assertStatus(403);
        $denies->assertSeeText('User has not got a role');
    }

    public function testSeoManager()
    {
        $user = User::find(2); // manager and seo-manager
        Auth::login($user);

        $admin   = $this->call('GET', 'role/admin');
        $manager = $this->call('GET', 'role/manager');
        $denies  = $this->call('GET', 'role/denies');

        $admin->assertStatus(403);
        $admin->assertSeeText('User has not got a role');

        $manager->assertStatus(200);
        $this->assertEquals('successfully', $manager->getContent());

        $denies->assertStatus(403);
        $denies->assertSeeText('User has not got a role');
    }

    public function testManager()
    {
        $user = User::find(3); // manager
        Auth::login($user);

        $admin       = $this->call('GET', 'permission/admin');
        $manager     = $this->call('GET', 'permission/manager');
        $seo_manager = $this->call('GET', 'permission/seo-manager');

        $admin->assertStatus(403);
        $admin->assertSeeText('User has not got a permissions');

        $manager->assertStatus(200);
        $this->assertEquals('successfully', $manager->getContent());

        $seo_manager->assertStatus(403);
        $seo_manager->assertSeeText('User has not got a permissions');
    }
}
