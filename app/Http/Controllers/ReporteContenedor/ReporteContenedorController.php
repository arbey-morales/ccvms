<?php
namespace App\Http\Controllers\ReporteContenedor;

use App\Events\NotificacionEvent;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Events\Dispatcher;

use App\Events\MessagePublished;

use Request, Response, Input, DB, Session, Auth;
use App\User;
use App\Models\ReporteContenedor\ReportesContenedores;
use App\Models\ReporteContenedor\SeguimientosReportesContenedores;
use App\Models\Catalogo\RedFrio\EstatusContenedor;
use App\Models\ReporteContenedor\Notificaciones;
use App\Models\ReporteContenedor\NotificacionesUsuarios;
use App\Models\ReporteContenedor\SisUsuariosNotificaciones;

use App\Models\Catalogo\RedFrio\FallaContenedor;
use App\Models\Catalogo\RedFrio\ContenedorBiologico;

use App\Catalogo\Clue;
/**
* Controlador Modulo
* 
* @package    Plataforma API
* @subpackage Controlador
* @author     Eliecer Ramirez Esquinca <ramirez.esquinca@gmail.com>
* @created    2015-07-20
*
* Controlador `ReportesContenedores`: Manejo los permisos(modulo)
*
*/
class ReporteContenedorController extends Controller {

	/**
	 * Muestra una lista de los recurso según los parametros a procesar en la petición.
	 *
	 * <h3>Lista de parametros Request:</h3>
	 * <Ul>Paginación
	 * <Li> <code>$pagina</code> numero del puntero(offset) para la sentencia limit </ li>
	 * <Li> <code>$limite</code> numero de filas a mostrar por página</ li>	 
	 * </Ul>
	 * <Ul>Busqueda
	 * <Li> <code>$valor</code> string con el valor para hacer la busqueda</ li>
	 * <Li> <code>$order</code> campo de la base de datos por la que se debe ordenar la información. Por Defaul es ASC, pero si se antepone el signo - es de manera DESC</ li>	 
	 * </Ul>
	 *
	 * Ejemplo ordenamiento con respecto a id:
	 * <code>
	 * http://url?pagina=1&limite=5&order=id ASC 
	 * </code>
	 * <code>
	 * http://url?pagina=1&limite=5&order=-id DESC
	 * </code>
	 *
	 * Todo Los parametros son opcionales, pero si existe pagina debe de existir tambien limite
	 * @return Response 
	 * <code style="color:green"> Respuesta Ok json(array("status": 200, "messages": "Operación realizada con exito", "data": array(resultado)),status) </code>
	 * <code> Respuesta Error json(array("status": 404, "messages": "No hay resultados"),status) </code>
	 */
	public function index(){
		$datos = Request::all();
		
		// Si existe el paarametro pagina en la url devolver las filas según sea el caso
		// si no existe parametros en la url devolver todos las filas de la tabla correspondiente
		// esta opción es para devolver todos los datos cuando la tabla es de tipo catálogo
		if(array_key_exists("pagina", $datos)){
			$pagina = $datos["pagina"];
			if(isset($datos["order"])){
				$order = $datos["order"];
				if(strpos(" ".$order,"-"))
					$orden = "desc";
				else
					$orden = "asc";
				$order=str_replace("-", "", $order); 
			}
			else{
				$order = "id"; $orden = "asc";
			}
			
			if($pagina == 0){
				$pagina = 1;
			}
			if($pagina == 1)
				$datos["limite"] = $datos["limite"] - 1;
			// si existe buscar se realiza esta linea para devolver las filas que en el campo que coincidan con el valor que el usuario escribio
			// si no existe buscar devolver las filas con el limite y la pagina correspondiente a la paginación
			if(array_key_exists("buscar",  $datos)){
				$columna = $datos["columna"];
				$valor   = $datos["valor"];
				$data = ReportesContenedores::with('fallaContenedor','contenedor')->orderBy($order, $orden);
				
				$search = trim($valor);
				$keyword = $search;
				$data = $data->whereNested(function($query) use ($keyword){					
						$query->Where("contenedores_id", "LIKE", "%".$keyword."%")
							 ->orWhere("fallas_contenedores_id", "LIKE", "%".$keyword."%")
							 ->orWhere("folio", "LIKE", "%".$keyword."%"); 
				});
				$total = $data->get();
				$data = $data->skip($pagina-1)->take($datos["limite"])->get();
			}
			else{
				$data = ReportesContenedores::with('fallaContenedor','contenedor')->skip($pagina-1)->take($datos["limite"])->orderBy($order, $orden)->get();
				$total = ReportesContenedores::get();
			}
			
		}
		else{
			$data = ReportesContenedores::with('fallaContenedor','contenedor')->get();
			$total = $data;
		}

		if (Request::ajax()) {
			if(!$data){
				return Response::json(array("status" => 404,"messages" => "No hay resultados"), 404);
			} 
			else{
				return Response::json(array("status" => 200,"messages" => "Operación realizada con exito","data" => $data,"total" => count($total)), 200);			
			}
		} else {
			return view('reporte-contenedor.index')->with(['data' => $data]);
		}
	}

	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
    {
       return view('reporte-contenedor.create');
    }

	/**
	 * Crear un nuevo registro en la base de datos con los datos enviados
	 *
	 * <h4>Request</h4>
	 * Recibe un input request tipo json de los datos a almacenar en la tabla correspondiente
	 *
	 * @return Response
	 * <code style="color:green"> Respuesta Ok json(array("status": 201, "messages": "Creado", "data": array(resultado)),status) </code>
	 * <code> Respuesta Error json(array("status": 500, "messages": "Error interno del servidor"),status) </code>
	 */
	public function store(){
		$this->ValidarParametros(Request::all());			
		$datos = (object) Request::all();	
		$datos->folio = date('Y.m.i.s');
		$success = false;

		/* FOTO */
        $imgSave                  = 'user-default.png';
		$destinationPath          = 'storage/reporte-contenedor/';
		
		
        DB::beginTransaction();
        try {
			// dd($datos->foto);
			$width = 950;
			$height = 950;
			if (property_exists($datos, 'foto')) {
				$data = getimagesize($datos->foto);
				$width = $data[0];
				$height = $data[1];				
				$extension = $datos->foto->getClientOriginalExtension();
                $imgSave   = 'USER_RAND'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'_EVIDENCIA_DATE_'.date('Y-m-d').'.'.$extension;
				if($width>950){
					$porcentaje = (1 / $width) * 950;
					$width = $width * $porcentaje;
					$height = $height * $porcentaje;
				}
			}
			
			//var_dump($porcentaje, $width, $height); die;
            $data = new ReportesContenedores;
            $data->contenedores_id = $datos->contenedores_id;
			$data->folio = $datos->folio == '' ? null : $datos->folio;
			$data->fallas_contenedores_id = $datos->fallas_contenedores_id;
			$data->estatus_reporte =1;
			$data->reporto = $datos->reporto;
			$data->foto = $imgSave;
			$data->fecha = $datos->fecha;
			$data->hora = $datos->hora;
			$data->observacion = $datos->observacion;
			$data->usuario_id = Auth::user()->email;

            if ($data->save()) {
				if (property_exists($datos, 'foto')) {
					$img = \Image::make($datos->foto->getRealPath())->resize($width, $height)->save($destinationPath.$imgSave);
				}	

				$seguimiento = new SeguimientosReportesContenedores;
				$seguimiento->reportes_contenedores_id 	= $data->id;
				$seguimiento->estatus_seguimiento 		= 1;
				$seguimiento->observaciones 			= 'Levantamiento del reporte. En espera de seguimiento';
				$seguimiento->save();

				$msgGeneral = 'Hey! se guardaron los datos';
				$type       = 'flash_message_ok';		
				$success = true;
			} else {
				$msgGeneral = 'No se guardaron los datos.';
				$type       = 'flash_message_error';
			}
        } catch (\Exception $e) {
			$msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados'.$e->getMessage();
			$type       = 'flash_message_error';
			Session::flash($type, $msgGeneral);
            return redirect()->back();
        }
        if ($success){
			DB::commit();
			$contenedor = ContenedorBiologico::with('clue')->find($data->contenedores_id);
			$data->contenedor = $contenedor;
            // lanzar el emsaje
            $this->notificacion("Nuevo reporte", $data);
			Session::flash($type, $msgGeneral);
            return redirect()->back();
        } 
		else{
            DB::rollback();
			$msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados.';
			$type       = 'flash_message_error';
			Session::flash($type, $msgGeneral);
            return redirect()->back();
        }
		
	}

	/**
	 * @api {get}   /catalogo/contenedor-biologico/:id/edit     5. Editar Contenedor de biológico
	 * @apiVersion  0.1.0
	 * @apiName     EditContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
     * 
     * @apiParam    {Number}        id                  Contenedor de biológico id único.
     * 
     * @apiSuccess  {View}          edit                 Vista alojada en: \resources\views\catalogo\contenedor-biologico\edit
     * @apiSuccess  {Array}         clues                Arreglo del catálogo de clues
     * @apiSuccess  {Array}         modelos              Arreglo del catálogo de modelos
     * @apiSuccess  {Array}         tipos_contenedores   Arreglo del catálogo de tipos de contenedores
     * @apiSuccess  {Array}         estatus              Arreglo del catálogo de estatus  	 *
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'clues_id', 'servidor_id', 'incremento', 'tipos_contenedores_id', 'modelos_id', 'estatus_contenedor_id', 'serie', 'folio', 'tipos_mantenimiento', 'temperatura_minima', 'temperatura_maxima', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError ContenedorBiologicoNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
	public function edit($id)
	{
		if (Auth::user()->is('root|red-frio') && Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
		   $data = ReportesContenedores::with('fallaContenedor','contenedor','seguimientosReportesContenedores')->find($id);
		   $estatus = EstatusContenedor::where('deleted_at',NULL)->get();
		   $arrayestatu=[];
		   foreach ($estatus as $estatu) {
			   $arrayestatu[$estatu->id] = $estatu->descripcion;
		   }
		   if($data) {   
				$notificacion = NotificacionesUsuarios::where("usuarios_id", Auth::user()->id)->where('leido', NULL)->first();
				if($notificacion)
					$this->leida($notificacion->id);
			   return view('reporte-contenedor.edit')->with(['data' => $data, 'estatus' => $arrayestatu]); 
		   } else {
			   return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
		   }
		} else {
			return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
		}
	}

	/**
	 * Actualizar el  registro especificado en el la base de datos
	 *
	 * <h4>Request</h4>
	 * Recibe un Input Request con el json de los datos
	 *
	 * @param  int  $id que corresponde al identificador del dato a actualizar 	 
	 * @return Response
	 * <code style="color:green"> Respuesta Ok json(array("status": 200, "messages": "Operación realizada con exito", "data": array(resultado)),status) </code>
	 * <code> Respuesta Error json(array("status": 304, "messages": "No modificado"),status) </code>
	 */
	public function leida($id){	
		$success = false;
        //return Response::json(["status" => 500, 'error' => $id], 500);
        DB::beginTransaction();
        try{
            $data = NotificacionesUsuarios::find($id);

            if($data){
            	$notificacion = NotificacionesUsuarios::where("id", $data->id)->where("usuarios_id", Auth::user()->id)->first();
        		$notificacion->leido = date("Y-m-d h:i:s");
        		if($notificacion->save())
        			$success = true;
            }                     

        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(["status" => 500, 'error' => $e->getMessage()], 500);
        } 
        if($success){
			DB::commit();
			return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data), 200);
		}else {
			DB::rollback();
			return Response::json(array("status" => 304, "messages" => "No modificado"),200);
		}
	}

	/**
	 * Actualizar el  registro especificado en el la base de datos
	 *
	 * <h4>Request</h4>
	 * Recibe un Input Request con el json de los datos
	 *
	 * @param  int  $id que corresponde al identificador del dato a actualizar 	 
	 * @return Response
	 * <code style="color:green"> Respuesta Ok json(array("status": 200, "messages": "Operación realizada con exito", "data": array(resultado)),status) </code>
	 * <code> Respuesta Error json(array("status": 304, "messages": "No modificado"),status) </code>
	 */
	public function update($id){
		$this->ValidarSeguimiento(Request::json()->all());			
		$datos = (object) Input::all();	
		$success = false;

        DB::beginTransaction();
        try {
			$data = new SeguimientosReportesContenedores;
			$data->reportes_contenedores_id 	= $id;
			$data->estatus_seguimiento 			= $datos->estatus_seguimiento;
			$data->observaciones 				= $datos->observaciones;

			$segTerminado = SeguimientosReportesContenedores::where('reportes_contenedores_id', $id)
							->whereIn('estatus_seguimiento', [4,5,6])
							->where('deleted_at', NULL)
							->count();
			if($segTerminado<1){				
				if ($data->save()) {				
					$success = true;
					$datax = ReportesContenedores::find($id);

					if($datos->estatus_seguimiento==1)	
						$datax->estatus_reporte = 1;
					if($datos->estatus_seguimiento==2 || $datos->estatus_seguimiento==3)	
						$datax->estatus_reporte = 2;
					if($datos->estatus_seguimiento==4 || $datos->estatus_seguimiento==5 || $datos->estatus_seguimiento==6)	
						$datax->estatus_reporte = 3;
					$datax->save();
					$CB = ContenedorBiologico::find($datax->contenedores_id);
					$CB->estatus_contenedor_id = $datos->estatus_contenedores_id;
					$CB->save();					
				}
			} else {
				Session::flash('flash_message_error', 'Este reporte ha sido finalizado o descartado');
				return redirect()->back();
			}
		} 
		catch (\Exception $e) {
			// return Response::json($e->getMessage(), 500);
			Session::flash('flash_message_error', 'Error: '.$e->getMessage());
			return redirect()->back();
        }
        if ($success){
			DB::commit();
			$this->notificacion("Modificación de un reporte", $datax);
			$msgGeneral = 'Perfecto! se guardaron los datos';
			$type       = 'flash_message_ok';			
			// return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data), 200);
		} 
		else {
			DB::rollback();
			$msgGeneral = 'No se guardaron los datos personales. Verifique su información o recargue la página.';
			$type       = 'flash_message_error'; 
			// return Response::json(array("status" => 304, "messages" => "No modificado"),304);
		}
		Session::flash($type, $msgGeneral);
		return redirect()->back();
	}
	/**
	 * Devuelve la información del registro especificado.
	 *
	 * @param  int  $id que corresponde al identificador del recurso a mostrar
	 *
	 * @return Response
	 * <code style="color:green"> Respuesta Ok json(array("status": 200, "messages": "Operación realizada con exito", "data": array(resultado)),status) </code>
	 * <code> Respuesta Error json(array("status": 404, "messages": "No hay resultados"),status) </code>
	 */
	public function show($id){
		$data = ReportesContenedores::find($id);

		if(!$data){
			return Response::json(array("status"=> 204, "messages" => "No hay resultados"), 204);
		} 
		else {
			return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data), 200);
		}

	}


	/**
	 * Elimine el registro especificado del la base de datos (softdelete).
	 *
	 * @param  int  $id que corresponde al identificador del dato a eliminar
	 *
	 * @return Response
	 * <code style="color:green"> Respuesta Ok json(array("status": 200, "messages": "Operación realizada con exito", "data": array(resultado)),status) </code>
	 * <code> Respuesta Error json(array("status": 500, "messages": "Error interno del servidor"),status) </code>
	 */
	public function destroy($id){
		$success = false;
        DB::beginTransaction();
        try {
			$data = ReportesContenedores::find($id);
			$data->delete();
			$success=true;
		} 
		catch (\Exception $e) {
			return Response::json($e->getMessage(), 500);
        }
        if ($success){
			DB::commit();
			return Response::json(array("status" => 200, "messages" => "Operación realizada con exito","data" => $data), 200);
		} 
		else {
			DB::rollback();
			return Response::json(array("status" => 404, "messages" => "No se encontro el registro"), 404);
		}
	}
	
	/**
	 * Validad los parametros recibidos, Esto no tiene ruta de acceso es un metodo privado del controlador.
	 *
	 * @param  Request  $request que corresponde a los parametros enviados por el cliente
	 *
	 * @return Response
	 * <code> Respuesta Error json con los errores encontrados </code>
	 */
	private function ValidarParametros($request){
		$rules = [
			"contenedores_id" 			=> "number|min:1|required",
			"fallas_contenedores_id"	=> "number|min:1|required",
			"reporto" 					=> "required",
			"fecha"						=> "date|required",
			"hora" 						=> "time|required",
			"observacion"				=> "required"
		];
		$v = \Validator::make(Request::json()->all(), $rules );

		if ($v->fails()){
			return Response::json($v->errors());
		}
	}

	private function ValidarSeguimiento($request){
		$rules = [
			"estatus_seguimiento"	=> "number|min:1|required",
			"observaciones"			=> "required"
		];
		$v = \Validator::make(Request::json()->all(), $rules );

		if ($v->fails()){
			return Response::json($v->errors());
		}
	}

	private function notificacion($msn, $info){

		$mensaje = collect();
		$user = Auth::user()->id;
		$usuarioActual = User::with('jurisdiccion')->find($user);

		$mensaje->put('titulo', $msn);
		$mensaje->put('reportes_contenedores_id', $info->id);
        $mensaje->put('mensaje',$usuarioActual->nombre." ".$usuarioActual->paterno." ".$usuarioActual->materno.": ". $info->reporto." Reporta el contenedor con No. serie ".$info->contenedor->serie." de la unidad de salud ".$info->contenedor->clue->clues.":".$info->contenedor->clue->nombre);
        $mensaje->put('created_at', date('Y-m-d H:i:s'));
        $mensaje->put('enviado', null);
        $mensaje->put('leido', null);

		$notificacion = new Notificaciones;
        $notificacion->tipo                = '1';
        $notificacion->mensaje             = $mensaje;

        if ($notificacion->save()){
            $usuarios = SisUsuariosNotificaciones::where("tipos_notificaciones_id", $notificacion->tipo)->get();

            foreach($usuarios as $usuario){
                $notificacionesUsuarios = new NotificacionesUsuarios;
                $usuario_id = $usuario->sis_usuarios_id;

                $notificacionesUsuarios->usuarios_id          = $usuario_id;
                $notificacionesUsuarios->telefono             = '';

                $notificacionesUsuarios->notificaciones_id    = $notificacion->id;
                $notificacionesUsuarios->enviado              = date("Y-m-d H:i:s");
                $notificacionesUsuarios->sms                  = '';
                $notificacionesUsuarios->status               = 0;

                if($notificacionesUsuarios->save()){

	                $options = array(
					    'cluster' => env("PUSHER_CLUSTER"),
					    'encrypted' => true
					);
					$pusher = new \Pusher(
					    env("PUSHER_APP_KEY"),
					    env("PUSHER_APP_SECRET"),
					    env("PUSHER_APP_ID"),
					    $options
					);
					$mensaje->put('notificaciones_usuarios_id', $notificacionesUsuarios->id);
					$data['name'] = $usuarioActual->nombre;
					$data['message'] = $mensaje;
					$pusher->trigger('reporteC'.$usuario_id, 'my-event', $data);
				}
            }

        }
       	
	    return true;
	}
}
