<?php

namespace IsakzhanovR\Permissions\Commands;

use Illuminate\Console\Command;
use IsakzhanovR\Permissions\Helpers\Configable;
use IsakzhanovR\Permissions\Traits\Console;

class CreatePermission extends Command
{
    use Console;

    protected $signature = 'laravel-permissions:create-permission {name}';

    protected $description = 'Create a new permission';

    public function handle()
    {
        if ($this->permissionExist()) {
            $this->error(sprintf('Permission "%s" already exists!', $this->name()));
        }

        $this->create();
    }

    /**
     * @throws \Exception
     */
    protected function create()
    {
        $model = Configable::model('permission');

        $model::query()
            ->create(['title' => $this->name()]);

        $this->info(sprintf('Permission "%s" created successfully!', $this->name()));
    }
}
