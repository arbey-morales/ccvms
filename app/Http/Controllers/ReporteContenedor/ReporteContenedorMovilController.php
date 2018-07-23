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
class ReporteContenedorMovilController extends Controller {

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
	}

	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
    {
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
		//$this->ValidarParametros(Request::all());		


		$datos = (object) Request::all();	
		$datos->folio = date('Y.m.i.s');
		$success = false;
		$data    = NULL;
   
		//return Response::json(array("status" => 510, "messages" => $datos ), 200);

		/* FOTO */
        $imgSave                  = 'user-default.png';
		$destinationPath          = 'storage/reporte-contenedor/';
		
        DB::beginTransaction();
        try {

			$width = 950;
			$height = 950;
			
			$name  = NULL;
			$name2 = NULL;

			
			if($datos->foto != NULL || $datos->foto != 'null')
			{
				$time = time().rand(11111, 99999);
				$name = "EVIDENCIA_IMG_MOVIL_".$time.".png";
			}
			
			if($datos->foto2 != NULL || $datos->foto2 != 'null' )
			{
				$time = time().rand(11111, 99999);
				$name2 = "EVIDENCIA_IMG_MOVIL_".$time.".png";
			}
			
			//var_dump($porcentaje, $width, $height); die;
            $data = new ReportesContenedores;
            $data->contenedores_id = $datos->contenedores_id;
			$data->folio = $datos->folio == '' ? NULL : $datos->folio;
			$data->fallas_contenedores_id = $datos->fallas_contenedores_id;
			$data->estatus_reporte =1;
			$data->reporto = $datos->reporto;
			$data->foto = $name;
			$data->foto2 = $name2;
			$data->fecha = $datos->fecha;
			$data->hora = $datos->hora;
			$data->observacion = $datos->observaciones;
			$data->usuario_id = $datos->usuario_id;

            if ($data->save()) {
					
					$seguimiento = new SeguimientosReportesContenedores;
					$seguimiento->reportes_contenedores_id 	= $data->id;
					$seguimiento->estatus_seguimiento 		= 1;
					$seguimiento->observaciones 			= 'Levantamiento del reporte. En espera de seguimiento';
					$seguimiento->save();

					if($datos->foto != 'null')
					{
						$data_foto = base64_decode($datos->foto);
						$im = imagecreatefromstring($data_foto);
						
						header('Content-Type: image/png');
						imagepng($im, $destinationPath.$name);
						imagedestroy($im);
					}
					
					if($datos->foto2 != 'null' )
					{
						$data_foto2 = base64_decode($datos->foto2);
						$im = imagecreatefromstring($data_foto2);
						
						header('Content-Type: image/png');
						imagepng($im, $destinationPath.$name2);
						imagedestroy($im);
					}

				$success = true;
			}

        } catch (\Exception $e) {
			
			//return Response::json($e->getMessage(), 500);
			return Response::json(array("status" => 500, "messages" => "ERROR".$e->getMessage()), 200);

        }
        if ($success){
			DB::commit();
			$contenedor = ContenedorBiologico::with('clue')->find($data->contenedores_id);
			$data->contenedor = $contenedor;

			//return Response::json(array("status" => 409, "messages" => "Conflicto (".$datos->usuario_id.")"),200);

			$user = (object) User::where('email',$datos->usuario_id)->first();
            // lanzar el emsaje
            $this->notificacion("Nuevo reporte", $data, $user->id);
	return Response::json(array("status" => 201, "messages" => "Operación realizada con exito", "data" => $data), 201);

        } 
		else{
            DB::rollback();
            ///return redirect()->back();
			return Response::json(array("status" => 409, "messages" => "Conflicto !!!"),409);
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

	


	private function notificacion($msn, $info,$usuario_id){

		$mensaje = collect();
		//$user = Auth::user()->id;
		$usuarioActual = User::with('jurisdiccion')->find($usuario_id);

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
