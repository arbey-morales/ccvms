<?php

namespace App\Http\Controllers\Modulo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session;
use App\Modulo\Operador;
use App\Modulo\TelefonoOperador;
use App\Modulo\CheckOperador;
use App\Modulo\Asignacion;
use App\Modulo\Servicio;
use App\Catalogo\TiposDocumento;
use App\Catalogo\TipoTelefono;


class OperadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('show.operadores') && Auth::user()->activo==1) {
            $operadores = Operador::where('borrado', 0)
                ->where('idSitio', Auth::user()->idSitio)
                ->with('localidad.municipio.estado')
                ->get();
            return view('operador.index')->with('operadores', $operadores);
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
        if (Auth::user()->can('create.operadores') && Auth::user()->activo==1) {
            $arrayDocumentos = [null => 'Seleccionar un tipo de documento'];
            $arrayTipoTelefono = [];

            $tiposTelefono = TipoTelefono::where('borrado',0)
                ->get();
            foreach ($tiposTelefono as $tipoTelefono) {
                $arrayTipoTelefono[$tipoTelefono->id] = ['id' => $tipoTelefono->id, 'nombre' => $tipoTelefono->nombre, 'abreviatura' => $tipoTelefono->abreviatura, 'icon' => $tipoTelefono->icon];
            }

            $documentos = TiposDocumento::where('activo',1)
                ->where('borrado', 0)
                ->get();
            foreach ($documentos as $documento) {
                $arrayDocumentos[$documento->id] = $documento->nombre;
            }

            $arrayMain = ['documentos' => $arrayDocumentos, 'tipostelefono' => $arrayTipoTelefono];
            
            return view('operador.create')->with('main', $arrayMain);
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
        $destinationPath          = 'storage/operador/profile/';
        /* LICENCIA */
        $licenciaSave             = 'licencia-default.png';
        $destinationPathLicencia  = 'storage/operador/licencia/';
        /* DOMICILIO */
        $domicilioSave            = 'domicilio-default.png';
        $destinationPathDomicilio = 'storage/operador/domicilio/';
        /* INE */
        $ineSave                  = 'ine-default.png';
        $destinationPathIne       = 'storage/operador/ine/';

        if (Auth::user()->can('create.operadores') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'exists'   => 'El campo :attribute ya está registrado',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'alpha'    => 'El campo :attribute debe contener solo letras',
                'string'   => 'El campo :attribute sólo acepta caracteres ',
                'date'     => 'El campo :attribute debe tener el formato(YYYY-MM-DD), ej: 2016-02-23',
            ];

            $rules = [
                'nombre'               => 'required|min:3|max:30|string',
                'paterno'              => 'required|min:3|max:20|string',
                'materno'              => 'required|min:3|max:20|string',
                'idLocalidad'          => 'required|min:1|numeric',
                'direccion'            => 'required|min:10|max:80',
                'tipoDocumento'        => 'required|min:1|numeric',
                'numeroDocumento'      => 'required|min:10|max:15|unique:Operadores,numeroDocumento,borrado,0',
                'numeroLicencia'       => 'required|min:10|max:12|unique:Operadores,numeroLicencia,borrado,0',
                'venceLicencia'        => 'required|date',
                'foto'                 => 'sometimes|mimes:jpeg,jpg',
                'licencia'             => 'sometimes|mimes:jpeg,jpg',
                'comprobanteDomicilio' => 'sometimes|mimes:jpeg,jpg',
                'ine'                  => 'sometimes|mimes:jpeg,jpg'
            ];
            
            $this->validate($request, $rules, $messages);

            if ($request->activo!=1) {
                $activo = 0;
            }

            if ($request->foto) {
                $extension = $request->foto->getClientOriginalExtension();
                $imgSave   = 'OPERADOR_RAND_FOTO'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            }

            if ($request->licencia) {
                $extension    = $request->licencia->getClientOriginalExtension();
                $licenciaSave = 'OPERADOR_RAND_LICENCIA'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            }

            if ($request->comprobanteDomicilio) {
                $extension     = $request->comprobanteDomicilio->getClientOriginalExtension();
                $domicilioSave = 'OPERADOR_RAND_COMPROBANTE'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            }

            if ($request->ine) {
                $extension = $request->ine->getClientOriginalExtension();
                $ineSave   = 'OPERADOR_RAND_INE'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            }
            
            $operador = new Operador;
            $operador->idSitio               = Auth::user()->idSitio;
            $operador->nombre                = $request->nombre;
            $operador->paterno               = $request->paterno;
            $operador->materno               = $request->materno;
            $operador->idLocalidad           = $request->idLocalidad;
            $operador->direccion             = $request->direccion;
            $operador->tipoDocumento         = $request->tipoDocumento;
            $operador->numeroDocumento       = $request->numeroDocumento;
            $operador->numeroLicencia        = $request->numeroLicencia;
            $operador->venceLicencia         = $request->venceLicencia;
            $operador->nombreCompleto        = $request->nombre.' '.$request->paterno.' '.$request->materno;
            $operador->foto                  = $imgSave;
            if (Auth::user()->is('admin|root') && Auth::user()->can('upload.files')) {
                $operador->licencia              = $licenciaSave;
                $operador->comprobanteDomicilio  = $domicilioSave;
                $operador->ine                   = $ineSave;
            }
            $operador->activo                = $activo;
            $operador->creadoUsuario         = Auth::user()->id;

        // dd($request->licencia.' :LIC - COMP: '.$request->comprobanteDomicilio.' - INE: '.$request->ine);

            try {
                $operador->save();
                
                if ($request->foto) {
                    $img = \Image::make($request->foto->getRealPath())->resize(250, 250)->save($destinationPath.$imgSave);
                }

                if (Auth::user()->is('admin|root') && Auth::user()->can('upload.files')) {
                    if ($request->licencia) {
                        $img = \Image::make($request->licencia->getRealPath())->resize(2550,3300)->save($destinationPathLicencia.$licenciaSave);
                    }

                    if ($request->comprobanteDomicilio) {
                        $img = \Image::make($request->comprobanteDomicilio->getRealPath())->resize(2550,3300)->save($destinationPathDomicilio.$domicilioSave);
                    }

                    if ($request->ine) {
                        $img = \Image::make($request->ine->getRealPath())->resize(2550,3300)->save($destinationPathIne.$ineSave);
                    }
                }

                $tagsPhone = TipoTelefono::where('borrado',0)->get();
            
                if ($operador->id) {
                // Insert teléfonos
                foreach ($tagsPhone as $key => $value) { // foreach que obtiene los tipos de télefonos
                        $valueTag = explode(',',$request['tags_'.$value->id]);
                        $idTipoTelefono = $value->id;
                        if (count($valueTag)>0) {  // si tiene valores
                            foreach ($valueTag as $keyPhone => $valuePhone) { // foreach que obtiene los tipos de télefonos
                                if ($valuePhone!=null && $valuePhone!="") {
                                    $telOperador = new TelefonoOperador;
                                    $telOperador->idOperador       =  $operador->id;
                                    $telOperador->idTipotelefono   =  $idTipoTelefono;
                                    $telOperador->numero           =  $valuePhone;
                                    $telOperador->creadoUsuario    =  Auth::user()->id;

                                    $telOperador->save();
                                }
                            }
                        }
                    }

                $msgGeneral = 'Se agregó exitosamente el registro.';
                $type       = 'flash_message_ok';
                } else {
                    $msgGeneral = 'No se pudo agregar el registro solicitado, verifique la lista!';
                    $type       = 'flash_message_error';
                }

                Session::flash($type, $msgGeneral);
            } catch(\PDOException $e){
                Session::flash('flash_message_error', 'Ocurrió un error al intentar guardar los datos enviados.');
            }

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
        if (Auth::user()->can('show.operadores') && Auth::user()->activo==1) {
            $operador = Operador::findOrFail($id);
            $operadorSend = Operador::where('id', '=', $id)
                ->where('idSitio', Auth::user()->idSitio)
                ->where('borrado',0)
                ->with('tipo_documento')
                ->with('localidad')
                ->with('telefonos')
                ->with('servicios')
                ->with('incidencias')
                ->with('suspenciones')
                ->with('checksLast')
                ->first();

            if (!$operadorSend) {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
            return view('operador.show')->with('operador', $operadorSend);
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
        if (Auth::user()->can('show.operadores') && Auth::user()->activo==1) {
            $arrayDocumentos = [null => 'Seleccionar un tipo de documento'];
            $arrayTipoTelefono = [];

            $tiposTelefono = TipoTelefono::where('borrado',0)
                ->get();
            foreach ($tiposTelefono as $tipoTelefono) {
                $arrayTipoTelefono[$tipoTelefono->id] = ['id' => $tipoTelefono->id, 'nombre' => $tipoTelefono->nombre, 'abreviatura' => $tipoTelefono->abreviatura, 'icon' => $tipoTelefono->icon];
            }

            $documentos = TiposDocumento::where('activo',1)
                ->where('borrado', 0)
                ->get();
            foreach ($documentos as $documento) {
                $arrayDocumentos[$documento->id] = $documento->nombre;
            }

            $operador = Operador::findOrFail($id);
            $operadorSend = Operador::where('id', '=', $id)
                ->where('idSitio', Auth::user()->idSitio)
                ->where('borrado',0)
                ->with('localidad')
                ->with('tipo_documento')
                ->with('telefonos')
                ->first();
            if (!$operadorSend) {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }

            $arrayMain = ['documentos' => $arrayDocumentos, 'tipostelefono' => $arrayTipoTelefono, 'operador' => $operadorSend];

            return view('operador.edit')->with('main', $arrayMain);
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
        $operadorMain = Operador::findOrFail($id);

        $msgGeneral = '';
        $type       = 'flash_message_info';
        $activo     = 1;
        /* FOTO */
        $imgSave                  = 'user-default.png';
        $destinationPath          = 'storage/operador/profile/';
        /* LICENCIA */
        $licenciaSave             = 'licencia-default.png';
        $destinationPathLicencia  = 'storage/operador/licencia/';
        /* DOMICILIO */
        $domicilioSave            = 'domicilio-default.png';
        $destinationPathDomicilio = 'storage/operador/domicilio/';
        /* INE */
        $ineSave                  = 'ine-default.png';
        $destinationPathIne       = 'storage/operador/ine/';

        if (Auth::user()->can('update.operadores') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El campo :attribute debe ser de tipo jpeg o jpg.',
                'unique'   => 'El campo :attribute ya existe',
                'exists'   => 'El campo :attribute ya está registrado',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'alpha'    => 'El campo :attribute debe contener solo letras',
                'string'   => 'El campo :attribute sólo acepta caracteres ',
                'date'     => 'El campo :attribute debe tener el formato(YYYY-MM-DD), ej: 2016-02-23',
            ];

            $rules = [
                'nombre'               => 'required|min:3|max:30|string',
                'paterno'              => 'required|min:3|max:20|string',
                'materno'              => 'required|min:3|max:20|string',
                'idLocalidad'          => 'required|min:1|numeric',
                'direccion'            => 'required|min:10|max:80',
                'tipoDocumento'        => 'required|min:1|numeric',
                'numeroDocumento'      => 'required|min:10|max:15|unique:Operadores,numeroDocumento,'.$id.',id,borrado,0',
                'numeroLicencia'       => 'required|min:10|max:12|unique:Operadores,numeroLicencia,'.$id.',id,borrado,0',
                'venceLicencia'        => 'required|date',
                'foto'                 => 'sometimes|mimes:jpeg,jpg',
                'licencia'             => 'sometimes|mimes:jpeg,jpg',
                'comprobanteDomicilio' => 'sometimes|mimes:jpeg,jpg',
                'ine'                  => 'sometimes|mimes:jpeg,jpg'
            ];
            
            $this->validate($request, $rules, $messages);

            if ($request->activo!=1) {
                $activo = 0;
            }
            
            if ($request->foto) {
                $extension = $request->foto->getClientOriginalExtension();
                $imgSave   = 'OPERADOR_RAND_UPDATE'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            } else {
                $imgSave = $operadorMain->foto;
            }
            
            if ($request->licencia) {
                $extension    = $request->licencia->getClientOriginalExtension();
                $licenciaSave = 'OPERADOR_RAND_LICENCIA_UPDATE'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            } else {
                $licenciaSave = $operadorMain->licencia;
            }

            if ($request->comprobanteDomicilio) {
                $extension     = $request->comprobanteDomicilio->getClientOriginalExtension();
                $domicilioSave = 'OPERADOR_RAND_COMPROBANTE_UPDATE'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            } else {
                $domicilioSave = $operadorMain->comprobanteDomicilio;
            }

            if ($request->ine) {
                $extension = $request->ine->getClientOriginalExtension();
                $ineSave   = 'OPERADOR_RAND_INE_UPDATE'.rand(111111111111111,999999999999999).'_U'.Auth::user()->id.'USI'.Auth::user()->idSitio.'_DATE_'.date('Y-m-d').'.'.$extension;
            } else {
                $ineSave = $operadorMain->ine;
            }
            
            try {
                if (Auth::user()->is('admin|root') && Auth::user()->can('upload.files')) {
                    $updates = DB::table('Operadores')
                        ->where('idSitio', Auth::user()->idSitio)
                        ->where('id', '=', $id)
                        ->update([
                            'idLocalidad'           => $request->idLocalidad,
                            'tipoDocumento'         => $request->tipoDocumento,
                            'paterno'               => $request->paterno,
                            'materno'               => $request->materno,
                            'nombre'                => $request->nombre,
                            'numeroLicencia'        => $request->numeroLicencia,
                            'venceLicencia'         => $request->venceLicencia,
                            'direccion'             => $request->direccion,
                            'numeroDocumento'       => $request->numeroDocumento,
                            'nombreCompleto'        => $request->nombre.' '.$request->paterno.' '.$request->materno,
                            'foto'                  => $imgSave,
                            'licencia'              => $licenciaSave,
                            'comprobanteDomicilio'  => $domicilioSave,
                            'ine'                   => $ineSave,
                            'activo'                => $activo,
                            'modificadoAl'          => date('Y-m-d H:m:s'),
                            'modificadoUsuario'     => Auth::user()->id
                        ]);
                } else {
                    $updates = DB::table('Operadores')
                        ->where('idSitio', Auth::user()->idSitio)
                        ->where('id', '=', $id)
                        ->update([
                            'idLocalidad'           => $request->idLocalidad,
                            'tipoDocumento'         => $request->tipoDocumento,
                            'paterno'               => $request->paterno,
                            'materno'               => $request->materno,
                            'nombre'                => $request->nombre,
                            'numeroLicencia'        => $request->numeroLicencia,
                            'venceLicencia'         => $request->venceLicencia,
                            'direccion'             => $request->direccion,
                            'numeroDocumento'       => $request->numeroDocumento,
                            'nombreCompleto'        => $request->nombre.' '.$request->paterno.' '.$request->materno,
                            'foto'                  => $imgSave,
                            'activo'                => $activo,
                            'modificadoAl'          => date('Y-m-d H:m:s'),
                            'modificadoUsuario'     => Auth::user()->id
                        ]);
                }

                
                if ($request->foto) {
                    $img = \Image::make($request->foto->getRealPath())->resize(250, 250)->save($destinationPath.$imgSave);
                }

                if (Auth::user()->is('admin|root') && Auth::user()->can('upload.files')) {
                    if ($request->licencia) {
                        $img = \Image::make($request->licencia->getRealPath())->resize(2550,3300)->save($destinationPathLicencia.$licenciaSave);
                    }

                    if ($request->comprobanteDomicilio) {
                        $img = \Image::make($request->comprobanteDomicilio->getRealPath())->resize(2550,3300)->save($destinationPathDomicilio.$domicilioSave);
                    }

                    if ($request->ine) {
                        $img = \Image::make($request->ine->getRealPath())->resize(2550,3300)->save($destinationPathIne.$ineSave);
                    }
                }

                $tagsPhone = TipoTelefono::where('borrado',0)->get();
            
                if ($updates) {
                // Delete Telefonos 
                $deletePhones = DB::table('TelefonosOperadores')
                    ->where('idOperador', '=', $id)
                    ->update([
                        'borrado'        => 1,
                        'borradoAl'      => date('Y-m-d H:m:s'),
                        'borradoUsuario' => Auth::user()->id
                    ]);
                // Insert teléfonos
                foreach ($tagsPhone as $key => $value) { // foreach que obtiene los tipos de télefonos
                        $valueTag = explode(',',$request['tags_'.$value->id]);
                        $idTipoTelefono = $value->id;
                        if (count($valueTag)>0) {  // si tiene valores
                            foreach ($valueTag as $keyPhone => $valuePhone) { // foreach que obtiene los tipos de télefonos
                                if ($valuePhone!=null && $valuePhone!="") {
                                    $telOperador = new TelefonoOperador;
                                    $telOperador->idOperador       =  $id;
                                    $telOperador->idTipoTelefono   =  $idTipoTelefono;
                                    $telOperador->numero           =  $valuePhone;
                                    $telOperador->creadoUsuario    =  Auth::user()->id;

                                    $telOperador->save();
                                }
                            }
                        }
                    }

                $msgGeneral = 'Se guardaron los cambios.';
                $type       = 'flash_message_ok';
                } else {
                    $msgGeneral = 'No se guardaron los cambios, verifique la lista!';
                    $type       = 'flash_message_error';
                }

                Session::flash($type, $msgGeneral);
            } catch(\PDOException $e){
                Session::flash('flash_message_error', 'Ocurrió un error al intentar guardar los cambios. ');
            }

            return redirect()->back();
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
    public function destroy($id, Request $request)   // Esta eliminación es "LÒGICA" solo se modifican los registros borradoAl y borradoUsuario
    {
        $msgGeneral     = '';
        $type           = 'flash_message_info';
        $type2          = 'error';
        
        $operador = Operador::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->can('delete.operadores') && Auth::user()->activo==1) {
                if ($operador->id) {
                    $asignaciones = Asignacion::select('id')->where('idOperador', $id)->where('idSitio', Auth::user()->idSitio)->where('terminado', 0)->where('borrado', 0)->get();
                    $checkIn = CheckOperador::select('id')->where('idOperador', $id)->where('idSitio', Auth::user()->idSitio)->where('checkOut', NULL)->where('borrado', 0)->get();
                    $servicios = Servicio::select('id')->where('idOperador', $id)->where('idSitio', Auth::user()->idSitio)->where('fechaFin', NULL)->where('cancelado', 0)->get();
                    if(count($asignaciones)<=0 && count($checkIn)<=0 && count($servicios)<=0) {
                        try {
                            $updates = DB::table('Operadores')
                                ->where('idSitio', Auth::user()->idSitio)
                                ->where('id', '=', $id)
                                ->update([
                                    'borrado'        => 1,
                                    'borradoAl'      => date('Y-m-d H:m:s'),
                                    'borradoUsuario' => Auth::user()->id
                                ]);
                                $updatesPhones = DB::table('TelefonosOperadores')
                                ->where('idOperador', '=', $id)
                                ->update([
                                    'borrado'        => 1,
                                    'borradoAl'      => date('Y-m-d H:m:s'),
                                    'borradoUsuario' => Auth::user()->id
                                ]);
                            if ($updates) {
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
                        $msgGeneral = 'No se puede borrar el operador, verifique sus Asignaciones, CheckIn y Servicios.';
                        $type2      = 'error';
                    }
                } else {
                    $msgGeneral = 'No se encuentra el elemento a borrar!';
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

    /**
     * Search autocomplete element at the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
      $term = $request->input('q');
	
	  $results = array();
	
	  $queries = Operador::select('Operadores.id', 'Operadores.nombreCompleto')
		 ->where('nombreCompleto', 'LIKE', '%'.$term.'%')
         ->where('idSitio', Auth::user()->idSitio)
         ->where('activo',1)
         ->where('borrado',0)
         ->orderBy('nombreCompleto', 'ASC')
		 ->take(100)->get();
      return response()->json($queries);
    }

}
