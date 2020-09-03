<?php

namespace IsakzhanovR\UserPermission\Console;

use Illuminate\Console\Command;
use IsakzhanovR\UserPermission\Support\Config;
use IsakzhanovR\UserPermission\Traits\Console;
use Mockery\Exception;

class CreateRole extends Command
{
    use Console;

    protected $signature = 'create:role {name}';

    protected $description = 'Create a new role';

    public function handle()
    {
        if ($this->roleExist()) {
            throw new Exception(sprintf('Role "%s" already exists!', $this->name()));
        }

        $this->create();
    }

    protected function create()
    {
        $role = Config::model('role');

        $role::create(['title' => $this->name()]);

        $this->info(sprintf('Role "%s" created successfully!', $this->name()));
    }
}
