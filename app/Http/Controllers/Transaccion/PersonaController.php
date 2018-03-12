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
use App\User;
use App\Catalogo\Clue;
use App\Catalogo\Entidad;
use App\Catalogo\Pais;
use App\Catalogo\Ageb;
use App\Catalogo\Municipio;
use App\Catalogo\TipoParto;
use App\Catalogo\CodigoCenso;
use App\Catalogo\Institucion;
use App\Catalogo\Jurisdiccion;
use App\Catalogo\Localidad;
use App\Catalogo\Colonia;
use App\Catalogo\Vacuna;
use App\Catalogo\Esquema;
use App\Catalogo\VacunaEsquema;
use App\Catalogo\PersonaVacunaEsquema;
use App\Catalogo\PoblacionConapo;

class PersonaController extends Controller
{
    public $tipo_aplicacion = array("X","Dosis única","1a Dosis","2a Dosis","3a Dosis","4a Dosis","Refuerzo");
    public $ta_abreviatura = array("X","Ú","1a","2a","3a","4a","R");
    
    public $estados = array("X","AS","BC","BS","CC","CL","CM","CS","CH","DF","DG","GT","GR","HG","JC","MC","MN","MS","NT","NL","OC","PL","QT","QR","SP","SL","SR","TC","TS","TL","VZ","YN","ZS");
    
    /**
     * Consigue intervalos.
     *
     * @return string 
     */
    public function obtieneIntervaloCompleto($anio,$mes,$dia){
        if($anio==0){ 
            if($mes==0){ 
                return $dia.' días ';
            } else {
                if($dia==0){
                    return $mes.' meses ';
                } else {
                    return $mes.' meses y '.$dia.' días ';
                }
            }
        } else {
            if($mes==0){ 
                if($dia==0){
                    return $anio.' Años ';
                } else {
                    return $anio.' años y '.$dia.' días';
                }
            } else {
                if($dia==0){
                    return $anio.' años y '.$mes.' meses';
                } else {
                    return $anio.' años y '.$mes.' meses y '.$dia.' días';
                }
            }
        }
    }
    /**
     * Consigue tipo de aplicación.
     *
     * @return string
     */
    public function tipoAplicacion($tipo){
        if($tipo==1) {
            return 'Dosis única';
        } 
        if($tipo==2) {
            return '1a Dosis';
        } 
        if($tipo==3) {
            return '2a Dosis';
        }
        if($tipo==4){ 
            return '3a Dosis'; 
        }
        if($tipo==5){ 
            return '4a Dosis'; 
        }
        if($tipo==6) {
            return 'Refuerzo';
        }
    }
    
    /**
	 * @api {get}  /persona/  1. Lista de personas(censo nominal) 
	 * @apiVersion  0.1.0
	 * @apiName     IndexPersona
	 * @apiGroup    Transaccion/Persona
	 *
	 * @apiParam    {String}        q               Cadena de texto para búsqueda en personas. Se espera nombre de infante/tutor o CURP.
     * @apiParam    {Number}        municipios_id   Id de  Municipio seleccionado
     * @apiParam    {Number}        edad            Edad  del infante, valores; 1-10
     * @apiParam    {Number}        clues_id        Id de  Clue seleccionada
     * @apiParam    {Number}        rep             Determina el tipo de reporte: valores 1, 2 y 3
     *
     * @apiSuccess  {Json}          data            Lista de personas en formato JSON
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
     *       "user": {'id', 'idJurisdiccion', 'direccion', 'nombre', 'paterno', 'materno', 'email', 'foto', 'activo', 'borrado', 'asRoot', 'creadoAl', 'creadoUsuario', 'modificadoAl', 'modificadoUsuario', 'borradoAl', 'borradoUsuario', 'jurisdiccion':{'id', 'entidades_id', 'clues_id', 'clave', 'nombre', 'created_at', 'updated_at', 'deleted_at'}},
	 *       "data": [{'id', 'servidor_id', 'incremento', 'clues_id', 'paises_id', 'entidades_federativas_nacimiento_id', 'entidades_federativas_domicilio_id', 'municipios_id', 'localidades_id', 'colonias_id', 'agebs_id', 'instituciones_id', 'codigos_censos_id', 'tipos_partos_id', 'folio_certificado_nacimiento', 'nombre', 'apellido_paterno', 'apellido_materno', 'curp', 'genero', 'fecha_nacimiento', 'descripcion_domicilio', 'calle', 'numero', 'codigo_postal', 'sector', 'manzana', 'telefono_casa', 'telefono_celular', 'tutor', 'fecha_nacimiento_tutor', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}...]
	 *     } 
	 *
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 404 No encontrado
	 *     {
	 *       "icon"     :   String icono a utilizar en la vista,
     *       "error"    :   String número de error,
     *       "title"    :   String titulo del mensaje,
     *       "message"  :   String descripción del error
	 *     }
	 */
    public function index()
    {
        $parametros = Input::only(['q','municipios_id','edad','clues_id','rep','todo']);
        $ta_abreviatura = $this->ta_abreviatura;
        $q = "";
        $text = '';
        $m_selected = "";
        $e_selected = "0-0-0";
        $c_selected = "0";
        
        $rep = array('seg' => true, 'bio' => false, 'act' => false); 

        if($parametros['rep']){
            if($parametros['rep']=='act'){ $rep['seg'] = false; $rep['bio'] = false; $rep['act'] = true; }
            if($parametros['rep']=='bio'){ $rep['seg'] = false; $rep['bio'] = true;  $rep['act'] = false; }
        }

        $today = Carbon::today("America/Mexico_City");
		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {            
            if (Auth::user()->is('root|admin')) {
                $municipios = Municipio::where('deleted_at', NULL)->get(); 
                $clues = DB::table('clues')->select('id','nombre','clues'); 
                $m_selected = $municipios[0]->id;
                $anios_atras = Carbon::today("America/Mexico_City")->subYears(10)->format('Y-m-d'); 
                $personas = DB::table('personas')
                    ->select('personas.*','clu.clues AS clu_clues','clu.nombre AS clu_nombre','col.nombre AS col_nombre','loc.nombre AS loc_nombre','mun.nombre AS mun_nombre','tp.clave AS tp_clave','tp.descripcion AS tp_descripcion')
                    ->where('personas.fecha_nacimiento','>=',$anios_atras); 
                if (!isset($parametros['q']) && !isset($parametros['municipios_id']) && !isset($parametros['edad']) && !isset($parametros['clues_id'])){
                    if(isset($parametros['todo']) && $parametros['todo']==1){ 
                        $text = 'Todo, sin filtros';
                    } else {
                        $text = 'Todo, del día';
                        $personas = $personas
                            ->where('personas.created_at', '>=', $today);
                    }
                } else {
                    if(isset($parametros['todo'])  && $parametros['todo']==1){ } else {                        
                        if($parametros['q']){
                            $q = $parametros['q'];
                            $personas = $personas
                            ->where('personas.curp','LIKE',"%".$parametros['q']."%")
                            ->orWhere(DB::raw("CONCAT(personas.nombre,' ',personas.apellido_paterno,' ',personas.apellido_materno)"),'LIKE',"%".$parametros['q']."%")->orWhere('personas.fecha_nacimiento','LIKE',"%".$parametros['q']."%");
                        }
                        if($parametros['municipios_id'] && $parametros['municipios_id']!=0){
                            $m_selected = $parametros['municipios_id'];
                            $personas = $personas
                            ->where('personas.municipios_id', $parametros['municipios_id']);
                        }
                        if($parametros['clues_id'] && $parametros['clues_id']!=0){
                            $c_selected = $parametros['clues_id'];
                            $personas = $personas
                            ->where('personas.clues_id', $parametros['clues_id']);
                        }
                        if($parametros['edad']){
                            $e_selected = $parametros['edad'];
                            $edad_explode = explode("-", $parametros['edad']);
                            $fecha = $today->subDays($edad_explode[2])->subMonths($edad_explode[1])->subYears($edad_explode[0])->format('Y-m-d');
                            if($parametros['edad']!='0-0-0'){
                                $personas = $personas
                                ->where('personas.fecha_nacimiento', '>=', $fecha);
                            }
                        }
                    }
                }
            } else { // Limitar por clues                
                $municipios = Municipio::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at', NULL)->get();
                $clues = DB::table('clues')->select('id','nombre','clues')->where('jurisdicciones_id', Auth::user()->idJurisdiccion);
                $personas = DB::table('personas')->select('personas.*','clu.clues AS clu_clues','clu.nombre AS clu_nombre','col.nombre AS col_nombre','loc.nombre AS loc_nombre','mun.nombre AS mun_nombre','tp.clave AS tp_clave','tp.descripcion AS tp_descripcion')->join('clues','clues.id','=','personas.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
                if (!isset($parametros['q']) && !isset($parametros['municipios_id']) && !isset($parametros['edad']) && !isset($parametros['clues_id'])){
                    if(isset($parametros['todo'])  && $parametros['todo']==1){ 
                        $text = 'Todo, sin filtros';
                    } else {    
                        $text = 'Todo, del día';
                        $personas = $personas
                            ->where('personas.created_at', '>=', $today);
                    }
                } else { 
                    if(isset($parametros['todo'])  && $parametros['todo']==1){ } else {
                        if($parametros['q']){
                            $q = $parametros['q'];
                            $personas = $personas
                            ->where('personas.curp','LIKE',"%".$parametros['q']."%")
                            ->orWhere(DB::raw("CONCAT(personas.nombre,' ',personas.apellido_paterno,' ',personas.apellido_materno)"),'LIKE',"%".$parametros['q']."%")->orWhere('personas.fecha_nacimiento','LIKE',"%".$parametros['q']."%");
                        }
                        if($parametros['municipios_id'] && $parametros['municipios_id']!=0){
                            $clues = $clues->where('municipios_id', $parametros['municipios_id']);
                            $m_selected = $parametros['municipios_id'];
                            $personas = $personas
                            ->where('personas.municipios_id', $parametros['municipios_id']);
                        }
                        if($parametros['clues_id'] && $parametros['clues_id']!=0){
                            $c_selected = $parametros['clues_id'];
                            $personas = $personas
                            ->where('personas.clues_id', $parametros['clues_id']);
                        }
                        if($parametros['edad']){
                            $e_selected = $parametros['edad'];
                            $edad_explode = explode("-", $parametros['edad']);
                            $fecha = $today->subDays($edad_explode[2])->subMonths($edad_explode[1])->subYears($edad_explode[0])->format('Y-m-d');
                            if($parametros['edad']!='0-0-0'){
                                $personas = $personas
                                ->where('personas.fecha_nacimiento', '>=', $fecha);
                            }
                        }
                    }
                }
            }
            

            $clues = $clues->where('clues','like','CSSSA%')->where('deleted_at',NULL)->where('estatus_id', 1)->get();
            $data = $personas->where('personas.deleted_at', NULL)
                ->leftJoin('clues AS clu','clu.id','=','personas.clues_id')
                ->leftJoin('municipios AS mun','mun.id','=','personas.municipios_id')
                ->leftJoin('localidades AS loc','loc.id','=','personas.localidades_id')
                ->leftJoin('colonias AS col','col.id','=','personas.colonias_id')
                ->leftJoin('tipos_partos AS tp','tp.id','=','personas.tipos_partos_id')
                ->orderBy('personas.municipios_id', 'ASC')
                ->orderBy('personas.clues_id', 'ASC')
                ->orderBy('personas.apellido_paterno', 'ASC')
                ->orderBy('personas.apellido_materno', 'ASC')
                ->orderBy('personas.nombre', 'ASC')
                ->get();
            
                if($rep['seg']==true){
                foreach ($data as $cont=>$value) { // valorar seguimientos, biologico y actividades
                    $value->seguimientos = collect();
                    $bd = explode("-", $value->fecha_nacimiento);
                    
                    $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                        ->select('ve.id','ve.vacunas_id','ve.tipo_aplicacion','ve.esquemas_id','ve.intervalo_inicio_anio','ve.intervalo_inicio_mes','ve.intervalo_inicio_dia','ve.margen_anticipacion','fila','columna','ve.deleted_at','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                        ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                        ->where('ve.esquemas_id', $bd[0])
                        ->where('ve.deleted_at', NULL)
                        ->where('v.deleted_at', NULL)                
                        ->orderBy('v_orden_esquema')
                        ->orderBy('intervalo_inicio_anio', 'ASC')
                        ->orderBy('intervalo_inicio_mes', 'ASC')
                        ->orderBy('intervalo_inicio_dia', 'ASC')
                        ->orderBy('fila', 'ASC')
                        ->orderBy('columna', 'ASC')
                        ->get(); 
                    $seguimientos = collect();
                    foreach ($esquema_detalle as $key_esquema => $value_esquema) {
                        $marca = ' ';
                        $pve = PersonaVacunaEsquema::select('fecha_aplicacion')->where('personas_id', $value->id)->where('vacunas_esquemas_id', $value_esquema->id)->where('deleted_at', NULL)->take(1)->get();
                        $hoy = Carbon::today("America/Mexico_City");
                        $fecha_ideal = Carbon::parse($bd[0]."-".$bd[1]."-".$bd[2]." 00:00:00","America/Mexico_City")->addYears($value_esquema->intervalo_inicio_anio)->addMonths($value_esquema->intervalo_inicio_mes)->addDays($value_esquema->intervalo_inicio_dia)->subDays($value_esquema->margen_anticipacion);
                        if($fecha_ideal<=$hoy){ $marca = '__'; }
                        if(count($pve)>0){ $marca = 'X'; }                    
                        $seguimientos->push(['id' => $value_esquema->id, 'tipo_aplicacion' => $ta_abreviatura[$value_esquema->tipo_aplicacion], 'vacunas_id' => $value_esquema->vacunas_id, 'clave' => $value_esquema->clave, 'marca' => $marca, 'color_rgb' => $value_esquema->color_rgb]);
                    }
                    $value->seguimientos = $seguimientos;
                }
            }

            $arraymunicipio[0] = 'Todos los municipios';
            foreach ($municipios as $cont=>$municipio) {
                $arraymunicipio[$municipio->id] = $municipio->nombre;
            }
            $arrayclue[0] = 'Todas las unidades de salud';
            foreach ($clues as $cont=>$clue) {
                $arrayclue[$clue->id] = $clue->clues .' - '.$clue->nombre;
            }
            
            $usuario = User::with('jurisdiccion')->find(Auth::user()->id);
            return view('persona.index')->with(['text' => $text, 'data' => $data, 'q' => $q, 'rep' => $rep, 'm_selected' => $m_selected, 'c_selected' => $c_selected, 'e_selected' => $e_selected, 'clues' => $arrayclue, 'municipios' => $arraymunicipio, 'user' => $usuario]);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function dashboard(Request $request)
     {
         $today = Carbon::now("America/Mexico_City");
         $ultima_semana = Carbon::now("America/Mexico_City")->subWeeks(1);
         $capturas_por_semana = collect();
         $capturas_por_semana = Persona::where('usuario_id', Auth::user()->email)->whereBetween('created_at', [$ultima_semana, $today])->where('deleted_at', NULL)->count();
         
         return response()->json([ 'capturas_por_semana'  => $capturas_por_semana,'us'  => $ultima_semana,'t'  => $today]);
     }

    /**
     * Display a index of reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function reporte()
    {
        if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            $edad = 0;
            $clue_id = 0;
            $genero = 'X';
            $titulo_edad = '';
            $titulo_genero = '';
            $titulo_clue = '';
            $parametros = Input::only('clue_id','edad','genero');
            $now = Carbon::now("America/Mexico_City");
            $data = collect();
            if (Auth::user()->is('root|admin')) {
                $clues = Clue::select('id','nombre','clues')->where('deleted_at',NULL)->where('estatus_id', 1)->get();
                $data = Persona::where('deleted_at', NULL);                    
            } else { // Limitar por clues
                $clues = Clue::select('id','nombre','clues')->where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
                $data = Persona::select('personas.*')
                    ->join('clues','clues.id','=','personas.clues_id')
                    ->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)
                    ->where('personas.deleted_at', NULL);
            }
            
            if(isset($parametros['edad']) && $parametros['edad']>0 && $parametros['edad']<=10){ // Edad especifica fecha actual menos x años atras
                $edad = $parametros['edad']; 
                $titulo_edad = 'hasta '.$edad.' a\u00f1os de edad';
                $data = $data->where('fecha_nacimiento', '>=', Carbon::now("America/Mexico_City")->subYears($parametros['edad']));
            }
            if(isset($parametros['genero']) && $parametros['genero']=="M" || $parametros['genero']=="F"){ // Género especifico
                $genero = $parametros['genero'];
                $titulo_genero = 'Todas las ni\u00f1as ';
                if($genero=="M")
                    $titulo_edad = 'Todos los ni\u00f1os ';
                $data = $data->where('genero', $parametros['genero']);
            }

            if(isset($parametros['clue_id']) && $parametros['clue_id']!=NULL && $parametros['clue_id']>0){ // Clue especifica
                $clue_id = $parametros['clue_id'];
                $clue_x = Clue::find($clue_id);
                $titulo_clue = ' pertenecientes a '.$clue_x->clues.' - '.$clue_x->nombre.' ';
                $data = $data->where('clues_id', $parametros['clue_id']); 
            }

            $titulo = $titulo_genero.' '.$titulo_edad.' '.$titulo_clue;

            $data = $data->with('clue','municipio','localidad','colonia','ageb','afiliacion','codigo','tipoParto')
            ->orderBy('id', 'DESC')->get();
            foreach ($data as $key => $value) { // buscar todas las aplicaciones
                $value->aplicaciones = DB::table('personas_vacunas_esquemas AS pve')
                ->select('pve.*','ve.vacunas_id','ve.tipo_aplicacion','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas_esquemas AS ve','ve.id','=','pve.vacunas_esquemas_id')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('pve.personas_id', $value->id)
                ->where('pve.deleted_at', NULL)
                ->where('ve.deleted_at', NULL)
                ->where('v.deleted_at', NULL)                
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio_anio', 'ASC')
                ->orderBy('intervalo_inicio_mes', 'ASC')
                ->orderBy('intervalo_inicio_dia', 'ASC')
                ->orderBy('fila', 'ASC')
                ->orderBy('columna', 'ASC')
                ->get(); 
            }

            $arrayclue[0] = 'Todas las unidades de salud';
            foreach ($clues as $cont=>$clue) {
                $arrayclue[$clue->id] = $clue->clues .' - '.$clue->nombre;
            }
            $usuario = User::with('jurisdiccion')->find(Auth::user()->id);
            return view('persona.reporte')->with(['titulo' => $titulo, 'clues' => $arrayclue, 'data' => $data, 'user' => $usuario, 'clue_id' => $clue_id, 'edad' => $edad, 'genero' => $genero]);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param   int     filtro          1: búsqueda por filtros de municipio, localidad, clue, ageb, colonia, manzana y sector. 2: búsqueda por cadena de texto 
     * @param   int     municipios_id   Id del lista de municipios  
     * @param   int     localidaddes_id Id del lista de localidades 
     * @param   int     clues_id        Id del lista de unidades de salud 
     * @param   int     agebs_id        Id del lista de agebs 
     * @param   string  sector          cadena de texto sector  
     * @param   string  manzana         cadena de texto manzana     
     * @param   string  q               Cadena de texto para búsqueda en personas. Se espera nombre de infante/tutor o CURP
     * @param   int     todo            Determina si la busqueda es de todo lo registrado, valor esperado: 1
     * @param   string  rep             Parametro que determina el tipo de reporte a mostrar, valores esperados: seg, act y bio
     * @return \Illuminate\Http\Response
     */
    public function buscar(Request $request)
    {
        $parametros = Input::only(['q','jurisdicciones_id','municipios_id','localidades_id','clues_id','agebs_id','sector','manzana','filtro','todo','rep']);
        $ta_abreviatura = $this->ta_abreviatura;
        $q = "";
        $text = '';

        $today = Carbon::today("America/Mexico_City");
		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) { 
            $anios_atras = Carbon::today("America/Mexico_City")->subYears(10)->format('Y-m-d'); 
            if($parametros['rep']=='seg'){ // Reporte de Seguimientos
                $personas = DB::table('personas')
                ->select('personas.*','clu.clues AS clu_clues','clu.jurisdicciones_id AS clu_jurisdiccion_id','clu.nombre AS clu_nombre','col.nombre AS col_nombre','loc.nombre AS loc_nombre','mun.nombre AS mun_nombre','tp.clave AS tp_clave','tp.descripcion AS tp_descripcion');  

                if (Auth::user()->is('root|admin')) { } else {            
                    $personas = $personas->where('clu.jurisdicciones_id', Auth::user()->idJurisdiccion);
                }

                if ($parametros['todo']==NULL){ // La búsqueda usa filtros
                    if (isset($parametros['filtro']) && $parametros['filtro']==1){ // Los filtros son: jurisdicciones(root,admin), municipios, localidad, clue, agebs, colonias, manzana y sector
                        $text = 'Filtros: ';
                        if(isset($parametros['jurisdicciones_id']) && $parametros['jurisdicciones_id']!=0){
                            $personas = $personas->where('clu.jurisdicciones_id', $parametros['jurisdicciones_id']);
                        }
                        if(isset($parametros['municipios_id']) && $parametros['municipios_id']!=0){
                            $personas = $personas->where('personas.municipios_id', $parametros['municipios_id']);
                        }
                        if(isset($parametros['clues_id']) && $parametros['clues_id']!=0){
                            $personas = $personas->where('personas.clues_id', $parametros['clues_id']);
                        }
                        if(isset($parametros['localidades_id']) && $parametros['localidades_id']!=0){
                            $personas = $personas->where('personas.localidades_id', $parametros['localidades_id']);
                        }
                        if(isset($parametros['colonias_id']) && $parametros['colonias_id']!=0){
                            $personas = $personas->where('personas.colonias_id', $parametros['colonias_id']);
                        }
                        if(isset($parametros['agebs_id']) && $parametros['agebs_id']!=0){
                            $personas = $personas->where('personas.agebs_id', $parametros['agebs_id']);
                        }
                        if(isset($parametros['sector']) && $parametros['sector']!="" && $parametros['sector']!=NULL){
                            $personas = $personas->where('personas.sector', $parametros['sector']);
                        }
                        if(isset($parametros['manzana']) && $parametros['manzana']!="" && $parametros['manzana']!=NULL){
                            $personas = $personas->where('personas.manzana', $parametros['manzana']);
                        }
                    } 
                    if(isset($parametros['filtro']) && $parametros['filtro']==2){ // El filtro es buscar por cadena de texto
                        $text = 'Nombre del infante/tutor o CURP: '.$parametros['q'];
                        $personas = $personas->where(function($query) use ($parametros) {
                            $query->where('personas.curp','LIKE',"%".$parametros['q']."%")
                            ->orWhere('personas.tutor','LIKE',"%".$parametros['q']."%")
                            ->orWhere(\DB::raw("CONCAT(personas.nombre,' ',personas.apellido_paterno,' ',personas.apellido_materno)"),'LIKE',"%".$parametros['q']."%");
                        });
                    }
                }
            
                $data = $personas->where('personas.fecha_nacimiento', '>=', $anios_atras)
                ->where('personas.deleted_at', NULL)
                ->leftJoin('clues AS clu','clu.id','=','personas.clues_id')
                ->leftJoin('municipios AS mun','mun.id','=','personas.municipios_id')
                ->leftJoin('localidades AS loc','loc.id','=','personas.localidades_id')
                ->leftJoin('colonias AS col','col.id','=','personas.colonias_id')
                ->leftJoin('tipos_partos AS tp','tp.id','=','personas.tipos_partos_id')
                ->orderBy('clu_jurisdiccion_id', 'ASC')
                ->orderBy('personas.municipios_id', 'ASC')
                ->orderBy('personas.clues_id', 'ASC')
                ->orderBy('personas.apellido_paterno', 'ASC')
                ->orderBy('personas.apellido_materno', 'ASC')
                ->orderBy('personas.nombre', 'ASC')
                ->get();            
                
                foreach ($data as $cont=>$value) { 
                    $value->seguimientos = collect();
                    $bd = explode("-", $value->fecha_nacimiento);
                    
                    $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                        ->select('ve.id','ve.vacunas_id','ve.tipo_aplicacion','ve.esquemas_id','ve.intervalo_inicio_anio','ve.intervalo_inicio_mes','ve.intervalo_inicio_dia','ve.margen_anticipacion','fila','columna','ve.deleted_at','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                        ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                        ->where('ve.esquemas_id', $bd[0])
                        ->where('ve.deleted_at', NULL)
                        ->where('v.deleted_at', NULL)                
                        ->orderBy('v_orden_esquema')
                        ->orderBy('intervalo_inicio_anio', 'ASC')
                        ->orderBy('intervalo_inicio_mes', 'ASC')
                        ->orderBy('intervalo_inicio_dia', 'ASC')
                        ->orderBy('fila', 'ASC')
                        ->orderBy('columna', 'ASC')
                        ->get(); 
                    $seguimientos = collect();
                    foreach ($esquema_detalle as $key_esquema => $value_esquema) {
                        $marca = ' ';
                        $pve = PersonaVacunaEsquema::select('fecha_aplicacion')->where('personas_id', $value->id)->where('vacunas_esquemas_id', $value_esquema->id)->where('deleted_at', NULL)->take(1)->get();
                        $hoy = Carbon::today("America/Mexico_City");
                        $fecha_ideal = Carbon::parse($bd[0]."-".$bd[1]."-".$bd[2]." 00:00:00","America/Mexico_City")->addYears($value_esquema->intervalo_inicio_anio)->addMonths($value_esquema->intervalo_inicio_mes)->addDays($value_esquema->intervalo_inicio_dia)->subDays($value_esquema->margen_anticipacion);
                        if($fecha_ideal<=$hoy){ $marca = '__'; }
                        if(count($pve)>0){ $marca = 'X'; }                    
                        $seguimientos->push(['id' => $value_esquema->id, 'tipo_aplicacion' => $ta_abreviatura[$value_esquema->tipo_aplicacion], 'vacunas_id' => $value_esquema->vacunas_id, 'clave' => $value_esquema->clave, 'marca' => $marca, 'color_rgb' => $value_esquema->color_rgb]);
                    }
                    $value->seguimientos = $seguimientos;
                }
            }


            if($parametros['rep']=='act'){ // Reporte de actividades
                $data = collect();
                $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                    ->select('ve.id','ve.vacunas_id','ve.tipo_aplicacion','ve.esquemas_id','ve.intervalo_inicio_anio','ve.intervalo_inicio_mes','ve.intervalo_inicio_dia','ve.margen_anticipacion','fila','columna','ve.deleted_at','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                    ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                    ->where('ve.esquemas_id', Carbon::now()->format('Y'))
                    ->where('ve.deleted_at', NULL)
                    ->where('v.deleted_at', NULL)                
                    ->orderBy('v_orden_esquema')
                    ->orderBy('intervalo_inicio_anio', 'ASC')
                    ->orderBy('intervalo_inicio_mes', 'ASC')
                    ->orderBy('intervalo_inicio_dia', 'ASC')
                    ->orderBy('fila', 'ASC')
                    ->orderBy('columna', 'ASC')
                    ->get();
                
                if(isset($parametros['municipios_id']) && $parametros['municipios_id']!=0){
                    $poblacion_oficial_municipio = PoblacionConapo::where('anio', Carbon::now()->format('Y'))->where('municipios_id',$parametros['municipios_id'])->where('deleted_at', NULL )->take(1)->get();
                    if(count($poblacion_oficial_municipio)<0){
                        $poblacion_oficial_municipio[0]->hombres_0 = 0;   
                        $poblacion_oficial_municipio[0]->hombres_1 = 0;
                        $poblacion_oficial_municipio[0]->hombres_2 = 0;
                        $poblacion_oficial_municipio[0]->hombres_3 = 0;
                        $poblacion_oficial_municipio[0]->hombres_4 = 0;
                        $poblacion_oficial_municipio[0]->hombres_5 = 0;
                        $poblacion_oficial_municipio[0]->hombres_6 = 0;
                        $poblacion_oficial_municipio[0]->hombres_7 = 0;
                        $poblacion_oficial_municipio[0]->hombres_8 = 0;
                        $poblacion_oficial_municipio[0]->hombres_9 = 0; 
                        $poblacion_oficial_municipio[0]->mujeres_10= 0; 
                        $poblacion_oficial_municipio[0]->mujeres_0 = 0;   
                        $poblacion_oficial_municipio[0]->mujeres_1 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_2 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_3 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_4 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_5 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_6 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_7 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_8 = 0;
                        $poblacion_oficial_municipio[0]->mujeres_9 = 0; 
                        $poblacion_oficial_municipio[0]->mujeres_10= 0; 
                    }
                    
                    $edad_0m_nominal            = 0;
                    $edad_1m_nominal            = 0;
                    $edad_2m_nominal            = 0;
                    $edad_3m_nominal            = 0;
                    $edad_4m_nominal            = 0;
                    $edad_5m_nominal            = 0;
                    $edad_6m_nominal            = 0;
                    $edad_7m_nominal            = 0;
                    $edad_8m_nominal            = 0;
                    $edad_9m_nominal            = 0;
                    $edad_10m_nominal           = 0;
                    $edad_11m_nominal           = 0;
                    $edad_menores_1a_nominal    = 0;
                    $edad_12_17m_nominal        = 0;
                    $edad_18_24m_nominal        = 0;
                    $edad_1a_nominal            = 0;
                    $edad_2a_nominal            = 0;
                    $edad_3a_nominal            = 0;
                    $edad_4a_nominal            = 0;
                    $edad_1_4a_nominal          = 0;
                    $edad_5a_nominal            = 0;
                    $edad_6a_nominal            = 0;
                    $edad_7a_nominal            = 0;
                    $edad_5_7a_nominal          = 0;
                    $edad_0_7a_nominal          = 0;
                    $r = 0;

                    $menor_siete_anios = Carbon::today("America/Mexico_City")->subYears(7)->format('Y-m-d');
                    
                    $data_actividad = DB::table('personas')
                        ->select('personas.id','personas.fecha_nacimiento','personas.municipios_id','personas.deleted_at')
                        ->where('fecha_nacimiento', '>=', $menor_siete_anios)
                        ->where('fecha_nacimiento', '<=', Carbon::today("America/Mexico_City")->format('Y-m-d'))
                        ->where('deleted_at', NULL)
                        ->where('municipios_id', $parametros['municipios_id'])
                        ->get();
                        
                    foreach ($data_actividad as $key => $value) {
                        /**
                         * Menores de un año
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(1)->format('Y-m-d'))
                            $edad_0m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(2)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(1)->format('Y-m-d'))
                            $edad_1m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(3)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(2)->format('Y-m-d'))
                            $edad_2m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(4)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(3)->format('Y-m-d'))
                            $edad_3m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(5)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(4)->format('Y-m-d'))
                            $edad_4m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(6)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(5)->format('Y-m-d'))
                            $edad_5m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(7)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(6)->format('Y-m-d'))
                            $edad_6m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(8)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(7)->format('Y-m-d'))
                            $edad_7m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(9)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(8)->format('Y-m-d'))
                            $edad_8m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(10)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(9)->format('Y-m-d'))
                            $edad_9m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(11)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(10)->format('Y-m-d'))
                            $edad_10m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(12)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(11)->format('Y-m-d'))
                            $edad_11m_nominal++;
                        $edad_menores_1a_nominal = $edad_0m_nominal + $edad_1m_nominal + $edad_2m_nominal + $edad_3m_nominal + $edad_4m_nominal + $edad_5m_nominal + $edad_6m_nominal + $edad_7m_nominal+ $edad_8m_nominal + $edad_9m_nominal + $edad_10m_nominal + $edad_11m_nominal;
                        /**
                         * Un año
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(17)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(12)->format('Y-m-d'))
                            $edad_12_17m_nominal++;
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(24)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(17)->format('Y-m-d'))
                            $edad_18_24m_nominal++;
                        $edad_1a_nominal = $edad_12_17m_nominal + $edad_18_24m_nominal;
                        /**
                         * Dos años
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(36)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(24)->format('Y-m-d'))
                            $edad_2a_nominal++;
                        /**
                         * Tres años
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(48)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(36)->format('Y-m-d'))
                            $edad_3a_nominal++;
                        /**
                         * Cuatro años
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(60)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(48)->format('Y-m-d'))
                            $edad_4a_nominal++;
                        $edad_1_4a_nominal = $edad_1a_nominal + $edad_2a_nominal + $edad_3a_nominal + $edad_4a_nominal;
                        /**
                         * Cinco años
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(72)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(60)->format('Y-m-d'))
                            $edad_5a_nominal++;
                        /**
                         * Seis años
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(84)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(72)->format('Y-m-d'))
                            $edad_6a_nominal++;
                        /**
                         * Siete años
                         */
                        if($value->fecha_nacimiento >  Carbon::today("America/Mexico_City")->subMonths(96)->format('Y-m-d') && $value->fecha_nacimiento <= Carbon::today("America/Mexico_City")->subMonths(84)->format('Y-m-d'))
                            $edad_7a_nominal++;

                        $edad_5_7a_nominal = $edad_5a_nominal + $edad_6a_nominal + $edad_7a_nominal;
                        $edad_0_7a_nominal = $edad_menores_1a_nominal + $edad_1_4a_nominal + $edad_5_7a_nominal;
                    }
                    
                    $po_1a = ($poblacion_oficial_municipio[0]->hombres_1 + $poblacion_oficial_municipio[0]->mujeres_1);
                    
                    $data->push(["edad"=>"0 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"1 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_1m_nominal]);
                    $data->push(["edad"=>"2 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_2m_nominal]);
                    $data->push(["edad"=>"3 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_3m_nominal]);
                    $data->push(["edad"=>"4 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_4m_nominal]);
                    $data->push(["edad"=>"5 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_5m_nominal]);
                    $data->push(["edad"=>"6 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_6m_nominal]);
                    $data->push(["edad"=>"7 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_7m_nominal]);
                    $data->push(["edad"=>"8 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_8m_nominal]);
                    $data->push(["edad"=>"9 mes",       "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_9m_nominal]);
                    $data->push(["edad"=>"10 mes",      "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_10m_nominal]);
                    $data->push(["edad"=>"11 mes",      "poblacion_oficial"=>($po_1a/12) , "poblacion_nominal"=>$edad_11m_nominal]);
                    $data->push(["edad"=>"< 1 año",     "poblacion_oficial"=> $po_1a     , "poblacion_nominal"=>$edad_menores_1a_nominal]);
                    $data->push(["edad"=>"12-17 meses", "poblacion_oficial"=>($po_1a/2)  , "poblacion_nominal"=>$edad_12_17m_nominal]);
                    $data->push(["edad"=>"18-24 meses", "poblacion_oficial"=>($po_1a/2)  , "poblacion_nominal"=>$edad_18_24m_nominal]);
                    $data->push(["edad"=>"1 año",       "poblacion_oficial"=> $po_1a     , "poblacion_nominal"=>$edad_1a_nominal]);
                    $data->push(["edad"=>"2 años",      "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_1 + $poblacion_oficial_municipio[0]->mujeres_1) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"3 años",      "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_2 + $poblacion_oficial_municipio[0]->mujeres_2) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"4 años",      "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_3 + $poblacion_oficial_municipio[0]->mujeres_3) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"1-4 años",    "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_4 + $poblacion_oficial_municipio[0]->mujeres_4) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"5 años",      "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_5 + $poblacion_oficial_municipio[0]->mujeres_5) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"6 años",      "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_6 + $poblacion_oficial_municipio[0]->mujeres_6) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"7 años",      "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_7 + $poblacion_oficial_municipio[0]->mujeres_7) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"5-7 años",    "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_1 + $poblacion_oficial_municipio[0]->mujeres_1) , "poblacion_nominal"=>$edad_0m_nominal]);
                    $data->push(["edad"=>"0-7 años",    "poblacion_oficial"=>($poblacion_oficial_municipio[0]->hombres_1 + $poblacion_oficial_municipio[0]->mujeres_1) , "poblacion_nominal"=>$edad_0m_nominal]);
                    
                } else { // Todos los municipios

                }
            }



            if($parametros['rep']=='bio'){ // Reporte de bilógicos
            }
            
            $usuario = User::with('jurisdiccion')->find(Auth::user()->id);
            return response()->json(['text' => $text, 'data' => $data, 'usuario' => $usuario]);
        } else {
            return response()->json([ 'data' => [$data]]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function curpRepetida(Request $request)
    {
        $data = Persona::where('curp', $request->curp)->where('deleted_at', NULL)->get();
        return response()->json([ 'data' => $data]);
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
	 * @api {get}   /persona/create   2. Crear vista de nueva Persona/Infante
	 * @apiVersion  0.1.0
	 * @apiName     CreatePersona
	 * @apiGroup    Transaccion/Persona
     * 
     * @apiSuccess  {View}    create                 Vista alojada en: \resources\views\persona\create   
     * 
     */
	public function create()
    {
        if (Auth::user()->can('create.personas') && Auth::user()->activo==1) {
            return view('persona.create');
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
	 * @api {post} /persona/store     3. Crear Persona/Infante
	 * @apiVersion  0.1.0
	 * @apiName     StorePersona
	 * @apiGroup    Transaccion/Persona
	 *
     * @apiParam    {Request}       request                     Cabeceras de la petición.
	 *
	 * @apiSuccess  {View}          /persona/create             Vista para crear Persona
     * 
     * @apiSuccess  {String}        estatus                  Valores: info, success
     * @apiSuccess  {String}        titulo                   Titulo del mensaje
     * @apiSuccess  {String}        texto                    Mensaje descriptivo de la operación realizada
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'titulo'   :  'Perfecto!',
     *       'texto'    :  'Operación realizada con éxito',
     *       'estatus'  :  'success'
	 *     }
	 *
     * @apiError  {String}        estatus                  Valores: warning, error
     * @apiError  {String}        titulo                   Titulo del mensaje de error
     * @apiError  {String}        texto                    Mensaje descriptivo de la operación fallida
     * @apiError  PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 409 Conflicto
	 *     {
     *       'titulo'   :  'Error!',
     *       'texto'    :  'Operación fallida, -- Mensaje de error -- ',
     *       'estatus'  :  'error'
	 *     }
	 */
    public function store(Request $request)
    {
        $msgGeneral = '';
        $type       = 'flash_message_info';
        $tipo_aplicacion=$this->tipo_aplicacion;

        if (Auth::user()->can('create.personas') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'same'     => 'El campo :attribute debe ser igual al password',
                'sometimes'=> 'El campo :attribute debe sometimes',
                'confirmed'=> 'El campo :attribute debe ser confirmado',
                'date'     => 'El campo :attribute debe ser formato fecha',
                'before'   => 'La :attribute debe ser menor o igual a la fecha limite(fecha actual o fecha de nacimiento del niño)'
            ];

            $rules = [
                'nombre'                                 => 'required|min:3|max:30|string',
                'paterno'                                => 'required|min:2|max:20|string',
                'materno'                                => 'required|min:2|max:20|string',
                'clue_id'                                => 'required|min:1|numeric',
                'fecha_nacimiento'                       => 'required|date|before:tomorrow',
                'curp'                                   => 'required|min:17|max:18',
                'genero'                                 => 'required|in:F,M',
                'tipo_parto_id'                          => 'required|min:1|numeric',
                'entidad_federativa_nacimiento_id'       => 'required|min:1|numeric',
                'municipio_id'                           => 'required|min:1|numeric',
                'localidad_id'                           => 'required|min:1|numeric',
                'calle'                                  => 'required|min:1|max:100',
                'numero'                                 => 'required|min:1|max:5',
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
            if($request->colonias_id==0)
                $request->colonias_id = NULL;
            $fecha_nacimiento = explode('-',$request->fecha_nacimiento);
            $request->fecha_nacimiento = $fecha_nacimiento[2].'-'.$fecha_nacimiento[1].'-'.$fecha_nacimiento[0]; // formato valido para guardar fecha n

            $fecha_nacimiento_tutor = explode('-',$request->fecha_nacimiento_tutor);
            if(array_key_exists(0, $fecha_nacimiento_tutor) && array_key_exists(1, $fecha_nacimiento_tutor) && array_key_exists(2, $fecha_nacimiento_tutor)){
                $request->fecha_nacimiento_tutor = $fecha_nacimiento_tutor[2].'-'.$fecha_nacimiento_tutor[1].'-'.$fecha_nacimiento_tutor[0]; // formato valido para guardar fecha n t
            } else {
                $request->fecha_nacimiento_tutor = NULL;
            }
            
                   
            $persona = new Persona;
            $persona->id                    = $persona_id;
            $persona->servidor_id           = $clue->servidor;
            $persona->incremento            = $incremento;
            $persona->nombre                = strtoupper($request->nombre);
            $persona->apellido_paterno      = strtoupper($request->paterno);
            $persona->apellido_materno      = strtoupper($request->materno);
            $persona->clues_id              = (int) $request->clue_id;
            $persona->fecha_nacimiento      = $request->fecha_nacimiento;
            $persona->curp                  = strtoupper($request->curp);
            $persona->genero                = $request->genero;
            $persona->tipos_partos_id       = $request->tipo_parto_id;
            $persona->entidades_federativas_nacimiento_id = $request->entidad_federativa_nacimiento_id;
            $persona->entidades_federativas_domicilio_id = $request->entidad_federativa_nacimiento_id;
            $persona->municipios_id         = $request->municipio_id;
            $persona->localidades_id        = $request->localidad_id;
            $persona->agebs_id              = $request->ageb_id;
            $persona->colonias_id           = $request->colonias_id;
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
            $persona->created_at            = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
                        
            
            $vacunas_esquemas = DB::table('vacunas_esquemas AS ve')
                ->select('ve.*','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', $fecha_nacimiento[2])
                ->where('ve.deleted_at', NULL)
                ->where('v.deleted_at', NULL)                
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio_anio', 'ASC')
                ->orderBy('intervalo_inicio_mes', 'ASC')
                ->orderBy('intervalo_inicio_dia', 'ASC')
                ->orderBy('fila', 'ASC')
                ->orderBy('columna', 'ASC')
                ->get(); 
                
            $born_date = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City");
            $today     = Carbon::now("America/Mexico_City");

            $collect_esquema_detalle = collect();
            foreach ($vacunas_esquemas as $key => $value) {
                $fecha = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addYears($value->intervalo_inicio_anio)->addMonths($value->intervalo_inicio_mes)->addDays($value->intervalo_inicio_dia)->subDays($value->margen_anticipacion);
                if($fecha<=$today){                    
                    $mayores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                    ->where('esquemas_id', $fecha_nacimiento[2])
                    ->where('id', '!=', $value->id)
                    ->where('deleted_at', NULL)
                    ->orderBy('intervalo_inicio_anio','ASC')
                    ->orderBy('intervalo_inicio_mes','ASC')
                    ->orderBy('intervalo_inicio_dia','ASC')->get();

                    $collect_mayores = collect();
                    $collect_menores = collect();
                    foreach ($mayores as $k_mayores => $v_mayores) { // dosis que son diferentes a la actual de menor a mayor
                        $fecha_mayores = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addYears($v_mayores->intervalo_inicio_anio)->addMonths($v_mayores->intervalo_inicio_mes)->addDays($v_mayores->intervalo_inicio_dia)->subDays($v_mayores->margen_anticipacion);
                        if($fecha_mayores>$fecha){ 
                            $collect_mayores->push($v_mayores);
                        }
                    }

                    $value->mayores = $collect_mayores;

                    $menores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                    ->where('esquemas_id', $fecha_nacimiento[2])
                    ->where('id', '!=', $value->id)
                    ->where('deleted_at', NULL)
                    ->orderBy('intervalo_inicio_anio','DESC')
                    ->orderBy('intervalo_inicio_mes','DESC')
                    ->orderBy('intervalo_inicio_dia','DESC')->get();

                    foreach ($menores as $k_menores => $v_menores) {
                        $fecha_menores = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addYears($v_menores->intervalo_inicio_anio)->addMonths($v_menores->intervalo_inicio_mes)->addDays($v_menores->intervalo_inicio_dia)->subDays($v_menores->margen_anticipacion);
                        if($fecha_menores<$fecha){ 
                            $collect_menores->push($v_menores);
                        }
                    }
                      
                    $value->menores = $collect_menores;
                    $collect_esquema_detalle->push($value);
                }
            } // End foreach principal
            $vacunas_esquemas = $collect_esquema_detalle;

            $save_vac_esq = true;            
            $msg_dosis = '';
            foreach($vacunas_esquemas as $key=>$ve){                     
                if($request['fecha_aplicacion'.$ve->id]!=NULL && $request['fecha_aplicacion'.$ve->id]!="" && $request['fecha_aplicacion'.$ve->id]!="__-__-____"){ // Si trae algún valor la variable
                    $fecha_apli = explode('-',$request['fecha_aplicacion'.$ve->id]);
                    if(array_key_exists(0, $fecha_apli) && array_key_exists(1, $fecha_apli) && array_key_exists(2, $fecha_apli)){ // Si cumple con día, mes y año
                        $temp_fecha_aplicacion = $fecha_apli[2].'-'.$fecha_apli[1].'-'.$fecha_apli[0];
                        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$temp_fecha_aplicacion)) { // valida formato de fecha
                            
                            /*** AHORA  ***/
                            $f_ahora = Carbon::now("America/Mexico_City");
                            /*** APLICACIÓN ***/
                            $f_aplicacion = Carbon::parse($fecha_apli[2]."-".$fecha_apli[1]."-".$fecha_apli[0]." 00:00:00","America/Mexico_City");
                            /*** NACIMIENTO ***/
                            $f_nacimiento   = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City");
                            
                            if($f_aplicacion>=$f_nacimiento && $f_aplicacion<=$f_ahora) { // Si la fecha de aplicación >= fecha de nacimiento y <= la fecha de hoy
                                // validar que la aplicación actual no salte aplicaciones anteriores de cada vacuna...
                                /*** INFERIOR ***/
                                $limite_min = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addDays($ve->intervalo_inicio_dia)->addMonths($ve->intervalo_inicio_mes)->addYears($ve->intervalo_inicio_anio)->subDays($ve->margen_anticipacion);
                                /*** SUPERIOR ***/
                                $limite_max = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addDays($ve->intervalo_fin_dia)->addMonths($ve->intervalo_fin_mes)->addYears($ve->intervalo_fin_anio);
                                /*** IDEAL EDAD ***/
                                $edad_ideal = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addDays($ve->edad_ideal_dia)->addMonths($ve->edad_ideal_mes)->addYears($ve->edad_ideal_anio);
                                $msg_dosis_faltantes = '';
                                $falta_dosis = false;
                                if(count($ve->menores)>0){
                                    $menores_bien = true;
                                    $id_primera = NULL;
                                    $menor_mensaje = ' ';
                                    if(count($ve->menores)>0){
                                        foreach ($ve->menores as $key_men => $value_men) {
                                            $menor_mensaje = '<strong style="text-transform: uppercase;">'.$this->tipoAplicacion($value_men->tipo_aplicacion).'</strong>, ';
                                            if($request['fecha_aplicacion'.$value_men->id]!=NULL && $request['fecha_aplicacion'.$value_men->id]!="" && $request['fecha_aplicacion'.$value_men->id]!="__-__-____"){
                                                $apl = explode("-", $request['fecha_aplicacion'.$value_men->id]);
                                                if (Carbon::parse($apl[2]."-".$apl[1]."-".$apl[0]." 00:00:00","America/Mexico_City")) {
                                                } else { 
                                                    $menores_bien = false;                                               
                                                    break;
                                                }
                                            } else {
                                                $menores_bien = false;
                                                break;
                                            } 
                                        }
                                        $id_primera = $ve->menores[(count($ve->menores) - 1)]->id;
                                    }
                                    
                                    if($menores_bien){
                                        $primera = []; // de aquí podemos sacar si la primera es ideal o no
                                        foreach ($vacunas_esquemas as $key_primera => $value_primera) {
                                            if ($id_primera==$value_primera->id) {
                                                $primera = $value_primera;
                                                break;
                                            }
                                        }
                                        $apl_primera = explode("-",$request['fecha_aplicacion'.$primera->id]);
                                        $apl_primera = Carbon::parse($apl_primera[2]."-".$apl_primera[1]."-".$apl_primera[0]." 00:00:00","America/Mexico_City");
                                        $apl_menor = explode("-",$request['fecha_aplicacion'.$ve->menores[0]->id]);
                                        $apl_menor = Carbon::parse($apl_menor[2]."-".$apl_menor[1]."-".$apl_menor[0]." 00:00:00","America/Mexico_City");
                                        $ideal_primera = $f_nacimiento->addDays($primera->edad_ideal_dia)->addMonths($primera->edad_ideal_mes)->addYears($primera->edad_ideal_anio);

                                        if($apl_menor >= $f_aplicacion){
                                            $save_vac_esq = false;  
                                            $falta_dosis = true;
                                            $msg_dosis_faltantes.= 'Fecha de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' de '.$ve->clave.' debe ser mayor a la  '.$this->tipoAplicacion($primera->tipo_aplicacion);
                                        } else {
                                            if($f_aplicacion > $limite_max){
                                                $save_vac_esq = false;  
                                                $falta_dosis = true;
                                                $msg_dosis_faltantes.= 'Fecha de la '.$this->tipoAplicacion($ve->tipo_aplicacion).' de '.$ve->clave.' rebasa el limite de edad, que son '.$this->obtieneIntervaloCompleto($ve->intervalo_fin_anio,$ve->intervalo_fin_mes,$ve->intervalo_fin_dia).' Aplicar hasta '.$limite_max->toDateString();
                                            } 
                                        }
                                    } else {
                                        $save_vac_esq = false;  
                                        $falta_dosis = true;
                                        $msg_dosis_faltantes.= 'Debe agregar la fecha de aplicación de '.$menor_mensaje.' para de '.$ve->clave;
                                    }
                                } else { // Es primera dosis
                                    if($f_aplicacion > $limite_max){
                                        $save_vac_esq = false;  
                                        $falta_dosis = true;
                                        $msg_dosis_faltantes.= 'Fecha de la '.$this->tipoAplicacion($ve->tipo_aplicacion).' de '.$ve->clave.' rebasa el limite de edad, que son '.$this->obtieneIntervaloCompleto($ve->intervalo_fin_anio,$ve->intervalo_fin_mes,$ve->intervalo_fin_dia).' Aplicar hasta '.$limite_max->toDateString();
                                    }
                                }

                                if($falta_dosis)
                                    $msg_dosis.=$msg_dosis_faltantes;

                            } else {
                                $msg_dosis.='Fecha de aplicación de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' de '.$ve->clave.' debe ser mayor o igual a la fecha de nacimiento y menor igual a la fecha actual';
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
                                    $PersonaVacunaEsquema->created_at           = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
                                    if(!$PersonaVacunaEsquema->save()){
                                        $success = false;
                                        break;
                                    }                                                                           
                                }
                            }

                            if($success){
                                DB::commit();
                                $msgGeneral = 'Perfecto! se guardaron los datos';
                                $type       = 'flash_message_ok';
                                Session::flash($type, $msgGeneral);
                                if ($request->ajax()) {
                                    return response()->json(['estatus' => 'success', 'titulo' => 'Perfecto!', 'texto' => 'Se guardaron los datos']);
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
                        $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados. Recargue la página e intente de nuevo'.$e->getMessage();
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
	 * @api {get}   /persona/:id  4. Consultar Persona/Infante 
	 * @apiVersion  0.1.0
	 * @apiName     ShowPersona
	 * @apiGroup    Transaccion/Persona
	 *
	 *
	 * @apiSuccess  {View}       show       Vista de Persona(Se omite si la petición es ajax).
     * @apiSuccess  {Json}       data       Detalles de persona en formato JSON
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'servidor_id', 'incremento', 'clues_id', 'paises_id', 'entidades_federativas_nacimiento_id', 'entidades_federativas_domicilio_id', 'municipios_id', 'localidades_id', 'colonias_id', 'agebs_id', 'instituciones_id', 'codigos_censos_id', 'tipos_partos_id', 'folio_certificado_nacimiento', 'nombre', 'apellido_paterno', 'apellido_materno', 'curp', 'genero', 'fecha_nacimiento', 'descripcion_domicilio', 'calle', 'numero', 'codigo_postal', 'sector', 'manzana', 'telefono_casa', 'telefono_celular', 'tutor', 'fecha_nacimiento_tutor', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
    public function show($id)
    {
        if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            $person = Persona::findOrFail($id);
            if ($person) {
                if (Auth::user()->is('root|admin')) {
                    $persona = Persona::where('id', $id)->where('deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','colonia','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                } else { // Limitar por clues
                    $persona = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('personas.id', $id)->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','colonia','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                }

                $persona->aplicaciones = DB::table('personas_vacunas_esquemas AS pve')
                    ->select('pve.*','ve.vacunas_id','ve.tipo_aplicacion','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                    ->join('vacunas_esquemas AS ve','ve.id','=','pve.vacunas_esquemas_id')
                    ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                    ->where('pve.personas_id', $id)
                    ->where('pve.deleted_at', NULL)
                    ->where('ve.deleted_at', NULL)
                    ->where('v.deleted_at', NULL)                
                    ->orderBy('v_orden_esquema')
                    ->orderBy('intervalo_inicio_anio', 'ASC')
                    ->orderBy('intervalo_inicio_mes', 'ASC')
                    ->orderBy('intervalo_inicio_dia', 'ASC')
                    ->orderBy('fila', 'ASC')
                    ->orderBy('columna', 'ASC')
                    ->get(); 

                $esquema_date = explode('-', $persona->fecha_nacimiento);
                $bd = explode('-', $persona->fecha_nacimiento);
                $esquema = Esquema::with('vacunasEsquemas')->find($esquema_date[0]); 
                
                /**************/   
                $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                    ->select('ve.*','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                    ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                    ->where('ve.esquemas_id', $bd[0])
                    ->where('ve.deleted_at', NULL)
                    ->where('v.deleted_at', NULL)                
                    ->orderBy('v_orden_esquema')
                    ->orderBy('intervalo_inicio_anio', 'ASC')
                    ->orderBy('intervalo_inicio_mes', 'ASC')
                    ->orderBy('intervalo_inicio_dia', 'ASC')
                    ->orderBy('fila', 'ASC')
                    ->orderBy('columna', 'ASC')
                    ->get(); 
                
                $born_date = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City");
                $today     = Carbon::now("America/Mexico_City");

                $collect_esquema_detalle = collect();
                foreach ($esquema_detalle as $key => $value) {
                    $fecha = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City")->addYears($value->intervalo_inicio_anio)->addMonths($value->intervalo_inicio_mes)->addDays($value->intervalo_inicio_dia)->subDays($value->margen_anticipacion);
                    if($fecha<=$today){                    
                        $mayores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                        ->where('esquemas_id', $id)
                        ->where('id', '!=', $value->id)
                        ->where('deleted_at', NULL)
                        ->orderBy('intervalo_inicio_anio','ASC')
                        ->orderBy('intervalo_inicio_mes','ASC')
                        ->orderBy('intervalo_inicio_dia','ASC')->get();

                        $collect_mayores = collect();
                        $collect_menores = collect();
                        foreach ($mayores as $k_mayores => $v_mayores) { // dosis que son diferentes a la actual de menor a mayor
                            $fecha_mayores = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City")->addYears($v_mayores->intervalo_inicio_anio)->addMonths($v_mayores->intervalo_inicio_mes)->addDays($v_mayores->intervalo_inicio_dia)->subDays($v_mayores->margen_anticipacion);
                            if($fecha_mayores>$fecha){ 
                                $collect_mayores->push($v_mayores);
                            }
                        }

                        $value->mayores = $collect_mayores;

                        $menores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                        ->where('esquemas_id', $id)
                        ->where('id', '!=', $value->id)
                        ->where('deleted_at', NULL)
                        ->orderBy('intervalo_inicio_anio','DESC')
                        ->orderBy('intervalo_inicio_mes','DESC')
                        ->orderBy('intervalo_inicio_dia','DESC')->get();

                        foreach ($menores as $k_menores => $v_menores) {
                            $fecha_menores = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City")->addYears($v_menores->intervalo_inicio_anio)->addMonths($v_menores->intervalo_inicio_mes)->addDays($v_menores->intervalo_inicio_dia)->subDays($v_menores->margen_anticipacion);
                            if($fecha_menores<$fecha){ 
                                $collect_menores->push($v_menores);
                            }
                        }
                        
                        $value->menores = $collect_menores;
                        $collect_esquema_detalle->push($value);
                    }
                } // End foreach principal
                $persona->esquema_detalle = $collect_esquema_detalle;
                /**************/
                $ahora = Carbon::now("America/Mexico_City");
                $manana = Carbon::tomorrow("America/Mexico_City");
                $fecha_nacimiento = Carbon::parse($esquema_date[2]."-".$esquema_date[1]."-".$esquema_date[0]." 00:00:00","America/Mexico_City");
                $intervalo = $manana->diffInDays($fecha_nacimiento);
                $total_anios = $fecha_nacimiento->diffInYears($manana);
                $fecha_sin_anios = $fecha_nacimiento->addYears($total_anios);
                $total_meses = $fecha_sin_anios->diffInMonths($manana);
                $fecha_sin_meses = $fecha_sin_anios->addMonths($total_meses);
                $total_dias = $fecha_sin_meses->diffInDays($manana);
                $fecha_sin_dias = $fecha_sin_meses->addDays($total_dias);
                
                $letra_total_anios = 'Años';
                $letra_total_meses = 'Meses';
                $letra_total_dias  = 'Días';
                if($total_anios==1)
                    $letra_total_anios = 'Año';           
                if($total_meses==1)
                    $letra_total_meses = 'Mes';
                if($total_dias==1)
                    $letra_total_dias = 'Día';           
                $persona->edad = $total_anios.' '.$letra_total_anios.' '.$total_meses.' '.$letra_total_meses.' '.$total_dias.' '.$letra_total_dias;

                $fn_tutor = explode("-",$persona->fecha_nacimiento_tutor);
                if(array_key_exists(0, $fn_tutor) && array_key_exists(1, $fn_tutor) && array_key_exists(2, $fn_tutor)){
                    $persona->fecha_nacimiento_tutor = date($fn_tutor[2].'-'.$fn_tutor[1].'-'.$fn_tutor[0]);
                } else {
                    $persona->fecha_nacimiento_tutor = NULL;
                }
                
                $fn_nino = explode("-",$persona->fecha_nacimiento);
                $persona->fecha_nacimiento = date($fn_nino[2].'-'.$fn_nino[1].'-'.$fn_nino[0]);
            } else {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
            return view('persona.show')->with(['esquema' => $esquema, 'data' => $persona]);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
	 * @api {get}   /persona/:id/edit     5. Editar Persona
	 * @apiVersion  0.1.0
	 * @apiName     EditPersona
	 * @apiGroup    Transaccion/Persona
     * 
     * @apiParam    {Number}    id  Persona id único.
     * 
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'servidor_id', 'incremento', 'clues_id', 'paises_id', 'entidades_federativas_nacimiento_id', 'entidades_federativas_domicilio_id', 'municipios_id', 'localidades_id', 'colonias_id', 'agebs_id', 'instituciones_id', 'codigos_censos_id', 'tipos_partos_id', 'folio_certificado_nacimiento', 'nombre', 'apellido_paterno', 'apellido_materno', 'curp', 'genero', 'fecha_nacimiento', 'descripcion_domicilio', 'calle', 'numero', 'codigo_postal', 'sector', 'manzana', 'telefono_casa', 'telefono_celular', 'tutor', 'fecha_nacimiento_tutor', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error":   No se encuentra el recurso que esta buscando
	 *     }
	 */
    public function edit($id)
    {
        if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            $person = Persona::findOrFail($id);
            $id_persona = $id;
            if($person) {                    
                if (Auth::user()->is('root|admin')) {
                    //$clues = Clue::select('id','clues','nombre')->where('deleted_at',NULL)->where('estatus_id', 1)->get();
                    $municipios = Municipio::select('id','clave','nombre')->where('deleted_at',NULL)->get();
                    //$localidades = Localidad::select('id','clave','nombre')->where('deleted_at',NULL)->get();
                    //$colonias = Colonia::select('id','nombre','municipios_id')->where('deleted_at',NULL)->with('municipio')->get();
                    //$agebs = Ageb::select('id','municipios_id','localidades_id')->where('deleted_at',NULL)->with('municipio','localidad')->get();
                    $persona = Persona::where('id', $id_persona)->where('deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','colonia','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                } else {
                    //$localidades = collect();
                    //$colonias = collect();
                    //$agebs = collect();
                    //$clues = Clue::select('id','clues','nombre')->where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
                    $municipios = Municipio::select('id','clave','nombre')->where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->get();
                    /*foreach($municipios as $key=> $mpio){
                        $localidades_temp = Localidad::select('id','clave','nombre')->where('municipios_id', $mpio->id)->where('deleted_at',NULL)->get(); 
                        $colonias_temp = Colonia::select('id','nombre','municipios_id')->where('municipios_id', $mpio->id)->where('deleted_at',NULL)->with('municipio')->get();
                        foreach($localidades_temp as $id=> $item){
                            $localidades->push($item);
                        }
                        foreach($colonias_temp as $id=> $item){
                            $colonias->push($item);
                        }
                        $agebs_temp = Ageb::select('id','municipios_id','localidades_id')->where('municipios_id', $mpio->id)->where('deleted_at',NULL)->with('municipio','localidad')->get(); 
                        foreach($agebs_temp as $k=> $i){
                            $agebs->push($i);
                        }
                    }*/
                    $persona = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('personas.id', $id_persona)->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('clue','pais','entidadNacimiento','entidadDomicilio','municipio','localidad','colonia','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->first();
                }
                
                $estados = Entidad::select('id','clave','nombre')->where('deleted_at',NULL)->get();
                $instituciones = Institucion::select('id','clave','nombre')->where('deleted_at',NULL)->get();
                $codigos = CodigoCenso::select('id','clave','nombre')->where('deleted_at',NULL)->get();
                $tiposparto = TipoParto::select('id','clave','descripcion')->where('deleted_at',NULL)->get();

                /*$clue_selected = [];
                foreach ($clues as $cont=>$clue) {
                    $arrayclue[$clue->id] = $clue->clues .' - '.$clue->nombre;
                    if($cont==0)
                        $clue_selected = $clue;
                }*/
                
                foreach ($municipios as $municipio) {
                    $arraymunicipio[$municipio->id] = $municipio->clave .' - '.$municipio->nombre;
                }

                // $arrayageb[0] = 'Seleccionar AGEB';
                // foreach ($agebs as $ageb) {
                //     $arrayageb[$ageb->id] = substr($ageb->id, -4).' - '.$ageb->localidad->nombre.', '.$ageb->municipio->nombre;
                // }
                
                foreach ($estados as $estado) {
                    $arrayestado[$estado->id] = $estado->clave .' - '.$estado->nombre;
                }
                
                /*foreach ($localidades as $localidad) {
                    $arraylocalidad[$localidad->id] = $localidad->clave .' - '.$localidad->nombre;
                }

                $arraycolonia[0] = 'Sin colonia';
                foreach ($colonias as $colonia) {
                    $arraycolonia[$colonia->id] = $colonia->nombre.', '.$colonia->municipio->nombre;
                }*/			

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

                $esquema_date = explode('-', $persona->fecha_nacimiento);
                $esquema = Esquema::find($esquema_date[0]);
                
                $ahora = Carbon::now("America/Mexico_City");
                $manana = Carbon::tomorrow("America/Mexico_City");
                $fecha_nacimiento = Carbon::parse($esquema_date[2]."-".$esquema_date[1]."-".$esquema_date[0]." 00:00:00","America/Mexico_City");
                
                $total_anios = $fecha_nacimiento->diffInYears($manana);
                $fecha_sin_anios = $fecha_nacimiento->addYears($total_anios);
                $total_meses = $fecha_sin_anios->diffInMonths($manana);
                $fecha_sin_meses = $fecha_sin_anios->addMonths($total_meses);
                $total_dias = $fecha_sin_meses->diffInDays($manana);
                $fecha_sin_dias = $fecha_sin_meses->addDays($total_dias);
                
                $letra_total_anios = 'Años';
                $letra_total_meses = 'Meses';
                $letra_total_dias  = 'Días';
                if($total_anios==1)
                    $letra_total_anios = 'Año';           
                if($total_meses==1)
                    $letra_total_meses = 'Mes';
                if($total_dias==1)
                    $letra_total_dias = 'Día';           
                $persona->edad = $total_anios.' '.$letra_total_anios.' '.$total_meses.' '.$letra_total_meses.' '.$total_dias.' '.$letra_total_dias; 

                // $estados = Entidad::where('deleted_at',NULL)->get();
                // $paises = Pais::all();
                // $instituciones = Institucion::where('deleted_at',NULL)->get();
                // $codigos = CodigoCenso::where('deleted_at',NULL)->get();
                // $tiposparto = TipoParto::where('deleted_at',NULL)->get();

                // /*$clue_selected = [];
                // foreach ($clues as $cont=>$clue) {
                //     $arrayclue[$clue->id] = $clue->clues .' - '.$clue->nombre;
                // }*/
                
                // foreach ($municipios as $municipio) {
                //     $arraymunicipio[$municipio->id] = $municipio->clave .' - '.$municipio->nombre;
                // }

                // $arrayageb[0] = 'Seleccionar AGEB';
                // foreach ($agebs as $ageb) {
                //     $arrayageb[$ageb->id] = substr($ageb->id, -4).' - '.$ageb->localidad->nombre.', '.$ageb->municipio->nombre;
                // }
                
                // foreach ($estados as $estado) {
                //     $arrayestado[$estado->id] = $estado->clave .' - '.$estado->nombre;
                // }
                
                // foreach ($paises as $pais) {
                //     $arraypais[$pais->id] = $pais->claveA3 .' - '.$pais->descripcion;
                // }
                
                // /*foreach ($localidades as $localidad) {
                //     $arraylocalidad[$localidad->id] = $localidad->clave .' - '.$localidad->nombre;
                // }	*/		

                // $arraycodigo = array();
                // $arraycodigo[0] = 'Ningún código';
                // foreach ($codigos as $codigo) {
                //     $arraycodigo[$codigo->id] = $codigo->clave .' - '.$codigo->nombre;
                // }

                // foreach ($tiposparto as $tipoparto) {
                //     $arraytipoparto[$tipoparto->id] = $tipoparto->clave .' - '.$tipoparto->descripcion;
                // }

                // $arrayinstitucion = array();
                // $arrayinstitucion[0] = 'Ninguna afiliación';
                // foreach ($instituciones as $institucion) {
                //     $arrayinstitucion[$institucion->id] = $institucion->clave .' - '.$institucion->nombre;
                // }
            
                $fn_tutor = explode("-",$persona->fecha_nacimiento_tutor);
                if(array_key_exists(0, $fn_tutor) && array_key_exists(1, $fn_tutor) && array_key_exists(2, $fn_tutor)){
                    $persona->fecha_nacimiento_tutor = date($fn_tutor[2].'-'.$fn_tutor[1].'-'.$fn_tutor[0]);
                } else {
                    $persona->fecha_nacimiento_tutor = NULL;
                }
                $fn_nino = explode("-",$persona->fecha_nacimiento);
                $persona->fecha_nacimiento = date($fn_nino[2].'-'.$fn_nino[1].'-'.$fn_nino[0]);

                return view('persona.edit')->with(['esquema' => $esquema, 'data' => $persona, 'instituciones' => $arrayinstitucion, 'municipios' => $arraymunicipio, 'estados' => $arrayestado, 'codigos' => $arraycodigo, 'partos' => $arraytipoparto ]);
            } else {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
	 * @api {PUT}   /persona/update   6. Actualizar Persona 
	 * @apiVersion  0.1.0
	 * @apiName     UpdatePersona
	 * @apiGroup    Transaccion/Persona
     * 
     * @apiParam    {Number}       id                      Persona id único.
     * @apiParam    {Request}      request                 Cabeceras de la petición.
	 
	 * @apiSuccess  {String}        msgGeneral             Mensaje descriptivo de la operación realizada
     * @apiSuccess  {String}        type                   Tipos válidos: success, error, warning e info
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'msgGeneral'   :   'Operación realizada con éxito',
     *       'type'         :   'success'
	 *     }
	 *
     * @apiError PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'msgGeneral'   :  'Ocurrió un error al intentar guardar los datos enviados.',
     *       'type'         :  'error'
	 *     }
	 */
    public function update(Request $request, $id)
    {
        $msgGeneral = '';
        $type       = 'flash_message_info';
        $tipo_aplicacion=$this->tipo_aplicacion;

        $persona = Persona::findOrFail($id);
        $persona_id = $id; // ESTE SERÍA EL ID DE PERSONA SI LA CLUE NO CAMBIA
        $last_clue_id = $persona->clues_id; // ESTE SERÍA EL ID DE PERSONA SI LA CLUE NO CAMBIA
        $created_at = $persona->created_at;
        $persona_original_id = $id; // ESTE SERÍA EL ID DE PERSONA SI LA CLUE NO CAMBIA

        if (Auth::user()->can('update.personas') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'same'     => 'El campo :attribute debe ser igual al password',
                'sometimes'=> 'El campo :attribute debe sometimes',
                'confirmed'=> 'La :attribute debe ser confirmada',
                'date'     => 'La :attribute debe ser formato fecha',
                'before'   => 'La :attribute debe ser menor o igual a la fecha limite(fecha actual o fecha de nacimiento del niño)'
            ];

            $rules = [   
                'nombre'                                 => 'required|min:3|max:30|string',
                'paterno'                                => 'required|min:2|max:20|string',
                'materno'                                => 'required|min:2|max:20|string',
                'fecha_nacimiento'                       => 'required|date|before:tomorrow',
                'curp'                                   => 'required|min:17|max:18', 
                'genero'                                 => 'required|in:F,M',
                'entidad_federativa_nacimiento_id'       => 'required|min:1|numeric',
                'clue_id'                                => 'required|min:1|numeric',
                'tipo_parto_id'                          => 'required|min:1|numeric',
                'municipio_id'                           => 'required|min:1|numeric',
                'localidad_id'                           => 'required|min:1|numeric',
                'calle'                                  => 'required|min:1|max:100',
                'numero'                                 => 'required|min:1|max:5',
            ];

            $this->validate($request, $rules, $messages);
            $request->clue_id = (int) $request->clue_id;
            $clue = Clue::find($request->clue_id);
            
            if($last_clue_id!=$request->clue_id){ // SI LAS CLUES NO COINCIDEN
                $persona_id = '';
                $incremento = 0;
                $persona_increment = Persona::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();

                if(count($persona_increment)>0){
                    $incremento = $persona_increment[0]->incremento + 1; 
                } else {                 
                    $incremento = 1;                             
                }
                $persona_id            = $clue->servidor.''.$incremento;
                $persona               = new Persona;
                $persona->id           = $persona_id;
                $persona->servidor_id  = $clue->servidor;                
                $persona->incremento   = $incremento;
            }
            $new_persona = $persona->id;

            if($request->institucion_id==0)
                $request->institucion_id = NULL;
            if($request->codigo_id==0)
                $request->codigo_id = NULL;
            if($request->ageb_id==0)
                $request->ageb_id = NULL;
            if($request->colonias_id==0)
                $request->colonias_id = NULL;

            $fecha_nacimiento = explode('-',$request->fecha_nacimiento);
            $request->fecha_nacimiento = $fecha_nacimiento[2].'-'.$fecha_nacimiento[1].'-'.$fecha_nacimiento[0]; // formato valido para guardar fecha n

            $fecha_nacimiento_tutor = explode('-',$request->fecha_nacimiento_tutor);
            if(array_key_exists(0, $fecha_nacimiento_tutor) && array_key_exists(1, $fecha_nacimiento_tutor) && array_key_exists(2, $fecha_nacimiento_tutor)){
                $request->fecha_nacimiento_tutor = $fecha_nacimiento_tutor[2].'-'.$fecha_nacimiento_tutor[1].'-'.$fecha_nacimiento_tutor[0]; // formato valido para guardar fecha n t
            } else {
                $request->fecha_nacimiento_tutor = NULL;
            }

            $persona->nombre                              = strtoupper($request->nombre);
            $persona->apellido_paterno                    = strtoupper($request->paterno);
            $persona->apellido_materno                    = strtoupper($request->materno);
            $persona->fecha_nacimiento                    = $request->fecha_nacimiento;
            $persona->curp                                = strtoupper($request->curp);
            $persona->genero                              = $request->genero;           
            $persona->clues_id                            = $request->clue_id;
            $persona->tipos_partos_id                     = $request->tipo_parto_id;
            $persona->entidades_federativas_nacimiento_id = $request->entidad_federativa_nacimiento_id;
            $persona->entidades_federativas_domicilio_id  = $request->entidad_federativa_nacimiento_id;
            $persona->municipios_id                       = $request->municipio_id;
            $persona->localidades_id                      = $request->localidad_id;
            $persona->agebs_id                            = $request->ageb_id;
            $persona->colonias_id                         = $request->colonias_id;
            $persona->paises_id                           = 155;
            $persona->descripcion_domicilio               = $request->descripcion_domicilio;
            $persona->calle                               = $request->calle;
            $persona->numero                              = $request->numero;
            $persona->manzana                             = $request->manzana;
            $persona->codigo_postal                       = $request->codigo_postal;
            $persona->sector                              = $request->sector;
            $persona->codigos_censos_id                   = $request->codigo_id;
            $persona->instituciones_id                    = $request->institucion_id;
            $persona->tutor                               = strtoupper($request->tutor);
            $persona->fecha_nacimiento_tutor              = $request->fecha_nacimiento_tutor;
            $persona->usuario_id                          = Auth::user()->email;
            $persona->created_at                          = $created_at;
            $persona->updated_at                          = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
           
            $vacunas_esquemas = DB::table('vacunas_esquemas AS ve')
                ->select('ve.*','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', $fecha_nacimiento[2])
                ->where('ve.deleted_at', NULL)
                ->where('v.deleted_at', NULL)                
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio_anio', 'ASC')
                ->orderBy('intervalo_inicio_mes', 'ASC')
                ->orderBy('intervalo_inicio_dia', 'ASC')
                ->orderBy('fila', 'ASC')
                ->orderBy('columna', 'ASC')
                ->get(); 
                
            $born_date = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City");
            $today     = Carbon::now("America/Mexico_City");

            $collect_esquema_detalle = collect();
            foreach ($vacunas_esquemas as $key => $value) {
                $fecha = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addYears($value->intervalo_inicio_anio)->addMonths($value->intervalo_inicio_mes)->addDays($value->intervalo_inicio_dia)->subDays($value->margen_anticipacion);
                if($fecha<=$today){                    
                    $mayores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                    ->where('esquemas_id', $fecha_nacimiento[2])
                    ->where('id', '!=', $value->id)
                    ->where('deleted_at', NULL)
                    ->orderBy('intervalo_inicio_anio','ASC')
                    ->orderBy('intervalo_inicio_mes','ASC')
                    ->orderBy('intervalo_inicio_dia','ASC')->get();

                    $collect_mayores = collect();
                    $collect_menores = collect();
                    foreach ($mayores as $k_mayores => $v_mayores) { // dosis que son diferentes a la actual de menor a mayor
                        $fecha_mayores = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addYears($v_mayores->intervalo_inicio_anio)->addMonths($v_mayores->intervalo_inicio_mes)->addDays($v_mayores->intervalo_inicio_dia)->subDays($v_mayores->margen_anticipacion);
                        if($fecha_mayores>$fecha){ 
                            $collect_mayores->push($v_mayores);
                        }
                    }

                    $value->mayores = $collect_mayores;

                    $menores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                    ->where('esquemas_id', $fecha_nacimiento[2])
                    ->where('id', '!=', $value->id)
                    ->where('deleted_at', NULL)
                    ->orderBy('intervalo_inicio_anio','DESC')
                    ->orderBy('intervalo_inicio_mes','DESC')
                    ->orderBy('intervalo_inicio_dia','DESC')->get();

                    foreach ($menores as $k_menores => $v_menores) {
                        $fecha_menores = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addYears($v_menores->intervalo_inicio_anio)->addMonths($v_menores->intervalo_inicio_mes)->addDays($v_menores->intervalo_inicio_dia)->subDays($v_menores->margen_anticipacion);
                        if($fecha_menores<$fecha){ 
                            $collect_menores->push($v_menores);
                        }
                    }
                      
                    $value->menores = $collect_menores;
                    $collect_esquema_detalle->push($value);
                }
            } // End foreach principal
            $vacunas_esquemas = $collect_esquema_detalle;

            $save_vac_esq = true;            
            $msg_dosis = '';
            foreach($vacunas_esquemas as $key=>$ve){                     
                if($request['fecha_aplicacion'.$ve->id]!=NULL && $request['fecha_aplicacion'.$ve->id]!="" && $request['fecha_aplicacion'.$ve->id]!="__-__-____"){ // Si trae algún valor la variable
                    $fecha_apli = explode('-',$request['fecha_aplicacion'.$ve->id]);
                    if(array_key_exists(0, $fecha_apli) && array_key_exists(1, $fecha_apli) && array_key_exists(2, $fecha_apli)){ // Si cumple con día, mes y año
                        $temp_fecha_aplicacion = $fecha_apli[2].'-'.$fecha_apli[1].'-'.$fecha_apli[0];
                        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$temp_fecha_aplicacion)) { // valida formato de fecha
                            
                            /*** AHORA  ***/
                            $f_ahora = Carbon::now("America/Mexico_City");
                            /*** APLICACIÓN ***/
                            $f_aplicacion = Carbon::parse($fecha_apli[2]."-".$fecha_apli[1]."-".$fecha_apli[0]." 00:00:00","America/Mexico_City");
                            /*** NACIMIENTO ***/
                            $f_nacimiento   = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City");
                            
                            if($f_aplicacion>=$f_nacimiento && $f_aplicacion<=$f_ahora) { // Si la fecha de aplicación >= fecha de nacimiento y <= la fecha de hoy
                                // validar que la aplicación actual no salte aplicaciones anteriores de cada vacuna...
                                /*** INFERIOR ***/
                                $limite_min = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addDays($ve->intervalo_inicio_dia)->addMonths($ve->intervalo_inicio_mes)->addYears($ve->intervalo_inicio_anio)->subDays($ve->margen_anticipacion);
                                /*** SUPERIOR ***/
                                $limite_max = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addDays($ve->intervalo_fin_dia)->addMonths($ve->intervalo_fin_mes)->addYears($ve->intervalo_fin_anio);
                                /*** IDEAL EDAD ***/
                                $edad_ideal = Carbon::parse($fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0]." 00:00:00","America/Mexico_City")->addDays($ve->edad_ideal_dia)->addMonths($ve->edad_ideal_mes)->addYears($ve->edad_ideal_anio);
                                $msg_dosis_faltantes = '';
                                $falta_dosis = false;
                                if(count($ve->menores)>0){
                                    $menores_bien = true;
                                    $id_primera = NULL;
                                    $menor_mensaje = ' ';
                                    if(count($ve->menores)>0){
                                        foreach ($ve->menores as $key_men => $value_men) {
                                            $menor_mensaje = '<strong style="text-transform: uppercase;">'.$this->tipoAplicacion($value_men->tipo_aplicacion).'</strong>, ';
                                            if($request['fecha_aplicacion'.$value_men->id]!=NULL && $request['fecha_aplicacion'.$value_men->id]!="" && $request['fecha_aplicacion'.$value_men->id]!="__-__-____"){
                                                $apl = explode("-", $request['fecha_aplicacion'.$value_men->id]);
                                                if (Carbon::parse($apl[2]."-".$apl[1]."-".$apl[0]." 00:00:00","America/Mexico_City")) {
                                                } else { 
                                                    $menores_bien = false;                                               
                                                    break;
                                                }
                                            } else {
                                                $menores_bien = false;
                                                break;
                                            } 
                                        }
                                        $id_primera = $ve->menores[(count($ve->menores) - 1)]->id;
                                    }
                                    
                                    if($menores_bien){
                                        $primera = []; // de aquí podemos sacar si la primera es ideal o no
                                        foreach ($vacunas_esquemas as $key_primera => $value_primera) {
                                            if ($id_primera==$value_primera->id) {
                                                $primera = $value_primera;
                                                break;
                                            }
                                        }
                                        $apl_primera = explode("-",$request['fecha_aplicacion'.$primera->id]);
                                        $apl_primera = Carbon::parse($apl_primera[2]."-".$apl_primera[1]."-".$apl_primera[0]." 00:00:00","America/Mexico_City");
                                        $apl_menor = explode("-",$request['fecha_aplicacion'.$ve->menores[0]->id]);
                                        $apl_menor = Carbon::parse($apl_menor[2]."-".$apl_menor[1]."-".$apl_menor[0]." 00:00:00","America/Mexico_City");
                                        $ideal_primera = $f_nacimiento->addDays($primera->edad_ideal_dia)->addMonths($primera->edad_ideal_mes)->addYears($primera->edad_ideal_anio);

                                        if($apl_menor >= $f_aplicacion){
                                            $save_vac_esq = false;  
                                            $falta_dosis = true;
                                            $msg_dosis_faltantes.= 'Fecha de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' de '.$ve->clave.' debe ser mayor a la  '.$this->tipoAplicacion($primera->tipo_aplicacion);
                                        } else {
                                            if($f_aplicacion > $limite_max){
                                                $save_vac_esq = false;  
                                                $falta_dosis = true;
                                                $msg_dosis_faltantes.= 'Fecha de la '.$this->tipoAplicacion($ve->tipo_aplicacion).' de '.$ve->clave.' rebasa el limite de edad, que son '.$this->obtieneIntervaloCompleto($ve->intervalo_fin_anio,$ve->intervalo_fin_mes,$ve->intervalo_fin_dia).' Aplicar hasta '.$limite_max->toDateString();
                                            } 
                                        }
                                    } else {
                                        $save_vac_esq = false;  
                                        $falta_dosis = true;
                                        $msg_dosis_faltantes.= 'Debe agregar la fecha de aplicación de '.$menor_mensaje.' para de '.$ve->clave;
                                    }
                                } else { // Es primera dosis
                                    if($f_aplicacion > $limite_max){
                                        $save_vac_esq = false;  
                                        $falta_dosis = true;
                                        $msg_dosis_faltantes.= 'Fecha de la '.$this->tipoAplicacion($ve->tipo_aplicacion).' de '.$ve->clave.' rebasa el limite de edad, que son '.$this->obtieneIntervaloCompleto($ve->intervalo_fin_anio,$ve->intervalo_fin_mes,$ve->intervalo_fin_dia).' Aplicar hasta '.$limite_max->toDateString();
                                    }
                                }

                                if($falta_dosis)
                                    $msg_dosis.=$msg_dosis_faltantes;

                            } else {
                                $msg_dosis.='Fecha de aplicación de la '.$tipo_aplicacion[$ve->tipo_aplicacion].' de '.$ve->clave.' debe ser mayor o igual a la fecha de nacimiento y menor igual a la fecha actual';
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
            
            $repeat_curp = Persona::where('curp', $request->curp)->where('id','!=',$persona_original_id)->where('deleted_at', NULL)->get();
            if($save_vac_esq==true) {
                if(count($repeat_curp)<=0) {
                    try {       
                        DB::beginTransaction();   
                           
                        if($persona->save()) {  
                            if($last_clue_id!=$request->clue_id){ // SI LAS CLUES NO COINCIDEN
                                $updates = DB::table('personas')
                                    ->where('id', '=', $persona_original_id)
                                    ->update(['deleted_at' => Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s'), 'usuario_id' => Auth::user()->email]);
                                }
                            if(PersonaVacunaEsquema::where('personas_id', '=', $persona_original_id)->count()>0){ 
                                $delete_vacunas_esquemas = DB::table('personas_vacunas_esquemas')
                                    ->where('personas_id', '=', $persona_original_id)
                                    ->delete();
                            }  
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
                                    $PersonaVacunaEsquema->updated_at           = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
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
                                Session::flash($type, $msgGeneral);
                                return redirect('persona/'.$new_persona.'/edit');
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
                        $msgGeneral = 'Ocurrió un error al intentar modificar los datos enviados. Recargue la página e intente de nuevo.';
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
	 * @api {DELETE}    /persona/:id  7. Borrar Persona 
	 * @apiVersion  0.1.0
	 * @apiName     DestroyPersona
	 * @apiGroup    Transaccion/Persona
     * 
     * @apiParam    {Number}       id              Persona id único.
     * @apiParam    {Request}      request         Cabeceras de la petición.
	 
	 * @apiSuccess  {String}       msgGeneral      Mensaje descriptivo de la operación realizada
     * @apiSuccess  {String}       type            Tipos válidos: success, error, warning e info
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'code'    : 1,
     *       'title'   : 'Información',
     *       'text'    : 'Se borraron los datos',
     *       'type'    : 'success',
     *       'styling' : 'bootstrap3'
	 *     }
	 *
     * @apiError PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'code'    : 1,
     *       'title'   : 'Información',
     *       'text'    : 'Ocurrió un error al intentar eliminar los datos.',
     *       'type'    : 'error',
     *       'styling' : 'bootstrap3'
	 *     }
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
                        ->update(['deleted_at' => Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s'), 'usuario_id' => Auth::user()->email]);
                        if(PersonaVacunaEsquema::where('personas_id', '=', $id)->count()>0){
                            $updates_pve = DB::table('personas_vacunas_esquemas')
                                ->where('personas_id', '=', $id)
                                ->update(['deleted_at' => Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s'), 'usuario_id' => Auth::user()->email]);
                        }                               

                    if ($updates>=0) {
                        DB::commit();
                        $msgGeneral = 'Se borraron los datos de '.$persona->nombre.' '.$persona->apellido_paterno.' '.$persona->apellido_materno.'';
                        $type2      = 'success';
                    } else {    
                        DB::rollback();
                        $msgGeneral = 'NO se borraron los datos de '.$persona->nombre.' '.$persona->apellido_paterno.' '.$persona->apellido_materno.'';
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
                'title'   => 'Información',
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
