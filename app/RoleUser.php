<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    protected $fillable = ['id'];

    public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id')->with('permissions');
    }
}
