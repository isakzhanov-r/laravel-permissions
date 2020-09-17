<?php

namespace Tests\Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Tests\Models\User;

class UserSeeder
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
            $this->items('Hallie Bins', 'xkoepp@example.com', Hash::make('password')),
            $this->items('Santino Fay', 'zaria.paucek@example.com', Hash::make('password')),
            $this->items('Emmet Bayer', 'nmonahan@example.org', Hash::make('password')),

        ])->each(function ($item) {
            User::query()
                ->create($item);
        });
    }

    private function items($name, $email, $password)
    {
        return compact('name', 'email', 'password');
    }
}
