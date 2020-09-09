<?php


namespace IsakzhanovR\UserPermission\Models;


use Illuminate\Database\Eloquent\Model;
use IsakzhanovR\UserPermission\Helpers\Configable;

class Permissible extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = Configable::table('permissible');
        parent::__construct($attributes);
    }

    public function permissible()
    {
        return $this->morphTo();
    }
}
