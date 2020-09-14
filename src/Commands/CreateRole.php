<?php

namespace IsakzhanovR\Permissions\Commands;

use Illuminate\Console\Command;
use IsakzhanovR\Permissions\Helpers\Configable;
use IsakzhanovR\Permissions\Traits\Console;

class CreateRole extends Command
{
    use Console;

    protected $signature = 'laravel-permissions:create-role {name}';

    protected $description = 'Create a new role';

    public function handle()
    {
        if ($this->roleExist()) {
            $this->error(sprintf('Role "%s" already exists!', $this->name()));
        }

        $this->create();
    }

    /**
     * @throws \Exception
     */
    protected function create()
    {
        $role = Configable::model('role');

        $role::query()
            ->create(['title' => $this->name()]);

        $this->info(sprintf('Role "%s" created successfully!', $this->name()));
    }
}
