<?php

namespace App\Http\Controllers\Catalogo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session;
use App\Catalogo\Municipio;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = Input::only('q','municipios_id');
        $data = Municipio::where('deleted_at',NULL)->with('jurisdiccion')
            ->orderBy('nombre', 'ASC');
        if (Auth::user()->is('root|admin')) { } else {
            $data = $data->where('jurisdicciones_id', Auth::user()->idJurisdiccion);  
        }  
        if ($parametros['q']) {
            $data = $data = $data->where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%");
        }
        if ($parametros['municipios_id']) {
            $data = $data = $data->where('municipios_id', $parametros['q']);
        }

        $data = $data->get();
        
        if ($request->ajax()) {
            return response()->json([ 'data' => $data]);
        } else {
            return view('catalogo.municipio.index')->with('municipios', $data);   
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
        $data = Municipio::with('clues','localidades')->find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return response()->json([ 'data' => $data]);
    }
}
