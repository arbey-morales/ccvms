<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['id'];

    public function permissions()
    {
        return $this->hasMany('App\PermissionRole', 'role_id', 'id')->select('id','permission_id','role_id')->with('permission');
    }
}
