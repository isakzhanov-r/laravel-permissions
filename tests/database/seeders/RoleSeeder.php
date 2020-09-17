<?php

namespace Tests\Database\Seeders;

use Illuminate\Database\Seeder;
use IsakzhanovR\Permissions\Models\Role;

class RoleSeeder extends Seeder
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
        collect([
            $this->items('Admin', 'Administrator'),
            $this->items('Manager', 'Manager'),
            $this->items('Seo Manager', 'Seo Manager'),

        ])->each(function ($item) {
            Role::query()
                ->create($item);
        });
    }

    private function items($title, $description)
    {
        return compact('title', 'description');
    }
}
