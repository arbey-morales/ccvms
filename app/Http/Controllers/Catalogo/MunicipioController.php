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
    public function index()
    {
        $parametros = Input::only('q');

        if (Auth::user()->is('root|admin')) {
            if ($parametros['q']) {
                $data =  Municipio::where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->with('jurisdiccion')->where('borradoAl',NULL)->get();
            } else {
                $data =  Municipio::with('jurisdiccion')->where('borradoAl',NULL)->get();
            }
        } else {
            if ($parametros['q']) {
                $data = Municipio::where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->where('idJurisdiccion', Auth::user()->idJurisdiccion)->where('borradoAl',NULL)->with('jurisdiccion')->get();
            } else {
                $data = Municipio::where('idJurisdiccion', Auth::user()->idJurisdiccion)->where('borradoAl',NULL)->with('jurisdiccion')->get();
            }
        }       
        return view('catalogo.municipio.index')->with('municipios', $data);    
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
        /*
        $data = Municipio::with('jurisdiccion')->find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);*/
    }
}