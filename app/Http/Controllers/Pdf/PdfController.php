<?php

namespace App\Http\Controllers\Pdf;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use Input;

use App\Transaccion\Persona;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function persona() 
    {
        if (Auth::user()->is('root|admin')) {
            $data = Persona::where('deleted_at', NULL)->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->orderBy('id', 'DESC')->get();
        } else { // Limitar por clues
            $data = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('clues.idJurisdiccion', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->orderBy('personas.id', 'DESC')->get();
        }
        
        $view =  \View::make('pdf.persona', compact('data'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('LEGAL', 'landscape');
        return $pdf->stream('persona');
    }
}
