<?php

namespace Tests\PHPUnit;

use Illuminate\Support\Facades\Auth;
use Tests\Models\User;
use Tests\TestCase;

class MiddlewareAbilityTests extends TestCase
{

    public function testAdmin()
    {
        $user = User::find(1); // admin
        Auth::login($user);

        $posts   = $this->call('GET', 'ability/delete');
        $delete  = $this->call('GET', 'ability/posts');
        $manager = $this->call('GET', 'ability/manager');

        $posts->assertStatus(200);
        $this->assertEquals('successfully', $posts->getContent());

        $delete->assertStatus(200);
        $this->assertEquals('successfully', $delete->getContent());

        $manager->assertStatus(403);
        $manager->assertSeeText('User has not got a permissions');
    }

    public function testSeoManager()
    {
        $user = User::find(2); // manager and seo-manager
        Auth::login($user);

        $posts   = $this->call('GET', 'ability/posts');
        $delete  = $this->call('GET', 'ability/delete');
        $manager = $this->call('GET', 'ability/manager');

        $posts->assertStatus(200);
        $this->assertEquals('successfully', $posts->getContent());

        $delete->assertStatus(200);
        $this->assertEquals('successfully', $delete->getContent());

        $manager->assertStatus(200);
        $this->assertEquals('successfully', $manager->getContent());
    }

    public function testManager()
    {
        $user = User::find(3); // manager
        Auth::login($user);

        $posts   = $this->call('GET', 'ability/posts');
        $delete  = $this->call('GET', 'ability/delete');
        $manager = $this->call('GET', 'ability/manager');

        $posts->assertStatus(403);
        $posts->assertSeeText('User has not got a permissions');

        $delete->assertStatus(403);
        $delete->assertSeeText('User has not got a permissions');

        $manager->assertStatus(200);
        $this->assertEquals('successfully', $manager->getContent());
    }

    public function testAuthorized()
    {
        Auth::logout();

        $posts   = $this->call('GET', 'ability/posts');
        $delete  = $this->call('GET', 'ability/delete');
        $manager = $this->call('GET', 'ability/manager');

        $posts->assertStatus(403);
        $posts->assertSeeText('User is not authorized');

        $delete->assertStatus(403);
        $delete->assertSeeText('User is not authorized');

        $manager->assertStatus(403);
        $manager->assertSeeText('User is not authorized');
    }
}
