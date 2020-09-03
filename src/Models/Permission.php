<?php


namespace IsakzhanovR\UserPermission\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        parent::__construct($attributes);

        $this->setTable(Config::table('permissions'));
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany();
    }
}
