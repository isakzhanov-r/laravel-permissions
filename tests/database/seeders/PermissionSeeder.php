<?php

namespace Tests\Database\Seeders;

use IsakzhanovR\Permissions\Models\Permission;

class PermissionSeeder
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
            $this->items('Show user', 'Permission to show user'),
            $this->items('Create user', 'Permission to create user'),
            $this->items('Update user', 'Permission to update user'),
            $this->items('Delete user', 'Permission to delete user'),

            $this->items('Show post', 'Permission to show post'),
            $this->items('Create post', 'Permission to create post'),
            $this->items('Update post', 'Permission to update post'),
            $this->items('Delete post', 'Permission to delete post'),

            $this->items('Show post type', 'Permission to show post type'),
            $this->items('Create post type', 'Permission to create post type'),
            $this->items('Delete post type', 'Permission to delete post type'),

        ])->each(function ($item) {
            Permission::query()
                ->create($item);
        });
    }

    private function items($title, $description)
    {
        return compact('title', 'description');
    }
}
