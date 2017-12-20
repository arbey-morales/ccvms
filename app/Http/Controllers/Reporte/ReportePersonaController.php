<?php

namespace App\Http\Controllers\Reporte;

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

class ReportePersonaController extends Controller
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
     * @param   int    Clave entera del tipo de aplicación
     * @return string  Cadena descriptiva del tipo de aplicación
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
     * Display a listing of the resource.
     * @param   string  q               Cadena de texto para búsqueda en personas. Se espera nombre de infante/tutor o CURP
     * @param   int     todo            Determina si la busqueda es de todo lo registrado, valor esperado: 1
     * @return \Illuminate\Http\Response
     */
    public function buscar(Request $request)
    {
        $parametros = Input::only(['q','todo']);
        $text = '';

        $today = Carbon::today("America/Mexico_City");
		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) { 
            $anios_atras = Carbon::today("America/Mexico_City")->subYears(10)->format('Y-m-d'); 
            $personas = DB::table('personas')
                ->select('personas.*','clu.clues AS clu_clues','clu.jurisdicciones_id AS clu_jurisdiccion_id','clu.nombre AS clu_nombre','col.nombre AS col_nombre','loc.nombre AS loc_nombre','mun.nombre AS mun_nombre','tp.clave AS tp_clave','tp.descripcion AS tp_descripcion');  
            
            if (Auth::user()->is('root|admin')) { } else {            
                $personas = $personas->where('clu.jurisdicciones_id', Auth::user()->idJurisdiccion);
            }

            if ($parametros['todo']==NULL){ // La búsqueda usa filtros
                $text = 'Nombre del infante/tutor o CURP: '.$parametros['q'];
                $personas = $personas->where(function($query) use ($parametros) {
                    $query->where('personas.curp','LIKE',"%".$parametros['q']."%")
                    ->orWhere('personas.tutor','LIKE',"%".$parametros['q']."%")
                    ->orWhere(\DB::raw("CONCAT(personas.nombre,' ',personas.apellido_paterno,' ',personas.apellido_materno)"),'LIKE',"%".$parametros['q']."%");
                });
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

            $usuario = User::with('jurisdiccion')->find(Auth::user()->id);
            return response()->json(['text' => $text, 'data' => $data, 'user' => $usuario]);
        } else {
            return response()->json([ 'data' => [$data]]);
        }
    }

    /**
     * Display a listing of the resource.
     * @param   int     jurisdicciones_id   Id del lista de jurisdicciones solo para root|admin
     * @param   int     municipios_id       Id del lista de municipios  
     * @param   int     localidaddes_id     Id del lista de localidades 
     * @param   int     clues_id            Id del lista de unidades de salud 
     * @param   int     agebs_id            Id del lista de agebs 
     * @param   string  sector              cadena de texto sector  
     * @param   string  manzana             cadena de texto manzana     
     * @return \Illuminate\Http\Response
     */
    public function seguimiento(Request $request)
    {
        $parametros = Input::only(['jurisdicciones_id','municipios_id','localidades_id','clues_id','agebs_id','sector','manzana']);
        $ta_abreviatura = $this->ta_abreviatura;

        $today = Carbon::today("America/Mexico_City");
		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) { 
            $anios_atras = Carbon::today("America/Mexico_City")->subYears(10)->format('Y-m-d');
            $personas = DB::table('personas')
                ->select('personas.*','clu.clues AS clu_clues','clu.jurisdicciones_id AS clu_jurisdiccion_id','clu.nombre AS clu_nombre','col.nombre AS col_nombre','loc.nombre AS loc_nombre','mun.nombre AS mun_nombre','tp.clave AS tp_clave','tp.descripcion AS tp_descripcion');  

            if (Auth::user()->is('captura'))          
                $personas = $personas->where('clu.jurisdicciones_id', Auth::user()->idJurisdiccion);
            
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
            
            $usuario = User::with('jurisdiccion')->find(Auth::user()->id);
            return response()->json(['text' => $text, 'data' => $data, 'user' => $usuario]);
        } else {
            return response()->json([ 'data' => [$data]]);
        }
    }

    public function actividad(Request $request)
    {
        $parametros = Input::only(['q','jurisdicciones_id','municipios_id','localidades_id','clues_id','agebs_id','sector','manzana','filtro','todo','rep']);
        $text = '';        

        $today = Carbon::today("America/Mexico_City");
		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) { 
            $fila = [  
                        ["edad"=>"0 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>0,"is"=>0,"fs"=>0,"fm"=>1],
                        ["edad"=>"1 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>1,"is"=>1,"fs"=>1,"fm"=>2],
                        ["edad"=>"2 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>2,"is"=>2,"fs"=>2,"fm"=>3],
                        ["edad"=>"3 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>3,"is"=>3,"fs"=>3,"fm"=>4],
                        ["edad"=>"4 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>4,"is"=>4,"fs"=>4,"fm"=>5],
                        ["edad"=>"5 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>5,"is"=>5,"fs"=>5,"fm"=>6],
                        ["edad"=>"6 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>6,"is"=>6,"fs"=>6,"fm"=>7],
                        ["edad"=>"7 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>7,"is"=>7,"fs"=>7,"fm"=>8],
                        ["edad"=>"8 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>8,"is"=>8,"fs"=>8,"fm"=>9],
                        ["edad"=>"9 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>9,"is"=>9,"fs"=>9,"fm"=>10],
                        ["edad"=>"10 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>10,"is"=>10,"fs"=>10,"fm"=>11],
                        ["edad"=>"11 mes","entre"=>11,"pom"=>0,"cuenta"=>1,"im"=>11,"is"=>11,"fs"=>11,"fm"=>12],
                        /*12*/["edad"=>"< 1 año","entre"=>0,"pom"=>0,"cuenta"=>0,"im"=>0,"is"=>0,"fs"=>11,"fm"=>12],
                        ["edad"=>"12-17 meses","entre"=>2,"pom"=>1,"cuenta"=>1,"im"=>12,"is"=>13,"fs"=>13,"fm"=>18],
                        ["edad"=>"18-24 meses","entre"=>2,"pom"=>1,"cuenta"=>1,"im"=>18,"is"=>14,"fs"=>14,"fm"=>24],
                        ["edad"=>"1 año","entre"=>0,"pom"=>1,"cuenta"=>1,"im"=>12,"is"=>13,"fs"=>14,"fm"=>24],
                        ["edad"=>"2 años","entre"=>0,"pom"=>2,"cuenta"=>1,"im"=>24,"is"=>16,"fs"=>16,"fm"=>36],
                        ["edad"=>"3 años","entre"=>0,"pom"=>3,"cuenta"=>1,"im"=>36,"is"=>17,"fs"=>17,"fm"=>48],
                        ["edad"=>"4 años","entre"=>0,"pom"=>4,"cuenta"=>1,"im"=>48,"is"=>18,"fs"=>18,"fm"=>60],
                        /*19*/["edad"=>"1-4 años","entre"=>0,"pom"=>[1,2,3,4],"cuenta"=>0,"im"=>12,"is"=>16,"fs"=>18,"fm"=>60],
                        ["edad"=>"5 años","entre"=>0,"pom"=>5,"cuenta"=>1,"im"=>60,"is"=>20,"fs"=>20,"fm"=>72],
                        ["edad"=>"6 años","entre"=>0,"pom"=>6,"cuenta"=>1,"im"=>72,"is"=>21,"fs"=>21,"fm"=>84],
                        ["edad"=>"7 años","entre"=>0,"pom"=>7,"cuenta"=>1,"im"=>84,"is"=>22,"fs"=>22,"fm"=>96],
                        /*23*/["edad"=>"5-7 años","entre"=>0,"pom"=>[5,6,7],"cuenta"=>0,"im"=>60,"is"=>20,"fs"=>22,"fm"=>96],
                        /*24*/["edad"=>"0-7 años","entre"=>0,"pom"=>[0,1,2,3,4,5,6,7],"cuenta"=>0,"im"=>0,"is"=>0,"fs"=>22,"fm"=>96]
                    ];

            $data = collect();  

                if(Auth::user()->is('captura')){
                    $parametros['jurisdicciones_id'] = Auth::user()->idJurisdiccion;
                    $pom = PoblacionConapo::where('anio', Carbon::now()->format('Y'))->where('municipios_id',$parametros['municipios_id'])->where('deleted_at', NULL )->take(1)->get();
                    $pom = $pom->toArray();
                    if(count($pom)<0){                    
                        $pom = $pom[0];                    
                        for ($i=0; $i < 10; $i++) { 
                            $pom['hombres_'.$i] = 0;
                            $pom['mujeres_'.$i] = 0;
                        }
                    } else {
                        $pom = $pom[0];
                    }
                } else {
                    $pom = [];
                    if(isset($parametros['jurisdicciones_id'])){
                        $municipios = Municipio::select('id','jurisdicciones_id')->where('jurisdicciones_id',$parametros['jurisdicciones_id'])->where('deleted_at', NULL )->get();
                        foreach ($municipios as $key => $value) {
                            $pom_temp = PoblacionConapo::where('anio', Carbon::now()->format('Y'))->where('municipios_id',$value->id)->where('deleted_at', NULL )->take(1)->get();
                            $pom_temp = $pom_temp->toArray();
                            if(count($pom_temp)<=0){                    
                                $pom_temp = [];                    
                                for ($i=0; $i < 10; $i++) { 
                                    $pom_temp['hombres_'.$i] = 0;
                                    $pom_temp['mujeres_'.$i] = 0;
                                }
                            } else {
                                $pom_temp = $pom_temp[0];
                            }
                            
                            if($key==0){
                                $pom = $pom_temp;
                            } else { // su
                                for ($i=0; $i < 10; $i++) { 
                                    $pom['hombres_'.$i] = $pom['hombres_'.$i] + $pom_temp['hombres_'.$i];
                                    $pom['mujeres_'.$i] = $pom['mujeres_'.$i] + $pom_temp['mujeres_'.$i];
                                }
                            }                            
                        }
                    } else {                   
                        for ($i=0; $i < 10; $i++) { 
                            $pom['hombres_'.$i] = 0;
                            $pom['mujeres_'.$i] = 0;
                        }
                    }
                }    

                /**
                 * Esquema / Por vacuna / Y orden
                 */
                $va_es = DB::table('vacunas_esquemas AS ve')
                ->select('ve.vacunas_id','v.clave')
                ->leftJoin('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', 2017)
                ->where('ve.deleted_at', NULL) 
                ->groupBy('ve.vacunas_id')               
                ->orderBy('v.orden_esquema')                
                ->get();                
                foreach ($va_es as $key => $value) 
                {  // Forma la estructura que debe tener cada elemento del data                  
                    $aplicacion = [];
                    $esq = DB::table('vacunas_esquemas AS ve')
                    ->select('ve.tipo_aplicacion')
                    ->leftJoin('vacunas AS v','v.id','=','ve.vacunas_id')
                    ->where('ve.esquemas_id', 2017)
                    ->where('ve.vacunas_id',$value->vacunas_id)
                    ->where('ve.deleted_at', NULL)                
                    ->orderBy('v.orden_esquema')
                    ->orderBy('ve.tipo_aplicacion')                
                    ->get(); 
                    foreach ($esq as $plus => $v) {
                        array_push($aplicacion,$v->tipo_aplicacion);
                    }
                    $value->aplicacion  = $aplicacion;
                }
                
                foreach ($fila as $key => $value) { // Agrega todas posiciones del reporte               
                    $data->push(["parametros"=> $value, "poblacion"=>[ "oficial"=>0, "nominal"=>0, "conc"=>0], "dosis"=>$va_es, "esquema_completo"=>0]);
                }
                
                $data = $data->toArray();
                
                foreach ($data as $key => $value) 
                { // Llenado de datos de la colección                                     
                    if(is_array($value['parametros']['pom'])){ // sumar las poblaciones y dividir
                        $tpo = 0;
                        foreach ($value['parametros']['pom'] as $kp) {
                            $tpo+=$pom['hombres_'.$kp]+$pom['mujeres_'.$kp];
                        }
                        $data[$key]['poblacion']['oficial'] = $tpo;
                        if($value['parametros']['entre']>0)
                            $data[$key]['poblacion']['oficial'] = $tpo / $value['parametros']['entre'];
                    } else {
                        $tpo = ($pom['hombres_'.$value['parametros']['pom']]+$pom['mujeres_'.$value['parametros']['pom']]);
                        $data[$key]['poblacion']['oficial'] = $tpo;
                        if($value['parametros']['entre']>0)
                            $data[$key]['poblacion']['oficial'] = $tpo / $value['parametros']['entre'];
                    }
                    //$data[$key]['poblacion']['conc']= 58;
                    $im = Carbon::today("America/Mexico_City")->subMonths($value['parametros']['im'])->format("Y-m-d");
                    $fm = Carbon::today("America/Mexico_City")->subMonths($value['parametros']['fm'])->format("Y-m-d");
                                 

                    $pob_nom = DB::table('personas as p')
                    ->leftJoin('clues as c','c.id','=','p.clues_id')
                    ->where('c.jurisdicciones_id',$parametros['jurisdicciones_id'])
                    ->where('p.fecha_nacimiento','<=',$im)
                    ->where('p.fecha_nacimiento','>',$fm)
                    ->where('p.deleted_at', NULL); 
                    if (isset($parametros['municipios_id']) && $parametros['municipios_id']!=0) {
                        $pob_nom = $pob_nom->where('p.municipios_id',$parametros['municipios_id']);
                    }
                    if (isset($parametros['clues_id']) && $parametros['clues_id']!=0) {
                        $pob_nom = $pob_nom->where('p.clues_id',$parametros['clues_id']);
                    }
                    if (isset($parametros['agebs_id']) && $parametros['agebs_id']!=0) {
                        $pob_nom = $pob_nom->where('p.agebs_id',$parametros['agebs_id']);
                    }
                    if (isset($parametros['localidades_id']) && $parametros['localidades_id']!=0) {
                        $pob_nom = $pob_nom->where('p.localidades_id',$parametros['localidades_id']);
                    }
                    if (isset($parametros['colonias_id']) && $parametros['colonias_id']!=0) {
                        $pob_nom = $pob_nom->where('p.colonias_id',$parametros['colonias_id']);
                    }
                    if (isset($parametros['sector']) && trim($parametros['sector'])!="" && trim($parametros['sector'])!=NULL) {
                        $pob_nom = $pob_nom->where('p.sector',$parametros['sector']);
                    }
                    if (isset($parametros['manzana']) && trim($parametros['manzana'])!="" && trim($parametros['manzana'])!=NULL) {
                        $pob_nom = $pob_nom->where('p.manzana',$parametros['manzana']);
                    }   

                    $pob_nom_esq = $pob_nom;
                    $data[$key]['poblacion']['nominal'] = $pob_nom->count();
                    $esquema_completo = 0;                    
                    $apk = [];
                    $ninos = [];

                    foreach ($value['dosis'] as $k => $dosis) {
                        foreach ($dosis->aplicacion as $ka => $aplicacion) {
                            $pob_real = DB::table('personas AS p')
                            ->select('p.id','p.fecha_nacimiento')
                            ->leftJoin('personas_vacunas_esquemas as pve','p.id','=','pve.personas_id')
                            ->leftJoin('vacunas_esquemas as ve','ve.id','=','pve.vacunas_esquemas_id')
                            ->leftJoin('clues as c','c.id','=','p.clues_id')
                            ->where('c.jurisdicciones_id',$parametros['jurisdicciones_id'])
                            ->where('p.fecha_nacimiento','<=',$im)
                            ->where('p.fecha_nacimiento','>',$fm)
                            ->where('ve.vacunas_id', $dosis->vacunas_id)
                            ->where('ve.tipo_aplicacion', $aplicacion)
                            ->where('pve.deleted_at', NULL)
                            ->where('p.deleted_at', NULL);
                                                        

                            if (isset($parametros['municipios_id']) && $parametros['municipios_id']!=0) {
                                $pob_real = $pob_real->where('p.municipios_id',$parametros['municipios_id']);
                            }
                            if (isset($parametros['clues_id']) && $parametros['clues_id']!=0) {
                                $pob_real = $pob_real->where('p.clues_id',$parametros['clues_id']);
                            }
                            if (isset($parametros['agebs_id']) && $parametros['agebs_id']!=0) {
                                $pob_real = $pob_real->where('p.agebs_id',$parametros['agebs_id']);
                            }
                            if (isset($parametros['localidades_id']) && $parametros['localidades_id']!=0) {
                                $pob_real = $pob_real->where('p.localidades_id',$parametros['localidades_id']);
                            }
                            if (isset($parametros['colonias_id']) && $parametros['colonias_id']!=0) {
                                $pob_real = $pob_real->where('p.colonias_id',$parametros['colonias_id']);
                            }
                            if (isset($parametros['sector']) && trim($parametros['sector'])!="" && trim($parametros['sector'])!=NULL) {
                                $pob_real = $pob_real->where('p.sector',$parametros['sector']);
                            }
                            if (isset($parametros['manzana']) && trim($parametros['manzana'])!="" && trim($parametros['manzana'])!=NULL) {
                                $pob_real = $pob_real->where('p.manzana',$parametros['manzana']);
                            }

                            $other = $pob_real;
                            $pob_real = $pob_real->count();
                            if($pob_real>0){
                                array_push($apk, $pob_real); 
                                
                                $query_debe_tener = DB::table('vacunas_esquemas')
                                ->select('id','edad_ideal_anio','edad_ideal_mes','edad_ideal_dia')
                                ->where('esquemas_id', 2017)
                                ->where('deleted_at', NULL)
                                ->get();
                                $debe_tener =  0;
                                foreach ($other->get() as $oth => $voth) {                                   
                                    if(in_array($voth->id, $ninos)){
                                    } else {
                                        array_push($ninos, $voth->id);
                                        foreach ($query_debe_tener as $et => $vet) {
                                            $ideal = Carbon::parse($voth->fecha_nacimiento,"America/Mexico_City")->addYears($vet->edad_ideal_anio)->addMonths($vet->edad_ideal_mes)->addDays($vet->edad_ideal_dia)->format("Y-m-d");
                                            if($ideal>$fm && $ideal<=$im)
                                                $debe_tener++;
                                        }
                                        $query_tiene = DB::table('personas_vacunas_esquemas')
                                            ->where('personas_id', $voth->id)
                                            ->where('deleted_at', NULL)
                                            ->count();
                                        if($query_tiene>=$debe_tener)
                                            $esquema_completo++;
                                    }
                                }  
                                
                            } else {
                                array_push($apk, 0);
                            }

                        }                        
                    }  

                    if(($key>=0 && $key<=11) || ($key>=13 && $key<=18) || ($key>=20 && $key<=22)){
                        $data[$key]['esquema_completo'] = $esquema_completo;
                    }
                    if($key==12){
                        $sss = 0;
                        for ($i12=0; $i12 < 12; $i12++) { 
                            $sss+=$data[$i12]['esquema_completo'];
                        }
                        $data[$key]['esquema_completo'] = $sss;
                    } 
                    if($key==19){
                        $sss = 0;
                        for ($i19=13; $i19 < 19; $i19++) { 
                            $sss+=$data[$i19]['esquema_completo'];
                        }
                        $data[$key]['esquema_completo'] = $sss;
                    }
                    if($key==23){
                        $sss = 0;
                        for ($i23=20; $i23 < 23; $i23++) { 
                            $sss+=$data[$i23]['esquema_completo'];
                        }
                        $data[$key]['esquema_completo'] = $sss;
                    }
                    if($key==24){
                        $data[$key]['esquema_completo'] = $data[12]['esquema_completo']+$data[19]['esquema_completo']+$data[23]['esquema_completo'];
                    }
                    
                    $data[$key]['da'] = $apk;                      
                }
            //} 
            $usuario = User::with('jurisdiccion')->find(Auth::user()->id);
            return response()->json(['text' => $text, 'data' => $data, 'usuario' => $usuario]);
        } else {
            return response()->json([ 'data' => [$data]]);
        }
    }

    public function biologico(Request $request)
    {
        return true;
    }
}
