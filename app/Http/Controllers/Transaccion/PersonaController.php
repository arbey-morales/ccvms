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
use App\Transaccion\Persona;
use App\Catalogo\Clue;
use App\Catalogo\Entidad;
use App\Catalogo\Pais;
use App\Catalogo\Ageb;
use App\Catalogo\Municipio;
use App\Catalogo\TipoParto;
use App\Catalogo\CodigoCenso;
use App\Catalogo\Institucion;
use App\Catalogo\Localidad;
use App\Catalogo\Vacuna;
use App\Catalogo\Esquema;
use App\Catalogo\VacunaEsquema;
use App\Catalogo\PersonaVacunaEsquema;

class PersonaController extends Controller
{
    public $tipo_aplicacion = array("X","Dosis única","1a Dosis","2a Dosis","3a Dosis","4a Dosis","Refuerzo");
    
    public $estados = array("X","AS","BC","BS","CC","CL","CM","CS","CH","DF","DG","GT","GR","HG","JC","MC","MN","MS","NT","NL","OC","PL","QT","QR","SP","SL","SR","TC","TS","TL","VZ","YN","ZS");
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = Input::only('q');
        $q = "";
        if($parametros['q'])
            $q = $parametros['q'];
		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            if (Auth::user()->is('root|admin')) {
                if($parametros['q']){
                    $personas = Persona::where('deleted_at', NULL)->where('curp','LIKE',"%".$parametros['q']."%")->orWhere(DB::raw("CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno)"),'LIKE',"%".$parametros['q']."%")->with('municipio','localidad','clue')->orderBy('id', 'DESC')->take(500)->get();
                } else {
                    $personas = Persona::where('deleted_at', NULL)->with('municipio','localidad','clue')->orderBy('id', 'DESC')->take(500)->get();
                }
            } else { // Limitar por clues
                 if($parametros['q']){
                    $personas = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->where('personas.curp','LIKE',"%".$parametros['q']."%")->orWhere(DB::raw("CONCAT(personas.nombre,' ',personas.apellido_paterno,' ',personas.apellido_materno)"),'LIKE',"%".$parametros['q']."%")->with('municipio','localidad','clue')->orderBy('personas.id', 'DESC')->take(500)->get();
                 } else {
                    $personas = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('municipio','localidad','clue')->orderBy('personas.id', 'DESC')->take(500)->get();
                 }
            }
            return view('persona.index')->with('data', $personas)->with('q', $q);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Display a index of reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function report()
    {
        if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {     
			if (Auth::user()->is('root|admin')) {
				$clues = Clue::where('deleted_at',NULL)->where('estatus_id', 1)->get();
			} else {
				$clues = Clue::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
			}

			$arrayclue[0] = 'Seleccionar Unidad de salud';
            foreach ($clues as $cont=>$clue) {
                $arrayclue[$clue->id] = $clue->clues .' - '.$clue->nombre;
            }

            return view('persona.reporte')->with(['clues' => $arrayclue]);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    public function curp(Request $request)
    {         
        $encontrada = false;
        $cur = "";
        $rfc = "";
        $estados=$this->estados;
		$ap=strtoupper($request->paterno);
		$am=strtoupper($request->materno);
		$na=strtoupper($request->nombre);

        $nacimiento = explode("-",$request->fecha_nacimiento);
		
		$d=$nacimiento[0];
		if($d<10) $d="0".(int)$d;
		$m=$nacimiento[1];
		if($m<10) $m="0".(int)$m;
		
		$y=$nacimiento[2];
		$se=$request->genero;
		$se=strtoupper($se);
		if($se=="M"||$se=="MASCULINO")
			$se="H";
		if($se=="F"||$se=="FEMENINO")
			$se="M";
		$edo=$estados[$request->entidad_federativa_nacimiento_id];
        
        if($ap!=""&&$am!=""&&$na!=""&&$d!=""&&$m!=""&&$y!=""&&$se!=""&&$edo!=""){
            /*******  CALCULA CURP  ********/
            $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://losimpuestos.com.mx/rfc/calcular-rfc.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,"paterno=$ap&materno=$am&nombre=$na&dia=$d&mes=$m&anno=$y&sexo=$se&entidad=$edo");
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)");
			$html = curl_exec($ch);
            if($html) {
			$infoinicio=substr($html,stripos($html,'<table>'));
			$info=substr($infoinicio,0,stripos($infoinicio,'</table>'));
                        $info = str_replace(' ', '', $info);
                        
                        $rfc = substr($info,  stripos($info, '<strong>RFC</strong>'));
                        $cur = substr($info,  stripos($info, '<strong>CURP</strong>'));
                        
                        $rfc = substr($rfc, 0,stripos($rfc,'</span></strong>'));
                        $cur = substr($cur, 0,stripos($cur,'</span></strong>'));
                        
                        $rfc = preg_replace('/[^ A-Za-z0-9_-ñÑ]/', '', $rfc);
                        $cur = preg_replace('/[^ A-Za-z0-9_-ñÑ]/', '', $cur);
                        
                        $replaces = array(
                            'strong' => '',
                            'td' => '',
                            'RFC'=>'',
                            'CURP'=>'',
                            'span' => '',
                            'style' => '',
                            'color'=>'',
                            'f00' => ''
                        );
                        
                        $rfc = str_replace(array_keys($replaces),array_values($replaces), $rfc);
                        $cur = str_replace(array_keys($replaces),array_values($replaces), $cur);
                        $encontrada = true;

            }

            curl_close($ch);
     
			/*if(strlen($cur)>10&&!stripos($cur,"<"))
			{
				if($regresar==1)
					return $array;
				else
					echo json_encode($array);
			}  */ 


            /*******  CONSULTAR CURP RENAPO  ********/
            /*
            $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://consultas.curp.gob.mx/CurpSP/datossxcurp.do?strPrimerApellido=$ap&strSegundoAplido=$am&strNombre=$na&strdia=$d&strmes=$m&stranio=$y&sSexoA=$se&sEntidadA=$edo&rdbBD=myoracle&strTipo=A&codigo=bf139");
			
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: JSESSIONID=XrQFT2YSf8BMmwnbJ7HyFlnfttYcjqp3dtJDjQ7HM2NRz84GGW12!-767651644"));
			
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)");
			$html = curl_exec($ch);
			curl_close($ch);
                        
                        echo "http://consultas.curp.gob.mx/CurpSP/datossxcurp.do?strPrimerApellido=$ap&strSegundoAplido=$am&strNombre=$na&strdia=$d&strmes=$m&stranio=$y&sSexoA=$se&sEntidadA=$edo&rdbBD=myoracle&strTipo=A";
                        echo $html;
			
			$pos=stripos($html,'<td class="TablaTitulo2"><span class="NotaBlanca">Curp</span></td>
	<td><b class="Nota">');
			$t=34;
			$html=substr($html,$pos,strlen($html)-$pos);
			
                        //echo "http://consultas.curp.gob.mx/CurpSP/datossxcurp.do?strPrimerApellido=$ap&strSegundoAplido=$am&strNombre=$na&strdia=$d&strmes=$m&stranio=$y&sSexoA=$se&sEntidadA=$edo&rdbBD=myoracle&strTipo=A";
                        //print($html);
                        
			$cu=substr($html,stripos($html,'>Curp')+($t+6),18);
			
			$ap=substr($html,stripos($html,'>Primer Apellido')+($t+17),38);
			$ap=substr($ap,0,stripos($ap,'<'));
	
			$am=substr($html,stripos($html,'>Segundo Apellido')+($t+20),38);
			$am=substr($am,0,stripos($am,'<'));
		
			$na=substr($html,stripos($html,'>Nombre(s)')+($t+11),18);
			$na=substr($na,0,stripos($na,'<'));
	
			$se=substr($html,stripos($html,'>Sexo')+($t+6),18);
			$se=substr($se,0,stripos($se,'<'));
	
			$fn=substr($html,stripos($html,'>Fecha de Nacimiento')+($t+33),12);

			$se=substr($html,stripos($html,'>Sexo')+($t+9),18);
			$se=substr($se,0,stripos($se,'<'));
	
			$nw=substr($html,stripos($html,'>Nacionalidad')+($t+16),18);
			$nw=substr($nw,0,stripos($nw,'<'));
	
			$ed=substr($html,stripos($html,'>Entidad de Nacimiento')+($t+25),28);
			$ed=substr($ed,0,stripos($ed,'<'));
	
			$dc=substr($html,stripos($html,'>Tipo Doc. Probatorio')+($t+25),28);
			$dc=substr($dc,0,stripos($dc,'<'));
	
			$if=substr($html,stripos($html,'<table'),strlen($html)-stripos($html,'</b></td>
		    </tr>
		    </table>')+40);
			$if=str_replace("\r","",$if);
			$if=str_replace("\n","",$if);
			$if=str_replace("\t","",$if);	
			
			$cp=substr($html,stripos($html,'>Historicas')+($t+15),18);
			$array=
			array(
				array(
					"curp"=>$cu,
					"paterno"=>$ap,
					"materno"=>$am,
					"nombre"=>$na,
					"nacimiento"=>$fn,
					"sexo"=>$se,
					"nacionalidad"=>$nw,
					"entidad"=>$ed,
					"documeto"=>$dc,
					"curpo"=>$cp,
					"informacion"=>utf8_encode(trim($if))					
				)
			);
			if(!stripos($cu,'Curp')&&!stripos($cu,'ink')&&!stripos($cu,"<"))
			{
				if($regresar==1)
					return $array;
				else
					echo json_encode($array);
			}

            dd($array); die;*/
        }
        return response()->json(['find' => $encontrada, 'curp'   => $cur, 'rfc' => $rfc]);
    }

	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
    {
        if (Auth::user()->can('create.personas') && Auth::user()->activo==1) {     
			if (Auth::user()->is('root|admin')) {
				$clues = Clue::where('deleted_at',NULL)->where('estatus_id', 1)->get();
				$municipios = Municipio::where('deleted_at',NULL)->get();
                $localidades = Localidad::where('deleted_at',NULL)->get();
                $agebs = Ageb::with('municipio','localidad')->get();
			} else {
                $localidades = collect();
                $agebs = collect();
				$clues = Clue::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
				$municipios = Municipio::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->get();
                foreach($municipios as $key=> $mpio){
                    $localidades_temp = Localidad::where('municipios_id', $mpio->id)->where('deleted_at',NULL)->get(); 
                    foreach($localidades_temp as $id=> $item){
                        $localidades->push($item);
                    }

                    $agebs_temp = Ageb::where('municipios_id', $mpio->id)->where('deleted_at',NULL)->with('municipio','localidad')->get(); 
                    foreach($agebs_temp as $k=> $i){
                        $agebs->push($i);
                    }
                }
			}
            
            $estados = Entidad::where('deleted_at',NULL)->get();
			$paises = Pais::all();
			$instituciones = Institucion::where('deleted_at',NULL)->get();
			$codigos = CodigoCenso::where('deleted_at',NULL)->get();
			$tiposparto = TipoParto::where('deleted_at',NULL)->get();

			$clue_selected = [];
            foreach ($clues as $cont=>$clue) {
                $arrayclue[$clue->id] = $clue->clues .' - '.$clue->nombre;
                if($cont==0)
                    $clue_selected = $clue;
            }
			
			foreach ($municipios as $municipio) {
                $arraymunicipio[$municipio->id] = $municipio->clave .' - '.$municipio->nombre;
            }

            $arrayageb[0] = 'Seleccionar AGEB';
            foreach ($agebs as $ageb) {
                $arrayageb[$ageb->id] = substr($ageb->id, -4).' - '.$ageb->localidad->nombre.', '.$ageb->municipio->nombre;
            }
            
			foreach ($estados as $estado) {
                $arrayestado[$estado->id] = $estado->clave .' - '.$estado->nombre;
            }
			
			foreach ($paises as $pais) {
                $arraypais[$pais->id] = $pais->claveA3 .' - '.$pais->descripcion;
            }
            
            foreach ($localidades as $localidad) {
                $arraylocalidad[$localidad->id] = $localidad->clave .' - '.$localidad->nombre;
            }			

			$arraycodigo = array();
			$arraycodigo[0] = 'Ningún código';
			foreach ($codigos as $codigo) {
                $arraycodigo[$codigo->id] = $codigo->clave .' - '.$codigo->nombre;
            }

			foreach ($tiposparto as $tipoparto) {
                $arraytipoparto[$tipoparto->id] = $tipoparto->clave .' - '.$tipoparto->descripcion;
            }

			$arrayinstitucion = array();
			$arrayinstitucion[0] = 'Ninguna afiliación';
			foreach ($instituciones as $institucion) {
                $arrayinstitucion[$institucion->id] = $institucion->clave .' - '.$institucion->nombre;
            }
            
            return view('persona.create')->with(['agebs' => $arrayageb, 'clue_selected' => $clue_selected, 'instituciones' => $arrayinstitucion, 'localidades' => $arraylocalidad, 'clues' => $arrayclue, 'municipios' => $arraymunicipio, 'estados' => $arrayestado, 'paises' => $arraypais, 'codigos' => $arraycodigo, 'partos' => $arraytipoparto, ]);
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
        $tipo_aplicacion=$this->tipo_aplicacion;

        if (Auth::user()->can('create.personas') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'same'     => 'El campo :attribute debe ser igual al password',
                'confirmed'=> 'El campo :attribute debe ser confirmado',
                'date'     => 'El campo :attribute debe ser formato fecha',
                'before'   => 'La :attribute debe ser menor o igual a la fecha limite(fecha actual o fecha de nacimiento del niño)'
            ];

            $rules = [
                'nombre'                                 => 'required|min:3|max:30|string',
                'paterno'                                => 'required|min:3|max:20|string',
                'materno'                                => 'required|min:3|max:20|string',
                'clue_id'                                => 'required|min:1|numeric',
                'fecha_nacimiento'                       => 'required|date|before:tomorrow',
                'fecha_nacimiento_tutor'                 => 'required|date|before:fecha_nacimiento',
                'curp'                                   => 'required|min:17|max:18',
                'genero'                                 => 'required|in:F,M',
                'tipo_parto_id'                          => 'required|min:1|numeric',
                'entidad_federativa_nacimiento_id'       => 'required|min:1|numeric',
                'municipio_id'                           => 'required|min:1|numeric',
                'localidad_id'                           => 'required|min:1|numeric',
                'calle'                                  => 'required|min:1|max:100',
                'numero'                                 => 'required|min:1|max:5',
                'tutor'                                  => 'required|min:10|max:100',
            ];
            
            $this->validate($request, $rules, $messages);

            $persona_id = '';
            $incremento = 0;
            $clue = Clue::find($request->clue_id);
            $persona_increment = Persona::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();

            if(count($persona_increment)>0){
                $incremento = $persona_increment[0]->incremento + 1; 
            } else {                 
                $incremento = 1;                             
            }
            $persona_id = $clue->servidor.''.$incremento; 
            
            if($request->institucion_id==0)
                $request->institucion_id = NULL;
            if($request->codigo_id==0)
                $request->codigo_id = NULL;
            if($request->ageb_id==0)
                $request->ageb_id = NULL;

            $fecha_nacimiento = explode('-',$request->fecha_nacimiento);
            $request->fecha_nacimiento = $fecha_nacimiento[2].'-'.$fecha_nacimiento[1].'-'.$fecha_nacimiento[0]; // formato valido para guardar fecha n

            $fecha_nacimiento_tutor = explode('-',$request->fecha_nacimiento_tutor);
            $request->fecha_nacimiento_tutor = $fecha_nacimiento_tutor[2].'-'.$fecha_nacimiento_tutor[1].'-'.$fecha_nacimiento_tutor[0]; // formato valido para guardar fecha n t
                   
            $persona = new Persona;
            $persona->id                    = $persona_id;
            $persona->servidor_id           = $clue->servidor;
            $persona->incremento            = $incremento;
            $persona->nombre                = strtoupper($request->nombre);
            $persona->apellido_paterno      = strtoupper($request->paterno);
            $persona->apellido_materno      = strtoupper($request->materno);
            $persona->clues_id              = $request->clue_id;
            $persona->fecha_nacimiento      = $request->fecha_nacimiento;
            $persona->curp                  = strtoupper($request->curp);
            $persona->genero                = $request->genero;
            $persona->tipos_partos_id       = $request->tipo_parto_id;
            $persona->entidades_federativas_nacimiento_id = $request->entidad_federativa_nacimiento_id;
            $persona->entidades_federativas_domicilio_id = $request->entidad_federativa_nacimiento_id;
            $persona->municipios_id         = $request->municipio_id;
            $persona->localidades_id        = $request->localidad_id;
            $persona->agebs_id              = $request->ageb_id;
            $persona->colonia               = $request->colonia;
            $persona->paises_id             = 155;
            $persona->descripcion_domicilio = $request->descripcion_domicilio;
            $persona->calle                 = $request->calle;
            $persona->numero                = $request->numero;
            $persona->manzana               = $request->manzana;
            $persona->codigo_postal         = $request->codigo_postal;
            $persona->sector                = $request->sector;
            $persona->codigos_censos_id     = $request->codigo_id;
            $persona->instituciones_id      = $request->institucion_id;
            $persona->tutor                 = strtoupper($request->tutor);
            $persona->fecha_nacimiento_tutor = $request->fecha_nacimiento_tutor;
            $persona->usuario_id            = Auth::user()->email;
            $persona->created_at            = date('Y-m-d H:m:s');

            //$vacunas_esquemas = VacunaEsquema::where('esquemas_id', $fecha_nacimiento[2])->with('vacuna')->get();
            /*$vacunas_esquemas = DB::table('vacunas_esquemas AS ve')
                        ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                        ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                        ->where('ve.esquemas_id', $fecha_nacimiento[2])
                        ->orderBy('v_orden_esquema')
                        ->orderBy('intervalo_inicio')
                        ->orderBy('fila')
                        ->orderBy('columna')
                        ->get();*/
                        
            $ahora = Carbon::now("America/Mexico_City");
            $dia_nacimiento = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City");
            $intervalo_dias = $dia_nacimiento->diffInDays($ahora);
            $dia_nacimiento = $dia_nacimiento->toDateString();
            $ahora = $ahora->toDateString();

            $vacunas_esquemas = DB::table('vacunas_esquemas AS ve')
                ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', $fecha_nacimiento[2])
                ->where('ve.intervalo_inicio','<',($intervalo_dias+1))
                ->where('ve.deleted_at', NULL)
                ->where('v.deleted_at', NULL)                
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio')
                ->orderBy('fila')
                ->orderBy('columna')
                ->get(); 
            foreach ($vacunas_esquemas as $key => $value) {
                $value->int_inicio_normal = $value->intervalo_inicio;
                $value->int_fin_normal = $value->intervalo_fin;
                $value->mayores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)->where('esquemas_id', $fecha_nacimiento[2])->where('intervalo_inicio', '>=', $value->intervalo_inicio)->where('id', '!=', $value->id)->where('deleted_at', NULL)->orderBy('intervalo_inicio', 'ASC')->take(1)->get();
                $value->menores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)->where('esquemas_id', $fecha_nacimiento[2])->where('intervalo_inicio', '<=', $value->intervalo_inicio)->where('id', '!=', $value->id)->where('deleted_at', NULL)->orderBy('intervalo_inicio', 'DESC')->take(1)->get();
            }

            $save_vac_esq = true;            
            $msg_dosis = '';
            $esquema_dosis_validada = array(); // dosis ya validada por una dosis al menos
            foreach($vacunas_esquemas as $key=>$ve){                     
                if($request['fecha_aplicacion'.$ve->id]!=NULL && $request['fecha_aplicacion'.$ve->id]!="" && $request['fecha_aplicacion'.$ve->id]!="__-__-____"){ // Si trae algún valor la variable
                    $fecha_apli = explode('-',$request['fecha_aplicacion'.$ve->id]);
                    if(array_key_exists(0, $fecha_apli) && array_key_exists(1, $fecha_apli) && array_key_exists(2, $fecha_apli)){ // Si cumple con día, mes y año
                        $temp_fecha_aplicacion = $fecha_apli[2].'-'.$fecha_apli[1].'-'.$fecha_apli[0];
                        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$temp_fecha_aplicacion)) { // valida formato de fecha
                                                                             
                            $today  = explode('-', $ahora);
                            $mktime_today = mktime(0,0,0,$today[1],$today[2],$today[0]);

                            $born  = explode('-', $dia_nacimiento);
                            $mktime_born = mktime(0,0,0,$born[1],$born[2],$born[0]);
                            
                            $apli  = explode('-', $temp_fecha_aplicacion);
                            $mktime_apli = mktime(0,0,0,$apli[1],$apli[2],$apli[0]);
                            if($mktime_apli>=$mktime_born && $mktime_apli<=$mktime_today) { // Si la fecha de aplicación >= fecha de nacimento y <= la fecha de hoy
                                // validar que la aplicación actual no salte aplicaciones anteriores de cada vacuna...
                                $msg_dosis_faltantes = '';
                                $falta_dosis = false;
                                if(count($ve->menores)>0){ // DOSIS MENOR A LA EVALUADA
                                    $value_menores = $ve->menores[0];
                                    $indice = 0;
                                    foreach ($vacunas_esquemas as $key_men => $value_men) {
                                        if ($vacunas_esquemas[$key]->menores[0]->id==$value_men->id) {
                                            $indice = $key_men;
                                            break;
                                        }
                                    }

                                    $intervalo_inicio = '';
                                    if($value_menores->intervalo_inicio<=29) { 
                                        $intervalo_inicio = 'Nac'; 
                                    } else {
                                        if(($value_menores->intervalo_inicio/30)<=23){
                                            $intervalo_inicio = ($value_menores->intervalo_inicio/30).'M';
                                        } else {
                                            $intervalo_inicio = round((($value_menores->intervalo_inicio/30)/12)).'A';
                                        }
                                    }
                                    if($request['fecha_aplicacion'.$value_menores->id]==NULL && $request['fecha_aplicacion'.$value_menores->id]=="" && $request['fecha_aplicacion'.$value_menores->id]=="__-__-____") {
                                        $save_vac_esq = false;  
                                        $falta_dosis = true;
                                        $msg_dosis_faltantes.= $tipo_aplicacion[$value_menores->tipo_aplicacion].' de '.$ve->clave.' ('.$intervalo_inicio.') | ';
                                    } else {
                                        // es decir que la fecha si tiene valor, hay que evaluar validez de formato y rango establecido por el esquema

                                        $fecha_apli_menores = explode('-',$request['fecha_aplicacion'.$value_menores->id]);
                                        if(array_key_exists(0, $fecha_apli_menores) && array_key_exists(1, $fecha_apli_menores) && array_key_exists(2, $fecha_apli_menores)){ // Si cumple con día, mes y año
                                            $temp_fecha_aplicacion_menores = $fecha_apli_menores[2].'-'.$fecha_apli_menores[1].'-'.$fecha_apli_menores[0];
                                            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$temp_fecha_aplicacion_menores)) { // valida formato de fecha
                                                $apli_menores  = explode('-', $temp_fecha_aplicacion_menores);
                                                $mktime_apli_menores = mktime(0,0,0,$apli_menores[1],$apli_menores[2],$apli_menores[0]);
                                                if($mktime_apli<=$mktime_apli_menores) {
                                                    $save_vac_esq = false;  
                                                    $falta_dosis = true;
                                                    $msg_dosis_faltantes.= 'Fecha de '.$tipo_aplicacion[$value_menores->tipo_aplicacion].' de '.$ve->clave.' debe ser menor a la fecha de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' | ';
                                                }

                                                $dias_diferencia_intervalo_inicio = $ve->intervalo_inicio - $vacunas_esquemas[$indice]->intervalo_inicio;
                                                $dias_diferencia = ($mktime_apli - $mktime_apli_menores) / (60 * 60 * 24);
                                                //dd($dias_diferencia,$dias_diferencia_intervalo_inicio); die;
                                                if($dias_diferencia<$dias_diferencia_intervalo_inicio) { // si hay un itervalo valido entre las dos fechas
                                                    $save_vac_esq = false;  
                                                    $falta_dosis = true;
                                                    $msg_dosis_faltantes.= $ve->clave. ' debe tener al menos '.$dias_diferencia_intervalo_inicio.' días de diferencia entre la  '.$tipo_aplicacion[$value_menores->tipo_aplicacion].' y la '.$tipo_aplicacion[$ve->tipo_aplicacion].' | ';
                                                }
                                            }
                                        }

                                    }

                                    if($falta_dosis)
                                        $msg_dosis.=$msg_dosis_faltantes;
                                }

                                if(count($ve->mayores)>0){ // DOSIS MENOR A LA EVALUADA
                                    $value_mayores = $ve->mayores[0];
                                    $indice = 0;
                                    foreach ($vacunas_esquemas as $key_may => $value_may) {
                                        if ($vacunas_esquemas[$key]->mayores[0]->id==$value_may->id) {
                                            $indice = $key_may;
                                            break;
                                        }
                                    }
                                    
                                    $intervalo_inicio = '';
                                    if($value_mayores->intervalo_inicio<=29) { 
                                        $intervalo_inicio = 'Nac'; 
                                    } else {
                                        if(($value_mayores->intervalo_inicio/30)<=23){
                                            $intervalo_inicio = ($value_mayores->intervalo_inicio/30).'M';
                                        } else {
                                            $intervalo_inicio = round((($value_mayores->intervalo_inicio/30)/12)).'A';
                                        }
                                    }
                                    if($request['fecha_aplicacion'.$value_mayores->id]==NULL && $request['fecha_aplicacion'.$value_mayores->id]=="" && $request['fecha_aplicacion'.$value_mayores->id]=="__-__-____") {
                                        $save_vac_esq = false;  
                                        $falta_dosis = true;
                                        $msg_dosis_faltantes.= $tipo_aplicacion[$value_mayores->tipo_aplicacion].' de '.$ve->clave.' ('.$intervalo_inicio.') | ';
                                    } else {
                                        // es decir que la fecha si tiene valor, hay que evaluar validez de formato y rango establecido por el esquema

                                        $fecha_apli_mayores = explode('-',$request['fecha_aplicacion'.$value_mayores->id]);
                                        if(array_key_exists(0, $fecha_apli_mayores) && array_key_exists(1, $fecha_apli_mayores) && array_key_exists(2, $fecha_apli_mayores)){ // Si cumple con día, mes y año
                                            $temp_fecha_aplicacion_mayores = $fecha_apli_mayores[2].'-'.$fecha_apli_mayores[1].'-'.$fecha_apli_mayores[0];
                                            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$temp_fecha_aplicacion_mayores)) { // valida formato de fecha
                                                $apli_mayores  = explode('-', $temp_fecha_aplicacion_mayores);
                                                $mktime_apli_mayores = mktime(0,0,0,$apli_mayores[1],$apli_mayores[2],$apli_mayores[0]);
                                                
                                                if($mktime_apli>=$mktime_apli_mayores) {
                                                    $save_vac_esq = false;  
                                                    $falta_dosis = true;
                                                    $msg_dosis_faltantes.= 'Fecha de '.$tipo_aplicacion[$value_mayores->tipo_aplicacion].' de '.$ve->clave.' debe ser mayor a la fecha de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' | ';
                                                }
                                                
                                                $dias_diferencia_intervalo_inicio = $value_mayores->intervalo_inicio - $ve->intervalo_inicio;
                                                $dias_diferencia = ($mktime_apli_mayores - $mktime_apli) / (60 * 60 * 24); // dias diferencia entre las dos fecha
                                                
                                                /*if($dias_diferencia>$dias_diferencia_intervalo_inicio) { // si hay un itervalo valido entre las dos fechas
                                                    $save_vac_esq = false;  
                                                    $falta_dosis = true;
                                                    $msg_dosis_faltantes.= $ve->clave. ' VRE QUE VAALIDAR '.$dias_diferencia_intervalo_inicio.' días de diferencia entre la  '.$tipo_aplicacion[$value_mayores->tipo_aplicacion].' y la '.$tipo_aplicacion[$ve->tipo_aplicacion].' | ';
                                                }*/

                                                if ($ve->maximo_ideal!=NULL && $ve->dias_agregar_siguiente_dosis!=NULL) {
                                                    $dias_diferencia = ($mktime_apli - $mktime_born) / (60 * 60 * 24); // dias diferencia entre las dos fecha
                                                    if($dias_diferencia>$ve->maximo_ideal) {
                                                        $vacunas_esquemas[$indice]->intervalo_inicio = ($vacunas_esquemas[$indice]->int_inicio_normal + $vacunas_esquemas[$key]->dias_agregar_siguiente_dosis);
                                                       // dd($tipo_aplicacion[$value_mayores->tipo_aplicacion], 'cambió', $vacunas_esquemas[$indice]->intervalo_inicio); die;
                                                    } else {
                                                        $vacunas_esquemas[$indice]->intervalo_inicio = $vacunas_esquemas[$indice]->int_inicio_normal;
                                                        //dd($tipo_aplicacion[$value_mayores->tipo_aplicacion], 'regresó',$vacunas_esquemas[$indice]->intervalo_inicio); die;
                                                    }
                                                }

                                            }
                                        }

                                    }

                                    if($falta_dosis)
                                        $msg_dosis.=$msg_dosis_faltantes;
                                }


                            } else {
                                $msg_dosis.='Fecha de aplicación de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' de '.$ve->clave.' debe ser mayor o igual a la fecha de nacimiento y menor igual a la fecha actual o de la última actualización';
                                $save_vac_esq = false; 
                                break;
                            }
                        } else { 
                            $msg_dosis.='Formato de fecha de aplicación no valida';
                            $save_vac_esq = false; 
                            break; 
                        }
                    } else {
                        $msg_dosis.='Formato de fecha de aplicación no valida. ';
                        $save_vac_esq = false; 
                        break;
                    }
                }
            }

            //die;

            $repeat_curp = Persona::where('curp', $request->curp)->where('deleted_at', NULL)->get();
            if($save_vac_esq==true) {
                if(count($repeat_curp)<=0) {
                    try {       
                        DB::beginTransaction();             
                        if($persona->save()) {
                            $success = true;
                            foreach($vacunas_esquemas as $key=>$ve){
                                if($request['fecha_aplicacion'.$ve->id]!=NULL && $request['fecha_aplicacion'.$ve->id]!=""){
                                    $pve_id = '';
                                    $incremento_pve = 0;
                                    $pve_increment = PersonaVacunaEsquema::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();

                                    if(count($pve_increment)>0){
                                        $incremento_pve = $pve_increment[0]->incremento + 1; 
                                    } else {                 
                                        $incremento_pve = 1;                             
                                    }
                                    $pve_id = $clue->servidor.''.$incremento_pve; 

                                    $fecha_apli = explode('-',$request['fecha_aplicacion'.$ve->id]);
                                    $temp_fecha_aplicacion = $fecha_apli[2].'-'.$fecha_apli[1].'-'.$fecha_apli[0];
                                    $PersonaVacunaEsquema = new PersonaVacunaEsquema;
                                    $PersonaVacunaEsquema->id                   = $pve_id;
                                    $PersonaVacunaEsquema->servidor_id          = $clue->servidor;
                                    $PersonaVacunaEsquema->incremento           = $incremento_pve;
                                    $PersonaVacunaEsquema->personas_id           = $persona_id;
                                    $PersonaVacunaEsquema->vacunas_esquemas_id    = $ve->id;
                                    $PersonaVacunaEsquema->fecha_aplicacion     = $temp_fecha_aplicacion;
                                    $PersonaVacunaEsquema->lote                 = '00000';
                                    $PersonaVacunaEsquema->dosis                = $ve->dosis_requerida;
                                    $PersonaVacunaEsquema->usuario_id           = Auth::user()->email;
                                    $PersonaVacunaEsquema->created_at           = date('Y-m-d H:m:s');
                                    if(!$PersonaVacunaEsquema->save()){
                                        $success = false;
                                        break;
                                    }                                                                           
                                }
                            }

                            if($success){
                                DB::commit();
                                $msgGeneral = 'Perfecto! se gurdaron los datos';
                                $type       = 'flash_message_ok';
                                Session::flash($type, $msgGeneral);
                                if ($request->ajax()) {
                                    return response()->json(['estatus' => 'success', 'titulo' => 'Perfecto!', 'texto' => 'Se gurdaron los datos']);
                                }
                                return redirect()->back();
                            } else {
                                DB::rollback();
                                $msgGeneral = 'No se guardaron los datos. Verifique su información v o recargue la página.';
                                $type       = 'flash_message_error';
                            }
                        } else {
                            DB::rollback();
                            $msgGeneral = 'No se guardaron los datos personales. Verifique su información o recargue la página.';
                            $type       = 'flash_message_error';                            
                        }
                    } catch(\PDOException $e){
                        $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados. Recargue la página e intente de nuevo';
                        $type       = 'flash_message_error';
                    }   
                } else {
                    $msgGeneral = 'La CURP está ya está registrada, verifique los datos.';
                    $type       = 'flash_message_error';
                }  
            } else {
                $msgGeneral = $msg_dosis;
                $type       = 'flash_message_error';
            }        
            
            Session::flash($type, $msgGeneral);            
            if ($request->ajax()) {
                return response()->json(['estatus' => 'error', 'titulo' => 'Error', 'texto' => $msgGeneral]);
            }
            return redirect()->back()->withInput();

        } else {
            if ($request->ajax()) {
                return response()->json(['estatus' => 'error', 'titulo' => 'Error de permisos', 'texto' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.']);
            }
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
        if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            $person = Persona::findOrFail($id);
            if ($person) {
                if (Auth::user()->is('root|admin')) {
                    $persona = Persona::where('id', $id)->where('deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                } else { // Limitar por clues
                    $persona = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('personas.id', $id)->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                }

                $esquema_date = explode('-', $persona->fecha_nacimiento);
                $esquema = Esquema::find($esquema_date[0]);
                
                $letra_edad = NULL; 
                $letra_years = 'Años'; $letra_months = 'Meses'; $letra_days = 'Días';

                $ahora = Carbon::now("America/Mexico_City");
                $fecha_nacimiento = Carbon::parse($esquema_date[2]."-".$esquema_date[1]."-".$esquema_date[0]." 00:00:00","America/Mexico_City");
                $intervalo_dias = $fecha_nacimiento->diffInDays($ahora);
                $diff = abs(strtotime(date("Y-m-d")) - strtotime($persona->fecha_nacimiento));
                $years = floor($diff / (365*60*60*24)); 
                if($years==1)
                    $letra_years = 'Año';           
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                if($months==1)
                    $letra_months = 'Mes';
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 
                if($days==1)
                    $letra_days = 'Día';           
                $persona->edad = $years.' '.$letra_years.' '.$months.' '.$letra_months.' '.$days.' '.$letra_days;

                $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                    ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                    ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                    ->where('ve.esquemas_id', $esquema_date[0])
                    ->where('ve.intervalo_inicio','<',($intervalo_dias+1))
                    ->orderBy('v_orden_esquema')
                    ->orderBy('intervalo_inicio')
                    ->orderBy('fila')
                    ->orderBy('columna')
                    ->get(); 




                $fn_tutor = explode("-",$persona->fecha_nacimiento_tutor);
                $persona->fecha_nacimiento_tutor = date($fn_tutor[2].'-'.$fn_tutor[1].'-'.$fn_tutor[0]);
                $fn_nino = explode("-",$persona->fecha_nacimiento);
                $persona->fecha_nacimiento = date($fn_nino[2].'-'.$fn_nino[1].'-'.$fn_nino[0]);
            } else {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
            return view('persona.show')->with(['esquema' => $esquema, 'data' => $persona, 'vacunas_esquemas' => $esquema_detalle]);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            $person = Persona::findOrFail($id);
            $id_persona = $id;
            if($person) {
                if (Auth::user()->is('root|admin')) {
                    $clues = Clue::where('deleted_at',NULL)->where('estatus_id', 1)->get();
                    $municipios = Municipio::where('deleted_at',NULL)->get();
                    $localidades = Localidad::where('deleted_at',NULL)->get();
                    $agebs = Ageb::with('municipio','localidad')->get();
                    $persona = Persona::where('id', $id_persona)->where('deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                } else {
                    $localidades = collect();
                    $agebs = collect();
                    $clues = Clue::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
                    $municipios = Municipio::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->get();
                    foreach($municipios as $key=> $mpio){
                        $localidades_temp = Localidad::where('municipios_id', $mpio->id)->where('deleted_at',NULL)->get(); 
                        foreach($localidades_temp as $id=> $item){
                            $localidades->push($item);
                        }

                        $agebs_temp = Ageb::where('municipios_id', $mpio->id)->where('deleted_at',NULL)->with('municipio','localidad')->get(); 
                        foreach($agebs_temp as $k=> $i){
                            $agebs->push($i);
                        }
                    }
                    $persona = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('personas.id', $id_persona)->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                }
                
                //$vacunas_esquemas = VacunaEsquema::select('vacunas_esquemas.*')->join('vacunas','vacunas.id','=','vacunas_esquemas.vacuna_id')->orderBy('vacunas_esquemas.intervalo_inicio', 'ASC')->get();
                $fecha_actual = explode('-', $persona->fecha_nacimiento);
                $esquema = Esquema::find($fecha_actual[0]);
                //$vacunas_esquemas = VacunaEsquema::where('esquemas_id', $fecha_actual[0])->with('vacuna','esquema')->orderBy('intervalo_inicio', 'ASC')->orderBy('orden_esquema', 'ASC')->get();
                $vacunas_esquemas = DB::table('vacunas_esquemas AS ve')
                        ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                        ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                        ->where('ve.esquemas_id', $fecha_actual[0])
                        ->orderBy('v_orden_esquema')
                        ->orderBy('intervalo_inicio')
                        ->orderBy('fila')
                        ->orderBy('columna')
                        ->get();

                $estados = Entidad::where('deleted_at',NULL)->get();
                $paises = Pais::all();
                $instituciones = Institucion::where('deleted_at',NULL)->get();
                $codigos = CodigoCenso::where('deleted_at',NULL)->get();
                $tiposparto = TipoParto::where('deleted_at',NULL)->get();

                $clue_selected = [];
                foreach ($clues as $cont=>$clue) {
                    $arrayclue[$clue->id] = $clue->clues .' - '.$clue->nombre;
                    if($cont==0)
                        $clue_selected = $clue;
                }
                
                foreach ($municipios as $municipio) {
                    $arraymunicipio[$municipio->id] = $municipio->clave .' - '.$municipio->nombre;
                }

                $arrayageb[0] = 'Seleccionar AGEB';
                foreach ($agebs as $ageb) {
                    $arrayageb[$ageb->id] = substr($ageb->id, -4).' - '.$ageb->localidad->nombre.', '.$ageb->municipio->nombre;
                }
                
                foreach ($estados as $estado) {
                    $arrayestado[$estado->id] = $estado->clave .' - '.$estado->nombre;
                }
                
                foreach ($paises as $pais) {
                    $arraypais[$pais->id] = $pais->claveA3 .' - '.$pais->descripcion;
                }
                
                foreach ($localidades as $localidad) {
                    $arraylocalidad[$localidad->id] = $localidad->clave .' - '.$localidad->nombre;
                }			

                $arraycodigo = array();
                $arraycodigo[0] = 'Ningún código';
                foreach ($codigos as $codigo) {
                    $arraycodigo[$codigo->id] = $codigo->clave .' - '.$codigo->nombre;
                }

                foreach ($tiposparto as $tipoparto) {
                    $arraytipoparto[$tipoparto->id] = $tipoparto->clave .' - '.$tipoparto->descripcion;
                }

                $arrayinstitucion = array();
                $arrayinstitucion[0] = 'Ninguna afiliación';
                foreach ($instituciones as $institucion) {
                    $arrayinstitucion[$institucion->id] = $institucion->clave .' - '.$institucion->nombre;
                }

                $fn_tutor = explode("-",$persona->fecha_nacimiento_tutor);
                $persona->fecha_nacimiento_tutor = date($fn_tutor[2].'-'.$fn_tutor[1].'-'.$fn_tutor[0]);
                $fn_nino = explode("-",$persona->fecha_nacimiento);
                $persona->fecha_nacimiento = date($fn_nino[2].'-'.$fn_nino[1].'-'.$fn_nino[0]);
                return view('persona.edit')->with(['esquema' => $esquema, 'data' => $persona, 'agebs' => $arrayageb, 'vacunas_esquemas' => $vacunas_esquemas, 'clue_selected' => $clue_selected, 'instituciones' => $arrayinstitucion, 'localidades' => $arraylocalidad, 'clues' => $arrayclue, 'municipios' => $arraymunicipio, 'estados' => $arrayestado, 'paises' => $arraypais, 'codigos' => $arraycodigo, 'partos' => $arraytipoparto, ]);
            } else {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
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
        $msgGeneral = '';
        $type       = 'flash_message_info';
        $tipo_aplicacion=$this->tipo_aplicacion;

        $persona = Persona::findOrFail($id);
        $persona_id = $id;

        if (Auth::user()->can('update.personas') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'same'     => 'El campo :attribute debe ser igual al password',
                'confirmed'=> 'La :attribute debe ser confirmada',
                'date'     => 'La :attribute debe ser formato fecha',
                'before'   => 'La :attribute debe ser menor o igual a la fecha limite(fecha actual o fecha de nacimiento del niño)'
            ];

            $rules = [    
                'clue_id'                 => 'required|min:1|numeric',
                'tipo_parto_id'           => 'required|min:1|numeric',
                'municipio_id'            => 'required|min:1|numeric',
                'localidad_id'            => 'required|min:1|numeric',
                'calle'                   => 'required|min:1|max:100',
                'numero'                  => 'required|min:1|max:5',
                'tutor'                   => 'required|min:10|max:100',
                'fecha_nacimiento_tutor'  => 'required|date|before:fecha_nacimiento',
            ];
            
            $this->validate($request, $rules, $messages);
            $clue = Clue::find($request->clue_id);
            
            if($request->institucion_id==0)
                $request->institucion_id = NULL;
            if($request->codigo_id==0)
                $request->codigo_id = NULL;
            if($request->ageb_id==0)
                $request->ageb_id = NULL;

            $fecha_nacimiento_tutor = explode('-',$request->fecha_nacimiento_tutor);
            $request->fecha_nacimiento_tutor = $fecha_nacimiento_tutor[2].'-'.$fecha_nacimiento_tutor[1].'-'.$fecha_nacimiento_tutor[0]; // formato valido para guardar fecha n t
                        
            $persona->clues_id              = $request->clue_id;
            $persona->tipos_partos_id       = $request->tipo_parto_id;
            $persona->municipios_id         = $request->municipio_id;
            $persona->localidades_id        = $request->localidad_id;
            $persona->agebs_id              = $request->ageb_id;
            $persona->colonia               = $request->colonia;
            $persona->paises_id             = 155;
            $persona->descripcion_domicilio = $request->descripcion_domicilio;
            $persona->calle                 = $request->calle;
            $persona->numero                = $request->numero;
            $persona->manzana               = $request->manzana;
            $persona->codigo_postal         = $request->codigo_postal;
            $persona->sector                = $request->sector;
            $persona->codigos_censos_id     = $request->codigo_id;
            $persona->instituciones_id      = $request->institucion_id;
            $persona->tutor                 = strtoupper($request->tutor);
            $persona->fecha_nacimiento_tutor= $request->fecha_nacimiento_tutor;
            $persona->usuario_id            = Auth::user()->email;
            $persona->updated_at            = date('Y-m-d H:m:s');

            $fecha_nacimiento = explode('-',$persona->fecha_nacimiento);
            //$vacunas_esquemas = VacunaEsquema::where('esquemas_id', $fecha_nacimiento[0])->get();
            $vacunas_esquemas = DB::table('vacunas_esquemas AS ve')
                        ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                        ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                        ->where('ve.esquemas_id', $fecha_nacimiento[0])
                        ->orderBy('v_orden_esquema')
                        ->orderBy('intervalo_inicio')
                        ->orderBy('fila')
                        ->orderBy('columna')
                        ->get();
            $save_vac_esq = true;
            $msg_dosis = '';
            $esquema_dosis_validada = array(); // dosis ya validada por una dosis al menos

            foreach($vacunas_esquemas as $key=>$ve){
                if($request['fecha_aplicacion'.$ve->id]!=NULL && $request['fecha_aplicacion'.$ve->id]!=""){
                    $fecha_apli = explode('-',$request['fecha_aplicacion'.$ve->id]);
                    if(array_key_exists(0, $fecha_apli) && array_key_exists(1, $fecha_apli) && array_key_exists(2, $fecha_apli)){
                        $temp_fecha_aplicacion = $fecha_apli[2].'-'.$fecha_apli[1].'-'.$fecha_apli[0];
                        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$temp_fecha_aplicacion)) { 
                            $dosis_anteriores = VacunaEsquema::where('vacunas_id', $ve->vacunas_id)->where('esquemas_id', $fecha_nacimiento[0])->where('intervalo_inicio','<',$ve->intervalo_inicio)->where('deleted_at', NULL)->orderBy('intervalo_inicio', 'DESC')->take(1)->get();
                            $today  = explode('-', date('Y-m-d'));
                            $mktime_today = mktime(0,0,0,$today[1],$today[2],$today[0]);
                            $born  = explode('-', $persona->fecha_nacimiento);
                            $mktime_born = mktime(0,0,0,$born[1],$born[2],$born[0]);
                            $apli  = explode('-', $temp_fecha_aplicacion);
                            $mktime_apli = mktime(0,0,0,$apli[1],$apli[2],$apli[0]);

                            if($mktime_apli>=$mktime_born && $mktime_apli<=$mktime_today) { // Si la fecha de aplicación >= fecha de nacimento y <= la fecha de hoy
                                // validar que la aplicación actual no salte aplicaciones anteriores de cada vacuna...
                                //$dosis_anteriores = VacunaEsquema::where('vacunas_id', $ve->vacuna_id)->where('esquemas_id', $fecha_nacimiento[0])->where('intervalo_inicio','<',$ve->intervalo_inicio)->where('deleted_at', NULL)->get();
                                $msg_dosis_faltantes = '';
                                $falta_dosis = false;
                                foreach($dosis_anteriores as $index_menores=>$value_menores){
                                    $intervalo_inicio = '';
                                    if($value_menores->intervalo_inicio<=29) { 
                                        $intervalo_inicio = 'Nacimiento'; 
                                    } else {
                                        if(($value_menores->intervalo_inicio/30)<=23){
                                            $intervalo_inicio = ($value_menores->intervalo_inicio/30).' Meses';
                                        } else {
                                            $intervalo_inicio = round((($value_menores->intervalo_inicio/30)/12)).' Años';
                                        }
                                    }
                                    if($request['fecha_aplicacion'.$value_menores->id]==NULL && $request['fecha_aplicacion'.$value_menores->id]=="") {
                                        $save_vac_esq = false;  
                                        $falta_dosis = true;
                                        if(in_array($value_menores->id, $esquema_dosis_validada)) { } else {
                                            $msg_dosis_faltantes.= $tipo_aplicacion[$value_menores->tipo_aplicacion].' de '.$ve->clave.' ('.$intervalo_inicio.') | ';
                                        }
                                    } else {
                                        // es decir que la fecha si tiene valor, hay que evaluar validez de formato y rango establecido por el esquema

                                        $fecha_apli_menores = explode('-',$request['fecha_aplicacion'.$value_menores->id]);
                                        if(array_key_exists(0, $fecha_apli_menores) && array_key_exists(1, $fecha_apli_menores) && array_key_exists(2, $fecha_apli_menores)){ // Si cumple con día, mes y año
                                            $temp_fecha_aplicacion_menores = $fecha_apli_menores[2].'-'.$fecha_apli_menores[1].'-'.$fecha_apli_menores[0];
                                            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$temp_fecha_aplicacion_menores)) { // valida formato de fecha
                                                $apli_menores  = explode('-', $temp_fecha_aplicacion_menores);
                                                $mktime_apli_menores = mktime(0,0,0,$apli_menores[1],$apli_menores[2],$apli_menores[0]);
                                                if($mktime_apli<=$mktime_apli_menores) {
                                                    $save_vac_esq = false;  
                                                    $falta_dosis = true;
                                                    if(in_array($value_menores->id, $esquema_dosis_validada)) { } else {
                                                        $msg_dosis_faltantes.= 'Fecha de '.$tipo_aplicacion[$value_menores->tipo_aplicacion].' de '.$ve->clave.' debe ser menor a la fecha de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' | ';
                                                    }
                                                }

                                                $dias_diferencia_intervalo_inicio = $ve->intervalo_inicio - $value_menores->intervalo_inicio;
                                                $dias_diferencia = ($mktime_apli - $mktime_apli_menores) / (60 * 60 * 24);
                                                if($dias_diferencia<$dias_diferencia_intervalo_inicio) { // si hay un itervalo valido entre las dos fechas
                                                    $save_vac_esq = false;  
                                                    $falta_dosis = true;
                                                    if(in_array($value_menores->id, $esquema_dosis_validada)) { } else {
                                                        $msg_dosis_faltantes.= $ve->clave. ' debe tener al menos '.$dias_diferencia_intervalo_inicio.' días de diferencia entre la  '.$tipo_aplicacion[$value_menores->tipo_aplicacion].' y la '.$tipo_aplicacion[$ve->tipo_aplicacion].' | ';
                                                    }
                                                }
                                            }
                                        }

                                    }

                                    if(in_array($value_menores->id, $esquema_dosis_validada)) { } else {
                                        array_push($esquema_dosis_validada, $value_menores->id);
                                    }
                                }
                                if($falta_dosis)
                                    $msg_dosis.=$msg_dosis_faltantes;
                            } else {
                                $msg_dosis.='Fecha de aplicación de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' de '.$ve->vacuna->clave.' debe ser mayor o igual a la fecha de nacimiento y menor igual a la fecha actual o de la última actualización';
                                $save_vac_esq = false; 
                                break;
                            }
                        } else { 
                            $msg_dosis.='Formato de fecha de aplicación no valida';
                            $save_vac_esq = false; 
                            break; 
                        }
                    } else { 
                        $msg_dosis.='Formato de fecha de aplicación no valida';
                        $save_vac_esq = false; 
                        break;
                    }
                }
            }

            $repeat_curp = Persona::where('curp', $request->curp)->where('id','!=',$id)->where('deleted_at', NULL)->get();
            if($save_vac_esq==true) {
                if(count($repeat_curp)<=0) {
                    try {       
                        DB::beginTransaction();             
                        if($persona->save()) {
                            // ELIMINAR LOS ESQUEMAS YA ALMACENADOS
                            $delete_vacunas_esquemas = DB::table('personas_vacunas_esquemas')->where('personas_id', '=', $persona_id)->update(['deleted_at' => date('Y-m-d H:m:s')]);
                            $success = true;
                            $m = '';
                            foreach($vacunas_esquemas as $key=>$ve){
                                if($request['fecha_aplicacion'.$ve->id]!=NULL && $request['fecha_aplicacion'.$ve->id]!=""){
                                    $pve_id = '';
                                    $incremento_pve = 0;
                                    $pve_increment = PersonaVacunaEsquema::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();

                                    if(count($pve_increment)>0){
                                        $incremento_pve = $pve_increment[0]->incremento + 1; 
                                    } else {                 
                                        $incremento_pve = 1;                             
                                    }
                                    $pve_id = $clue->servidor.''.$incremento_pve; 

                                    $fecha_apli = explode('-',$request['fecha_aplicacion'.$ve->id]);
                                    $temp_fecha_aplicacion = $fecha_apli[2].'-'.$fecha_apli[1].'-'.$fecha_apli[0];
                                    $PersonaVacunaEsquema = new PersonaVacunaEsquema;
                                    $PersonaVacunaEsquema->id                   = $pve_id;
                                    $PersonaVacunaEsquema->servidor_id          = $clue->servidor;
                                    $PersonaVacunaEsquema->incremento           = $incremento_pve;
                                    $PersonaVacunaEsquema->personas_id          = $persona_id;
                                    $PersonaVacunaEsquema->vacunas_esquemas_id  = $ve->id;
                                    $PersonaVacunaEsquema->fecha_aplicacion     = $temp_fecha_aplicacion;
                                    $PersonaVacunaEsquema->lote                 = '00000';
                                    $PersonaVacunaEsquema->dosis                = $ve->dosis_requerida;
                                    $PersonaVacunaEsquema->usuario_id           = Auth::user()->email;
                                    $PersonaVacunaEsquema->updated_at           = date('Y-m-d H:m:s');
                                    if(!$PersonaVacunaEsquema->save()){
                                        $success = false;
                                        break;
                                    }                                                                           
                                }
                            }

                            if($success){
                                DB::commit();
                                $msgGeneral = 'Perfecto! se modificaron los datos';
                                $type       = 'flash_message_ok';
                            } else {
                                DB::rollback();
                                $msgGeneral = 'No se modificaron los datos. Verifique su información o recargue la página.';
                                $type       = 'flash_message_error';
                            }
                        } else {
                            DB::rollback();
                            $msgGeneral = 'No se modificaron los datos personales. Verifique su información o recargue la página.';
                            $type       = 'flash_message_error';                            
                        }
                    } catch(\PDOException $e){
                        $msgGeneral = 'Ocurrió un error al intentar modificar los datos enviados. Recargue la página e intente de nuevo';
                        $type       = 'flash_message_error';
                    }   
                } else {
                    $msgGeneral = 'La CURP está ya está registrada, verifique los datos.';
                    $type       = 'flash_message_error';
                }  
            } else {
                $msgGeneral = $msg_dosis;
                $type       = 'flash_message_error';
            }        
            
            Session::flash($type, $msgGeneral);
            return redirect()->back()->withInput();

        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $msgGeneral     = '';
        $type           = 'flash_message_info';
        $type2          = 'error';        
        $persona = Persona::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->can('delete.personas') && Auth::user()->activo==1) {
                try {
                    
                    DB::beginTransaction();

                    $updates = DB::table('personas')
                               ->where('id', '=', $id)
                               ->update(['deleted_at' => date('Y-m-d H:m:s'), 'usuario_id' => Auth::user()->email]);
                    
                    if ($updates) {
                        $updates_pve = DB::table('personas_vacunas_esquemas')
                                       ->where('personas_id', '=', $id)
                                       ->update(['deleted_at' => date('Y-m-d H:m:s'), 'usuario_id' => Auth::user()->email]);
                        if ($updates_pve) {
                            DB::commit();
                            $msgGeneral = 'Se borró el elemento';
                            $type2      = 'success';
                        } else {
                            DB::rollback();
                            $msgGeneral = 'No se borró toda la información del elemento';
                            $type2       = 'error';
                        }
                    } else {
                        DB::rollback();
                        $msgGeneral = 'No se borró el elemento';
                        $type2      = 'error';
                    }
                } catch(\PDOException $e){
                    $msgGeneral = 'Ocurrió un error al intentar eliminar los datos.';
                    $type2      = 'error';
                }
            } else {
                $msgGeneral = 'No tiene autorización para acceder al recurso. Se ha negado el acceso.';
                $type2      = 'error';
            }

            return response()->json([
                'code'    => 1,
                'title'   => 'Hey!',
                'text'    => $msgGeneral,
                'type'    => $type2,
                'styling' => 'bootstrap3'
            ]);
        } else {
            Session::flash('flash_message_error', 'No submit!');
            return redirect()->back();
        }
    }
}
