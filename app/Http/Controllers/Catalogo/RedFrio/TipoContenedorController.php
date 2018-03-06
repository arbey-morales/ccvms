<?php

namespace App\Http\Controllers\Catalogo\RedFrio;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, DB, Input, Session, Response;
use Carbon\Carbon;

use App\Models\Catalogo\RedFrio\TipoContenedor;

class TipoContenedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = Input::only('q');
        $data = TipoContenedor::where('deleted_at',NULL);
        if ($parametros['q']) {
            $data = $data->where('nombre','LIKE',"%".$parametros['q']."%");
        }  
        $data = $data->get(); 

        if ($request->ajax()) {
            return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "total" => count($data)), 200);
        } else {             
            return view('catalogo.marca.index')->with('data', $data);
        }
    }
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         $data = TipoContenedor::find($id);
         if(!$data ){            
             return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
         }
         return response()->json([ 'data' => $data]);
     }
 
}
