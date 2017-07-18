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
use App\Catalogo\Localidad;

class LocalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = Input::only('q');
       
        if (Auth::user()->is('root|admin')) {
            if ($parametros['q']) {
                $data =  Localidad::where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->with('municipio')->where('deleted_at',NULL)->get();
            } else {
                $data =  Localidad::with('municipio')->where('deleted_at',NULL)->get();
            }
        } else {
            $data = collect();
            if ($parametros['q']) {
                $municipios = Municipio::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->get();
                foreach($municipios as $key=> $mpio){
                    $localidades_temp = Localidad::where('municipios_id', $mpio->id)->where('deleted_at',NULL)->where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->with('municipio')->get(); 
                    foreach($localidades_temp as $id=> $item){
                        $data->push($item);
                    }
                }
            } else {
                $municipios = Municipio::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->get();
                foreach($municipios as $key=> $mpio){
                    $localidades_temp = Localidad::where('municipios_id', $mpio->id)->where('deleted_at',NULL)->with('municipio')->get(); 
                    foreach($localidades_temp as $id=> $item){
                        $data->push($item);
                    }
                }
            }
        }       
        return view('catalogo.localidad.index')->with('localidades', $data);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->back();
        /*$data = Localidad::with('municipio')->find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);*/
    }
}
