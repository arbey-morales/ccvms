<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Session;
use Input;
use DB;
use Request;
abstract class BaseModel extends Model {

    use SoftDeletes;

    public static function boot(){
        parent::boot();

        static::creating(function($item){
            if(Session::get('usuario')){
                $item->creates_at = date('Y-m-d H:i:s');
            }
        });

        static::updating(function($item){
            if(Session::get('usuario')){
                $item->updated_at = date('Y-m-d H:i:s');
            }
        });

        static::deleting(function($item){
            if(Session::get('usuario')){
                $item->deleted_at = date('Y-m-d H:i:s');
            }
        });
    }
}