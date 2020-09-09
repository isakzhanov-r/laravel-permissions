<?php


namespace IsakzhanovR\UserPermission\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use IsakzhanovR\UserPermission\Helpers\Configable;
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
        $this->table = Configable::table('permissions');
        parent::__construct($attributes);
    }

    public function permissible(): HasMany
    {
        return $this->hasMany(Configable::model('permissible'), Configable::foreignKey('permission'));
    }

    public function users()
    {
        return $this->morphedByMany(Configable::model('user'), 'permissible', Configable::table('permissible'));
    }

    public function roles()
    {
        return $this->morphedByMany(Configable::model('role'), 'permissible', Configable::table('permissible'));
    }
}
