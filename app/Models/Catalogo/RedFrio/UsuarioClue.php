<?php


namespace App\Models\Catalogo\RedFrio;
use App\Models\BaseModel;

class UsuarioClue extends BaseModel
{
    protected $table = 'users_clues';

    public function clue(){
        return $this->belongsTo('App\Catalogo\Clue', 'clues_id', 'id');
    }
    public function usuario(){
		return $this->belongsTo('App\user','users_id','id');
    }
}
