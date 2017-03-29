<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session; 
use App\Catalogo\Clue;

class ClueController extends Controller
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
                $data =  Clue::where('clues','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->with('municipio','localidad','jurisdiccion')->get();
            } else {
                $data =  Clue::with('municipio','localidad','jurisdiccion')->get();
            }
        } else {
            if ($parametros['q']) {
                $data = Clue::where('clues','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->where('idJurisdiccion', Auth::user()->idJurisdiccion)->where('borradoAl',NULL)->with('municipio','localidad','jurisdiccion')->get();
            } else {
                $data = Clue::where('idJurisdiccion', Auth::user()->idJurisdiccion)->where('borradoAl',NULL)->with('municipio','localidad','jurisdiccion')->get();
            }
        }       
        return view('catalogo.clue.index')->with('clues', $data);
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
        /*$data = Clue::with('municipio','localidad','jurisdiccion')->find($id);        
        
        if(!$data ){            
            //return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
        }

       // return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);
        return response()->json([ 'data' => $data]);*/
    }
}
