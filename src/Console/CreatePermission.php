<?php

namespace IsakzhanovR\UserPermission\Console;

use Illuminate\Console\Command;
use IsakzhanovR\UserPermission\Helpers\Configable;
use IsakzhanovR\UserPermission\Traits\Console;
use Mockery\Exception;

class CreatePermission extends Command
{
    use Console;

    protected $signature = 'create:permission {name}';

    protected $description = 'Create a new permission';

    public function handle()
    {
        if ($this->permissionExist()) {
            throw new Exception(sprintf('Role "%s" already exists!', $this->name()));
        }

        $this->create();
    }

    protected function create()
    {
        $model = Configable::model('permission');

        $model::create(['title' => $this->name()]);

        $this->info(sprintf('Permission "%s" created successfully!', $this->name()));
    }
}
