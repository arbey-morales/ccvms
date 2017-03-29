<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_role';

    protected $fillable = ['id'];

    public function permission()
    {
        return $this->hasOne('App\Permission', 'id', 'permission_id')->select('id', 'name', 'model')->orderBy('model', 'asc');
    }
}
