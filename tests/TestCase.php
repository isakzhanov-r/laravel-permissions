<?php

namespace Tests;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use IsakzhanovR\Permissions\ServiceProvider;
use Tests\Database\Seeders\DatabaseSeeder;
use Tests\Models\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected $database = 'testbench';

    protected function setUp(): void
    {
        parent::setUp();

        $this->migrate();
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->setDatabase($app);
        $this->setRoutes($app);
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(Kernel::class, Http\Kernel::class);
    }

    protected function createUser()
    {
        $name = Str::random();

        return User::create([
            'name'     => $name,
            'email'    => $name . '@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    private function setDatabase($app)
    {
        $app['config']->set('database.default', $this->database);

        $app['config']->set('database.connections.' . $this->database, [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    private function setRoutes($app)
    {
        collect([
            $this->generateRoute('role/admin', 'role:admin'),
            $this->generateRoute('role/manager', 'role:manager,seo-manager'),
            $this->generateRoute('role/denies', 'role:admin, manager'),

            $this->generateRoute('permission/admin', 'permission:create-user,create-post,create-post-type,delete-post-type'),
            $this->generateRoute('permission/manager', 'permission:show-user,create-user,update-user'),
            $this->generateRoute('permission/seo-manager', 'permission:show-post,create-post,update-post,delete-post'),

            $this->generateRoute('ability/posts', 'ability:*post'),
            $this->generateRoute('ability/delete', 'ability:delete*'),
            $this->generateRoute('ability/manager', 'ability:*manager'),
        ])->each(function ($route) use ($app) {
            $app['router']->get($route['url'], function () {
                return 'successfully';
            })->middleware($route['middleware']);
        });
    }

    private function generateRoute($url, $middleware)
    {
        return compact('url', 'middleware');
    }

    private function migrate()
    {
        $this->loadLaravelMigrations($this->database);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->artisan('migrate')->run();

        DatabaseSeeder::run();
    }

}
