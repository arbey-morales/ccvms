<?php

namespace App\Http\Controllers\Transaccion;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Carbon\Carbon;

use Session;
use App\Transaccion\TemperaturaContenedor;
use App\Catalogo\Clue;

class TemperaturaContenedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1) { 
            $parametros = Input::only('fecha_inicial','fecha_final','por_dia');  
            $data = TemperaturaContenedor::select('fecha','temperatura','hora')
            ->where("deleted_at", NULL)
            ->where('fecha', '>=', $parametros['fecha_inicial'])
            ->where('fecha', '<=', $parametros['fecha_final'])
            ->orderBy("fecha", "ASC")
            ->orderBy("hora", "ASC"); 
            if($parametros['por_dia']=='SI'){
                $data = $data->groupBy('fecha');
            }        
            $data = $data->get();
            foreach ($data as $key => $value) {
                $data[$key] = [$value->fecha, $value->temperatura, $value->hora];
            }
            return view('temperatura.index')->with(['data' => $data]);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
     {
        if (Auth::user()->can('create.catalogos') && Auth::user()->activo==1) { 
            return view('temperatura.create');
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msgGeneral = '';
        $type       = 'flash_message_info';
        if (Auth::user()->can('create.catalogos') && Auth::user()->activo==1) { 
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'mimes'    => 'El :attribute debe ser de tipo .txt'
            ];

            $rules = [
                'archivo'                 => 'required|mimes:txt'
            ];
            
            $this->validate($request, $rules, $messages);
            $file = \File::get($request->archivo);
            $cabeceras = substr($file, 0, strrpos($file, "@HEADER ENDS"));

            dd(  str_replace("\r\n"," | ",substr($file, 0, strrpos($file, "@HEADER ENDS")))   );

            $lecturas = substr($file, strrpos($file, "@HEADER ENDS")+ 14, strlen($file));
            $limpiar_lectura =  str_replace("\t","",str_replace("\r\n","",$lecturas));
            $limpiar_lectura = str_replace("\n", "|", $limpiar_lectura);
            $array_lecturas = explode('"', $limpiar_lectura);
            $lectura_filtrada = [];
            foreach ($array_lecturas as $key => $value) {
                if($value!="" && $value!=null)
                    array_push($lectura_filtrada, str_replace('"','',str_replace(".","-", $value)));
            }
            
            $temperaturas = []; $i = 0; $array_temp = [];
            foreach ($lectura_filtrada as $key => $value) {
                if($i==3){
                    $i=0;
                    array_push($temperaturas, array('fecha' => $array_temp[0],'hora' => $array_temp[1],'temperatura' => str_replace("-",".",$array_temp[2]) ));
                    $array_temp = [];
                }
                $array_temp[$i] = $value; 
                $i++;

            }
            
            try {                
                $clue = Clue::find(4184);  
                foreach ($temperaturas as $key => $value) {
                    $value = (object) $value;
                    /** INCREMENT **/
                    $id = '';
                    $incremento = 0;
                    $increment = TemperaturaContenedor::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();
                    if(count($increment)>0){
                        $incremento = $increment[0]->incremento + 1; 
                    } else {                 
                        $incremento = 1;                             
                    }
                    $id = $clue->servidor.''.$incremento; 
                    /** END INCREMENT **/
                    $temperatura                     = new TemperaturaContenedor;
                    $temperatura->id                 = $id;
                    $temperatura->servidor_id        = $clue->servidor;
                    $temperatura->incremento         = $incremento;
                    $temperatura->contenedores_id    = 1;
                    $temperatura->fecha              = Carbon::parse($value->fecha)->format('Y-m-d');
                    $temperatura->hora               = $value->hora;
                    $temperatura->temperatura        = $value->temperatura;
                    $temperatura->observacion        = $cabeceras;
                    $temperatura->usuario_id         = Auth::user()->email;
                    $temperatura->created_at         = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
                    $temperatura->save();
                }           

                $msgGeneral = 'Hey! se guardaron los datos';
                $type       = 'flash_message_ok';

            } catch(\PDOException $e){
                $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados.'.$e->getMessage();
                $type       = 'flash_message_error';
            }

            Session::flash($type, $msgGeneral);
            return redirect()->back();
            
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
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
        dd('Come on!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd('Come on!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd('Come on!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        dd('Come on!');
    }
}
