<?php


namespace IsakzhanovR\UserPermission\Models;


use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\UserPermission\Helpers\Config;
use IsakzhanovR\UserPermission\Repositories\Contracts\PermissionContract;
use IsakzhanovR\UserPermission\Traits\SetAttribute;

class Permission extends Model implements PermissionContract
{
    use SetAttribute;

    protected $fillable = [
        'title', 'slug', 'description',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Config::table('permissions');
        parent::__construct($attributes);
    }

    public function users()
    {
        return $this->morphedByMany(Config::model('user'), 'permissible');
    }

    public function roles()
    {
        return $this->morphedByMany(Config::model('role'), 'permissible');
    }
}
