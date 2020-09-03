<?php

namespace IsakzhanovR\UserPermission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use IsakzhanovR\UserPermission\Repositories\Contracts\RoleContract;
use IsakzhanovR\UserPermission\Helpers\Config;
use IsakzhanovR\UserPermission\Traits\SetAttribute;

class Role extends Model implements RoleContract
{
    use SetAttribute;

    protected $fillable = [
        'title', 'slug', 'description',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setTable(Config::table('roles'));

        parent::__construct($attributes);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany();
    }

    public function hasPermission($permission): bool
    {
        // TODO: Implement hasPermission() method.
    }
}
