<?php

namespace IsakzhanovR\Permissions\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use IsakzhanovR\Permissions\Models\Permissible;
use IsakzhanovR\Permissions\Models\Permission;
use IsakzhanovR\Permissions\Models\Role;
use IsakzhanovR\Permissions\ServiceProvider;
use IsakzhanovR\Permissions\Services\ComposerService;
use IsakzhanovR\Permissions\Services\GeneratorModelService;
use IsakzhanovR\Permissions\Traits\Console;
use IsakzhanovR\Permissions\Traits\HasPermission;
use IsakzhanovR\Permissions\Traits\HasRoles;

class Migration extends Command
{
    use Console;

    protected $composer;

    protected $generator;

    protected $signature = 'laravel-permissions:migrate';

    protected $description = 'Install laravel permissions';

    public function __construct(ComposerService $composer_service, GeneratorModelService $generator_model_service)
    {
        $this->composer  = $composer_service;
        $this->generator = $generator_model_service;
        parent::__construct();
    }

    public function handle()
    {
        $tags    = ['config'];
        $classes = $this->composer->classes();
        $this->generator->loadView();

        $this->line('');
        $this->info('Publishing the assets and config files');
        $this->call('vendor:publish', ['--provider' => ServiceProvider::class, '--tag' => $tags]);

        $this->line('');
        $this->info('Migrating the database tables into your application');
        $this->call('migrate');

        $this->generateUserModel($classes);
        $this->line('');

        $this->generateModel($classes, 'Role', Role::class);
        $this->line('');

        $this->generateModel($classes, 'Permission', Permission::class);
        $this->line('');

        $this->generateModel($classes, 'Permissible', Permissible::class);
        $this->line('');
    }

    protected function generateUserModel(array $classes)
    {
        $user_model = $this->getModel($classes, 'User');

        if ($user_model && ! $this->hasTraits(Arr::first($user_model), HasRoles::class, HasPermission::class)) {
            $this->line('');

            $this->generator->editUserFile($user_model);
            $this->info('File ' . Arr::first($user_model) . ' edited');
        }
    }

    protected function generateModel(array $classes, string $name, string $extended)
    {
        $model = $this->getModel($classes, $name);

        if ($model) {
            if (! is_subclass_of(Arr::first($model), $extended)) {
                $this->generator->editFile($model, $extended);
                $this->info("{$name} Model successfully edited!");

            }

            return;
        }

        if ($this->generator->generateModel($name, 'Models')) {
            $this->info("{$name} Model successfully created!");
        } else {
            $this->error("Couldn't create Model.\n Check the write permissions");
        }
    }

    protected function getModel(array $classes, $model)
    {
        return array_filter(array_flip($classes), function ($class) use ($model) {
            return Str::contains($class, $model);
        });
    }

    protected function hasTraits($class, ...$traits)
    {
        return $contains = Arr::hasAny(class_uses($class), $traits);
    }
}
