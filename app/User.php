<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements AuthenticatableContract,
                                    CanResetPasswordContract, 
                                    HasRoleAndPermissionContract
{
    use Authenticatable, CanResetPassword, HasRoleAndPermission;
    // Quité: AuthorizableContract de implements
    // Quité: Authorizable de la lista de use's

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'paterno', 'materno', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

    public function jurisdiccion()
    {
        return $this->belongsTo('App\Catalogo\Jurisdiccion', 'idJurisdiccion', 'id');
    }

    public function rolesuser()
    {
        return $this->hasMany('App\RoleUser', 'user_id', 'id')->with('role');
    }
    
    public function notificacion()
    {
        return $this->hasMany('App\Models\ReporteContenedor\SisUsuariosNotificaciones', 'sis_usuarios_id', 'id');
    }
    
}
