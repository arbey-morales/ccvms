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
use App\User;
use App\Catalogo\Jurisdiccion;

class MonitoreoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->is('root|admin') && Auth::user()->can('show.usuarios') && Auth::user()->activo==1) {
            $parametros = Input::only('fecha','todo');
            $todo = false;
            $fecha_send = '';
            if($parametros['todo'] && $parametros['todo']!=NULL && $parametros['todo']!="" && $parametros['todo']=='SI'){
                $todo = true;
            } else {            
                if($parametros['fecha'] && $parametros['fecha']!=NULL && $parametros['fecha']!=""){
                    $fecha_send = $parametros['fecha'];
                    $date       = explode("-", $parametros['fecha']);
                    $fecha      = Carbon::parse($date[2]."-".$date[1]."-".$date[0]." 00:00:00","America/Mexico_City")->format('Y-m-d H:i:s');
                    $fecha_top  = Carbon::parse($date[2]."-".$date[1]."-".$date[0]." 00:00:00","America/Mexico_City");
                    $fecha_top  = $fecha_top->addDay()->format('Y-m-d H:i:s');
                } else {
                    $fecha      = Carbon::today('America/Mexico_City')->format('Y-m-d H:i:s');
                    $fecha_top  = Carbon::today('America/Mexico_City');   
                    $fecha_top  = $fecha_top->addDay()->format('Y-m-d H:i:s');             
                    $fecha_send = Carbon::today('America/Mexico_City')->format('d-m-Y');
                }
            }
            
            $data2 = Jurisdiccion::where('deleted_at', NULL)->orderBy('id')->get();
            foreach ($data2 as $key2 => $value2) {
                $data_users = User::select('idJurisdiccion','id','email','borrado','nombre','paterno','materno')->where('idJurisdiccion', $value2->id)->where('asRoot', 0)->where('borrado', 0)->orderBy('id')->get();
                $usuarios = collect();
                foreach ($data_users as $key_users => $value_users) {
                    if($todo){
                        $value_users->captura = DB::select("select count(id) as captura from personas where usuario_id='$value_users->email' and deleted_at is null")[0]->captura;
                    } else {
                        $value_users->captura = DB::select("select count(id) as captura from personas where usuario_id='$value_users->email' and created_at >= '$fecha' and created_at < '$fecha_top' and deleted_at is null")[0]->captura;
                    }                    
                    $value2->captura_jurisdiccion+= $value_users->captura;
                }
                $data2[$key2]->usuarios = $data_users;
            }
            
            $usuario = User::with('jurisdiccion')->find(Auth::user()->id);
            return view('monitoreo.index')->with(['usuario' => $usuario, 'data2' => $data2, 'fecha' => $fecha_send, 'todo' => $todo]);
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
        if (Auth::user()->is('admin|root') && Auth::user()->can('create.usuarios') && Auth::user()->activo==1) {
            
            $jurisdicciones = Jurisdiccion::all();

            foreach ($jurisdicciones as $jurisdiccion) {
                $arrayJurisdicciones[$jurisdiccion->id] = $jurisdiccion->clave .' - '.$jurisdiccion->nombre;
            }

            return view('usuario.create')->with(['jurisdicciones' => $arrayJurisdicciones]);
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
        $activo     = 1;
        
        /* FOTO */
        $imgSave                  = 'user-default.png';
        $destinationPath          = 'storage/user/profile/';

        $lId = User::orderBy('creadoAl', 'desc')->first();
        $lastId = ($lId->id) + 1;

        //dd($lastId);

        if (Auth::user()->is('root')) {
            $roles = Role::all();
        } else {
            $roles = Role::where('level', '!=', 1)->get();
        }

        if (Auth::user()->is('admin|root') && Auth::user()->can('create.usuarios') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'same'     => 'El campo :attribute debe ser igual al password',
                'confirmed' => 'El campo :attribute debe ser confirmado'
            ];

            $rules = [
                'nombre'               => 'required|min:3|max:30|string',
                'paterno'              => 'required|min:3|max:20|string',
                'materno'              => 'required|min:3|max:20|string',
                'idJurisdiccion'       => 'required|min:1|numeric',
                'direccion'            => 'required|min:10|max:80',
                'email'                => 'required|email|unique:users,email,borrado,0',
                'foto'                 => 'sometimes|mimes:jpeg,jpg',
                'password'             => 'required|confirmed|min:6'
            ];
            
            $this->validate($request, $rules, $messages);

            if ($request->activo!=1) {
                $activo = 0;
            }

            if ($request->foto) {
                $extension = $request->foto->getClientOriginalExtension();
                $imgSave   = 'USER_RAND'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            }

         
            $usuario = new User;
            $usuario->nombre                = $request->nombre;
            $usuario->paterno               = $request->paterno;
            $usuario->materno               = $request->materno;
            $usuario->idJurisdiccion        = $request->idJurisdiccion ;
            $usuario->direccion             = $request->direccion;
            $usuario->email                 = $request->email;
            $usuario->password              = bcrypt($request->password);
            $usuario->foto                  = $imgSave;
            $usuario->creadoUsuario         = Auth::user()->id;

                try {
                    $usuario->save();
                    
                    if($usuario->id) {

                        if ($request->foto) {
                            $img = \Image::make($request->foto->getRealPath())->resize(250, 250)->save($destinationPath.$imgSave);
                        }

                        // Attach Roles
                        $usuario->attachRole(3);

                        $msgGeneral = 'Hey! se gurdaron los datos';
                        $type       = 'flash_message_ok';
                    } else {
                        $msgGeneral = 'No se guardaron los datos.';
                        $type       = 'flash_message_error';
                    }
                } catch(\PDOException $e){
                    $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados.';
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
        if (Auth::user()->is('admin|root') && Auth::user()->can('show.usuarios') && Auth::user()->activo==1) {
            $usuario = User::findOrFail($id);
            if (Auth::user()->is('root')) {
                $usuarioSend = User::where('id', '=', $id)->where('borrado',0)
                ->with('jurisdiccion','rolesuser')
                ->first();
            } else {
                $usuarioSend = User::where('id', '=', $id)->where('asRoot', 0)->where('borrado',0)
                ->with('jurisdiccion','rolesuser')
                ->first();
            }

            if (!$usuarioSend) {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
            return view('usuario.show')->with('data', $usuarioSend);
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
        if (Auth::user()->is('admin|root') && Auth::user()->can('show.usuarios') && Auth::user()->activo==1) {
            $usuario = User::findOrFail($id);
            if (Auth::user()->is('root')) {
                $usuarioSend = User::where('id', '=', $id)->where('borrado',0)
                ->with('jurisdiccion','rolesuser')
                ->first();
            } else {
                $usuarioSend = User::where('id', '=', $id)->where('asRoot', 0)->where('borrado',0)
                ->with('jurisdiccion','rolesuser')
                ->first();
            }

           $jurisdicciones = Jurisdiccion::all();

            foreach ($jurisdicciones as $jurisdiccion) {
                $arrayJurisdicciones[$jurisdiccion->id] = $jurisdiccion->clave .' - '.$jurisdiccion->nombre;
            }

            if (!$usuarioSend) {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
            return view('usuario.edit')->with(['data' => $usuarioSend, 'jurisdicciones' => $arrayJurisdicciones]);
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
        $activo     = 1;
        /* FOTO */
        $imgSave                  = 'user-default.png';
        $destinationPath          = 'storage/user/profile/';

        if (Auth::user()->is('root')) {
            $roles = Role::all();
        } else {
            $roles = Role::where('level', '!=', 1)->get();
        }

        $user = User::findOrFail($id);

        if (Auth::user()->is('admin|root') && Auth::user()->can('update.usuarios') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'same'     => 'El campo :attribute debe ser igual al password',
                'confirmed' => 'El campo :attribute debe ser confirmado'
            ];

            $rules = [
                'nombre'               => 'required|min:3|max:30|string',
                'paterno'              => 'required|min:3|max:20|string',
                'materno'              => 'required|min:3|max:20|string',
                'idJurisdiccion'       => 'required|min:1|numeric',
                'direccion'            => 'required|min:10|max:80',
                'email'                => 'required|email||unique:users,email,'.$id.',id,borrado,0',
                'foto'                 => 'sometimes|mimes:jpeg,jpg',
                'password'             => 'sometimes|confirmed|min:6'
            ];
            
            $this->validate($request, $rules, $messages);

            if ($request->activo!=1) {
                $activo = 0;
            }

            if ($request->foto) {
                $extension = $request->foto->getClientOriginalExtension();
                $imgSave   = 'USER_RAND'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            } else {
                $imgSave = $user->foto;
            }

            try {
                if($request->password) {
                    $updates = DB::table('users')
                        ->where('id', '=', $id)
                        ->update([
                            'idJurisdiccion'        => $request->idJurisdiccion,
                            'paterno'               => $request->paterno,
                            'materno'               => $request->materno,
                            'nombre'                => $request->nombre,
                            'direccion'             => $request->direccion,
                            'email'                 => $request->email,
                            'password'              => bcrypt($request->password),
                            'foto'                  => $imgSave,
                            'activo'                => $activo,
                            'modificadoAl'          => date('Y-m-d H:m:s'),
                            'modificadoUsuario'     => Auth::user()->id
                        ]);
                } else {
                    $updates = DB::table('users')
                        ->where('id', '=', $id)
                        ->update([
                            'idJurisdiccion'        => $request->idJurisdiccion,
                            'paterno'               => $request->paterno,
                            'materno'               => $request->materno,
                            'nombre'                => $request->nombre,
                            'direccion'             => $request->direccion,
                            'email'                 => $request->email,
                            'foto'                  => $imgSave,
                            'activo'                => $activo,
                            'modificadoAl'          => date('Y-m-d H:m:s'),
                            'modificadoUsuario'     => Auth::user()->id
                        ]);
                }

                if ($request->foto) {
                    $img = \Image::make($request->foto->getRealPath())->resize(250, 250)->save($destinationPath.$imgSave);
                }
            
                if ($updates) {

                    $msgGeneral = 'Se guardaron los cambios.';
                    $type       = 'flash_message_ok';
                } else {
                    $msgGeneral = 'No se guardaron los cambios, verifique la lista!';
                    $type       = 'flash_message_error';
                }

                Session::flash($type, $msgGeneral);
            } catch(\PDOException $e){
                Session::flash('flash_message_error', 'Ocurrió un error al intentar guardar los cambios.');
            }
            
            if(Auth::user()->id == $id) {
                if($request->password!=NULL) {
                    return redirect('auth/logout');
                }
                Session::flash($type, $msgGeneral);
                return redirect()->back();
            } else { 
                Session::flash($type, $msgGeneral);
                return redirect()->back();
            }

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
        
        $usuario = User::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->can('delete.usuarios') && Auth::user()->activo==1) {
                if (Auth::user()->id != $usuario->id) {
                    try {
                        $updates = DB::table('users')
                            ->where('id', '=', $id)
                            ->where('asRoot', 0)
                            ->update([
                                'borrado'        => 1,
                                'borradoAl'      => date('Y-m-d H:m:s'),
                                'borradoUsuario' => Auth::user()->id
                            ]);
                        if ($updates) {
                            $usuario->detachAllRoles();
                            $msgGeneral = 'Se borró el elemento';
                            $type2      = 'success';
                        } else {
                            $msgGeneral = 'No se borró el elemento';
                            $type2      = 'error';
                        }
                    } catch(\PDOException $e){
                        $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados.';
                        $type2      = 'error';
                    }
                } else {
                    $msgGeneral = 'No se puede eliminar usted mismo, otro administrador tendrá que hecerlo';
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
