<?php

namespace IsakzhanovR\Permissions\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

final class GeneratorModelService
{
    public function loadView()
    {
        return View::addNamespace('laravel_permissions', substr(__DIR__, 0, -12) . 'views');
    }

    public function generateModel(string $filename, string $directory = '')
    {
        $directory = ucfirst($directory);
        $filename  = ucfirst($filename);
        $path      = app_path($directory . DIRECTORY_SEPARATOR . $filename . '.php');
        $namespace = app()->getNamespace() . $directory;
        $output    = View::make('laravel_permissions::generators.' . $filename)
            ->with(compact('filename', 'namespace'))
            ->render();

        $this->saveFile($path, $output);

        return $this->updateConfig($filename, $namespace . DIRECTORY_SEPARATOR . $filename);

    }

    public function editFile($model, string $extended)
    {
        $path    = Arr::first(array_keys($model));
        $content = file_get_contents($path);
        file_put_contents($path, $this->replaceModelFile($content, $extended));

        return $this->updateConfig(class_basename($extended), Arr::first($model));
    }

    public function editUserFile($model)
    {
        $path    = Arr::first(array_keys($model));
        $user    = Arr::first($model);
        $content = file_get_contents($path);
        file_put_contents($path, $this->replaceContentUserModel($content));

        return $this->updateConfig(class_basename($user), $user);
    }

    private function replaceModelFile($content, $to)
    {
        $basename = class_basename($to);
        $content
                  = Str::replaceFirst("use Illuminate\Database\Eloquent\Model", "use {$to} as Laravel{$basename}", $content);

        $content
            = Str::replaceLast("extends Model", "extends Laravel{$basename}", $content);

        return $content;
    }

    private function replaceContentUserModel($content)
    {
        $content =
            Str::replaceFirst("use Illuminate\Notifications\Notifiable;",
                "use Illuminate\Notifications\Notifiable;\n" .
                "use IsakzhanovR\Permissions\Traits\HasPermissions;\n" .
                "use IsakzhanovR\Permissions\Traits\HasRoles;\n" .
                "use IsakzhanovR\Permissions\Contracts\PermissibleContract;\n" .
                "use IsakzhanovR\Permissions\Contracts\RoleableContract;\n", $content);

        $content = Str::replaceFirst("extends Authenticatable", " extends Authenticatable implements PermissibleContract, RoleableContract\n", $content);

        $content
            = Str::replaceLast("Notifiable;", "Notifiable, HasRoles, HasPermissions;\n", $content);

        return $content;
    }

    private function saveFile($file, $output)
    {
        if (! file_exists($file) && $fs = fopen($file, 'x')) {
            fwrite($fs, $output);
            fclose($fs);

            return true;
        }

        return false;
    }

    private function updateConfig($name, $value)
    {
        $value = (string) Str::of($value)->replace('/', '\\');
        Config::set('laravel_permissions.models.' . Str::slug($name), $value);
        $data = var_export(Config::get('laravel_permissions'), 1);

        return File::put(base_path('config/laravel_permissions.php'), "<?php\n return $data ;");
    }
}
