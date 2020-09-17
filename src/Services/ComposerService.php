<?php

namespace IsakzhanovR\Permissions\Services;

use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Finder\SplFileInfo;

final class ComposerService
{
    protected $composer;

    public function __construct()
    {
        $this->boot();
    }

    public function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }

    public function namespace()
    {
        return (string) Str::of($this->app()->getNamespace())
            ->beforeLast('\\');
    }

    public function classes(): array
    {
        $classes     = [];
        $directories = Arr::get($this->composer->getPrefixesPsr4(), $this->app()->getNamespace());

        $files = $this->findFiles($directories);
        foreach ($files as $file) {
            if ($file instanceof SplFileInfo) {
                $fqcn = $this->getFullyQualifiedClassNameFromFile($file);

                $classes[$fqcn] = $file->getRealPath();
            }
        }

        return $classes;
    }

    protected function autoloadPath()
    {
        return $this->app()->basePath('vendor/autoload.php');
    }

    protected function finder(): SymfonyFinder
    {
        return new SymfonyFinder();
    }

    protected function findFiles($directories)
    {
        return $this->finder()
            ->files()
            ->name('*.php')
            ->in($directories);
    }

    protected function getFullyQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        return (string) Str::of($file->getRealPath())
            ->after($this->app()->basePath())
            ->replaceFirst('app', $this->namespace())
            ->replaceLast('.php', '')
            ->replace('/', '\\')
            ->trim(' \\')
            ->title();
    }

    private function boot()
    {
        if (file_exists($this->autoloadPath())) {
            $this->composer = require $this->autoloadPath();
        }
    }

}
