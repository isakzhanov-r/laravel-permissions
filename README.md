# Laravel Permissions

Basic roles and permissions is a succinct and flexible way to add Role-based Permissions

<p align="center">
    <a href="https://packagist.org/packages/isakzhanov-r/laravel-permissions"><img src="https://img.shields.io/packagist/dt/isakzhanov-r/laravel-permissions.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/isakzhanov-r/laravel-permissions"><img src="https://poser.pugx.org/isakzhanov-r/laravel-permissions/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/isakzhanov-r/laravel-permissions"><img src="https://poser.pugx.org/isakzhanov-r/laravel-permissions/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/isakzhanov-r/laravel-permissions/license?format=flat-square" alt="License" /></a>
</p>

## Contents
* [Installation](#installation)
* [Configuration](#configuration)
    * [Role model](#role-model)
    * [Permission model](#permission-model)
    * [Permissible model](#permissible-model)

* [Usage](#usage)
    * [Creating](#creating)
    * [User model](#user-model)
    * [Attach, detach and sync permissions](#attach-detach-and-sync-permissions)
        * [Attach permissions](#attach-permissions)
        * [Detach permissions](#detach-permissions)
        * [Syncing permissions](#syncing-permissions)
    * [Checking for permissions](#checking-for-permissions)
    * [Middleware](#middleware)
    * [Artisan commands](#artisan-commands)
* [License](#license)

## Installation

To get the latest version of Laravel Permissions package, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require isakzhanov-r/laravel-permissions
```

Instead, you can, of course, manually update the dependency block `require` in `composer.json` and run `composer update` if you want to:

```json
{
    "require-dev": {
        "isakzhanov-r/laravel-permissions": "^1.0"
    }
}
```

If you don't use auto-discovery, add the `ServiceProvider` to the providers array in `config/app.php`:

```php
IsakzhanovR\Permissions\ServiceProvider::class;
```

Copy the package config to your local config with the publish command:
```
php artisan vendor:publish --provider="IsakzhanovR\Permissions\ServiceProvider"
```

You can create the DB tables by running the migrations:
```
php artisan migrate
```

This command will create such `roles`, `permissions`, `user_roles` and `permissible` tables.

But you can also use the command for automatic installation

```
php artisan laravel-permissions:migrate
```
This command will publish the configuration file. Run the migration command, generate models for tables, and change the User model . all configuration of the package will happen automatically. after executing this command, you don't need to configure anything.
```
Role.php
Permission.php
Permissible.php
```

##Configuration

To further customize table names and model namespaces, edit the `config/laravel_permissions.php` after you run command
```
php artisan vendor:publish --provider="IsakzhanovR\Permissions\ServiceProvider
```
before you run the command `php artisan migrate` four new tables will be present:
- `tables`
  - `users` - the default table for the relationship to the user
  - `roles` - stores role records
  - `permissions` - stores permission records
  - `user_roles` - stores [many-to-many](https://laravel.com/docs/master/eloquent-relationships#many-to-many) relations between roles and users
  - `permissible` - stores many-to-many[polymorphic](https://laravel.com/docs/master/eloquent-relationships#many-to-many-polymorphic-relations) relations between another essences (User,Role, ...) and permissions

- `foreign_key` - the key relationships in the tables
- `models` - references to model classes

### Models

#### Role model
* Create a Role model, using the following example:
```php
namespace App\Models;

use IsakzhanovR\Permissions\Models\Role as LaravelRole;

final class Role extends LaravelRole
{
        protected $fillable = [
            'title',
            'slug',
            'description',
        ];
}
```
* After creating model set the model reference in file `config/laravel_permissions`
```php
return [
//...
'models' => [
    //....
    'role' => App\Models\Role::class, // or use string 'App\\Models\\Role'
    //....
    ],
];
```
* Or use command `php artisan laravel-permissions:migrate` to generate this automaticaly

The `Role` model has three main attributes:

- `slug` — Unique name for the Role, used for looking up role information in the application layer. For example: "admin", "owner", "manager".
- `title` — Human readable name for the Role. Not necessarily unique and optional. For example: "User Administrator", "Project Owner", "Project Manager".
- `description` — A more detailed explanation of what the Role does. Also optional.
And description are optional; its field is nullable in the database.

Base model `IsakzhanovR\Permissions\Models\Role` use trait `HasPermissions`

#### Permission model
* Create a Permission model, using the following example:
```php
namespace App\Models;

use IsakzhanovR\Permissions\Models\Permission as LaravelPermission;

final class Permission extends LaravelPermission
{
        protected $fillable = [
            'title',
            'slug',
            'description',
        ];
}
```
* After creating model set the model reference in file `config/laravel_permissions`
```php
return [
//...
'models' => [
    //....
    'permission' => App\Models\Permission::class,
    //....
    ],
//....
];
```
* Or use command `php artisan laravel-permissions:migrate` to generate this automaticaly

The `Permission` model has the same three attributes as the Role:

- `slug` — Unique name for the Permission, used for looking up role information in the application layer. For example: "create-user", "update-user", "delete-user".
- `title` — Human readable name for the Permission. Not necessarily unique and optional. For example: "Create User", "Update User", "Delete User".
- `description` — A more detailed explanation of what the Permission does.

if you look at the default model `IsakzhanovR\Permissions\Models\Permission`, you will see [morphedByMany](https://laravel.com/docs/master/eloquent-relationships#many-to-many-polymorphic-relations) relationships for users and roles. You can extend this list with your own entities.

Example :
```php
final class Permission extends LaravelPermission
{
    ....
    public function posts()
    {
        return $this->morphedByMany(PostType::class, 'permissible', 'permissible');
    }
    ....
}
```

And `PostType` model must use trait `HasPermissions`

#### Permissible model
This is the standard polymorph pivot model
The `Permissible` model has the same three attributes:
- `permission_id` — Foreign key for the Permission.
- `permissible_type` — Belong to model (Class name).
- `permissible_id` — Belong to model (key).

You also need to create a model `Permissible` and inherit from `IsakzhanovR\Permissions\Models\Permissible`

```php
use IsakzhanovR\Permissions\Models\Permissible as LaravelPermissible;

final class Permissible extends LaravelPermissible
{

}
```
After creating model you also need to append link in config file
```php
return [
//...
    'models' => [
        //....
        "permissible" => App\Models\Permissible::class,
        //....
    ],
];
```

Or use the command to automatically install the package and generate models and a configuration file
## Usage
###Creating
Let's start by creating the following `Roles` and `Permissions`:
```php
    use \App\Models\Role;
    $admin = new Role();
    $admin->slug  = 'admin';
    $admin->title = 'Administrator';
    $admin->description  = 'User is the administrator of a project'; // optional
    $admin->save();

    $manager = new Role();
    $manager->title = 'Project Manager';
    // if you do not specify 'slug' from title 'project-manager' by the method setSlugAttribute()
    $manager->description  = 'User is the manager of a given project'; // optional
    $manager->save();
```

Now we just need to creat permissions:
 We will create permissions without a description, and slug is created from title
```php
    use \App\Models\Permission;
    $createPost = new Permission();
    $createPost->title = 'Create Post'; // slug = create-post
    $createPost->save();

    $createNews = new Permission();
    $createNews->title = 'Create News'; // slug = create-news
    $createNews->save();

    $updatePost = new Permission();
    $updatePost->title = 'Update Post'; // slug = update-post
    $updatePost->save();

    $updateNews = new Permission();
    $updateNews->title = 'Update News'; // slug = update-news
    $updateNews->save();

    $updateProfile = new Permission();
    $updateProfile->title = 'Update Profile'; // slug = update-profile
    $updateProfile->save();

    $deletePost = new Permission();
    $deletePost->title = 'Delete Post'; // slug = delete-post
    $deletePost->save();

    $deleteNews = new Permission();
    $deleteNews->title = 'Delete News'; // slug = delete-news
    $deleteNews->save();
```
### User model
First, use the `IsakzhanovR\Permissions\Traits\HasPermissions` and `IsakzhanovR\Permissions\Traits\HasRoles` traits to your `User` model.

If you use the command `php artisan laravel-permissions:migrate`, these changes will occur automatically and your `User` model will look like this:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;

use IsakzhanovR\Permissions\Traits\HasPermissions;
use IsakzhanovR\Permissions\Traits\HasRoles;
use IsakzhanovR\Permissions\Contracts\PermissibleContract;
use IsakzhanovR\Permissions\Contracts\RoleableContract;

final class User  extends Authenticatable implements PermissibleContract, RoleableContract
{
    use HasRoles, HasPermissions; // add this traits to your user model

    ....
}
```

### Attach, detach and sync permissions
This package allows users to be associated with permissions and roles.
Each role is associated with several permissions. in this case, the `User` can have a separate `Permission` that is not included in the list of permissions for the role.
`Role` and `Permission` are the usual Eloquent  models.

#### Attach permissions
So, when the roles are created let's assign them to the users. Thanks to the `HasRoles` trait this is as easy as:

```php
    use \App\Models\User;
    $user = User::find(1);

    // abstraction over a method $user->roles()->attach($admin->id)
    $user->attachRole($admin); // parameter can be an Role object, id or slug

    // you can also add multiple user roles
    // equivalent to $user->roles()->sync(array($admin->id, $manager->id));
    $user->attachRoles($admin,$manager,...);
```
The method `$user->attachRole()`  accepts an argument that can be an `id`, `slug`, or `instance of Role` the model
```php
    $user->attachRole(1);                   // id
    //Or
    $user->attachRole('project-manager');   // slug
    //Or
    $user->attachRole(Role::find(1));       // instance of Role

    //Multiple
    $user->attachRoles(1,2,3);
    //Or
    $user->attachRoles('admin','manager','owner');
    //Or
    $user->attachRoles(Role::find(1),Role::find(2),Role::find(3));
```

Let's start adding permissions to roles and users:
```php
    $admin->attachPermissions($createPost,$createNews,$updateProfile);
    $manager->attachPermissions($updatePost,$updateNews);
    $user->attachPermissions($deletePost,$deleteNews);
```
So we added permissions for the admin and manager roles and also gave personal permissions for the user.

All entities that use trait `HasPermission` have access to permission relationships and the following methods for adding permissions:
```php
    $subject->attachPermission(1);                      // id
    //Or
    $subject->attachPermission('create-post');          // slug
    //Or
    $subject->attachPermission(Permission::find(1));    // instance of Permission

    //Multiple
    $subject->attachPermissions(1,'create-post',Permission::find(1),Permission::query()->get());
```

#### Detach permissions
To revoke roles and permissions, use the following methods:
```php
    $user->detachRole(1);                   // id
    //Or
    $user->detachRole('project-manager');   // slug
    //Or
    $user->detachRole(Role::find(1));       // instance of Role

    //Multiple
    $user->detachRoles(1,'admin',Role::find(1),Role::query()->whereIn('id',[1,2,3])->get());

    $subject->detachPermission(1);                      // id
    //Or
    $subject->detachPermission('create-post');          // slug
    //Or
    $subject->detachPermission(Permission::find(1));    // instance of Permission

    //Multiple
    $subject->detachPermissions(1,2,3);
```

#### Syncing permissions
To synchronization roles and permissions, use the following methods:
```php
    $user->syncRoles([1,2,3]);            // array roles ids

    $role->syncPermissions([1,2,3,4]);    //array permissions ids

    $subject->syncPermissions([1,2,3,4]); //array permissions ids
```

### Checking for permissions

Now we can check for roles and permissions simply by doing:
```php
    $user = User::find(1);

    // with role slug:
    $user->hasRole('project-manager'):bool

    // with role id:
    $user->hasRole(1):bool

    // with role instance:
    $user->hasRole(Role::find(1)): bool

```
You can have as many roles as you want for each user, and Vice versa.
```php
    $user = User::find(1);

    // with roles slug, id or instance:
    $user->hasRoles('project-manager',2, Role::find(3)):bool

    // if user has only 'admin' role return false
    $user->hasRoles('project-manager','admin'):bool
```
We can check permissions for entities using the following method:
```php
    $user = User::find(1);

    // with permission slug or id or instance:
    $subject->hasPermission('create-post'):bool
    // Or use `can`
    $subject->can('create-post'):bool

    // with permission slug, id or instance:
    $user->hasPermissions('create-post',2, Permission::find(3)):bool
```
You can also use placeholders (wildcards) to check any matching permission by doing:
```php
    $user = User::find(1);

    // match any create permission:
    $subject->matchPermissions('create*'):bool

    // match any permission about post
    $subject->matchPermissions('*post'):bool

```
### Middleware
You can use a middleware to filter routes and route groups by permission or role. Add middlewares in `$routeMiddleware` of `app/Http/Kernel.php` file:
```php
use IsakzhanovR\Permissions\Http\Middleware\Permission;
use IsakzhanovR\Permissions\Http\Middleware\Role;
use IsakzhanovR\Permissions\Http\Middleware\Ability;

protected $routeMiddleware = [
    // ...

    'role'        => Role::class,        // Checks for the entry of one of the specified permissions.
    'permission'  => Permission::class,  // Checks for the occurrence of one of the specified roles.
    'ability'     => Ability::class,     // Checks the entry of all of the specified roles.
]
```

You can use a middleware to filter routes and route groups by permission or role

```php
// Example, user has been a `seo-manager` `project-manager` roles and a `create-post` `update-post` permissions

// success access
app('router')
    ->middleware('role:project-manager,seo-manager', 'permission:create-post,update-post')
    ->get(...)

// failed access because user has not role `admin`
app('router')
    ->middleware('role:project-manager,admin')
    ->get(...)

// failed access because user has not permission `delete-post`
app('router')
    ->middleware('permission:create-post,update-post,delete-post')
    ->get(...)
```

If you need to check whether there are matches in permissions use `ability`

```php
// Example, user has been a `seo-manager` `project-manager` roles and a `create-post` `update-post` permissions

// success access
app('router')
    ->middleware('ability:*manager')
    ->get(...)

// success access
app('router')
    ->middleware('ability:*post')
    ->get(...)

// failed access because user has not permission `delete` anything
app('router')
    ->middleware('ability:delete*')
    ->get(...)

```

### Artisan commands
You can create a role or a permission from a console with artisan commands:
```
php artisan laravel-permissions:create-role {name}

php artisan laravel-permissions:create-permission {name}
```
You can also invoke the creation of roles and permissions from your application:

Artisan::call('laravel-permissions:create-role', ['name' => $name]);
Artisan::call('laravel-permissions:create-permission', ['name' => $name]);

## License
This package was written under [Andrey Helldar](https://github.com/andrey-helldar) supervision under the [MIT License](LICENSE.md).
