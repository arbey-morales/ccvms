<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session;
use App\Catalogo\Ageb;
use App\Catalogo\Municipio;

class AgebController extends Controller
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
                $data =  Ageb::where('id','LIKE',"%".$parametros['q']."%")->with('municipio','localidad')->get();
            } else {
                $data =  Ageb::with('municipio','localidad')->get();
            }
        } else {
            $data = collect();
            if ($parametros['q']) {
                $municipios = Municipio::where('idJurisdiccion', Auth::user()->idJurisdiccion)->where('borradoAl',NULL)->get();
                foreach($municipios as $key=> $mpio){
                    $agebs_temp = Ageb::where('id','LIKE',"%".$parametros['q']."%")->where('idMunicipio', $mpio->id)->where('deleted_at',NULL)->with('municipio','localidad')->get(); 
                    foreach($agebs_temp as $k=> $i){
                        $data->push($i);
                    }
                }
            } else {
                $municipios = Municipio::where('idJurisdiccion', Auth::user()->idJurisdiccion)->where('borradoAl',NULL)->get();
                foreach($municipios as $key=> $mpio){
                    $agebs_temp = Ageb::where('idMunicipio', $mpio->id)->where('deleted_at',NULL)->with('municipio','localidad')->get(); 
                    foreach($agebs_temp as $k=> $i){
                        $data->push($i);
                    }
                }
            }
        }       
        return view('catalogo.ageb.index')->with('agebs', $data);
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
        /*$data = Ageb::with('municipio','localidad')->find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);*/
    }
}
