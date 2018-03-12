@extends('app')
@section('title')
   Censo nominal
@endsection
@section('my_styles')
    <!-- Select2 -->
    {!! Html::style('assets/vendors/select2-4.0.3/dist/css/select2.css') !!}
    <!-- Switchery -->
    {!! Html::style('assets/vendors/switchery/dist/switchery.min.css') !!}
    <!-- Bootstrap Colorpicker -->
    {!! Html::style('assets/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') !!}
    <!-- Form Mine -->
    {!! Html::style('assets/mine/css/uikit.almost-flat.min.css') !!}
    {!! Html::style('assets/mine/css/form.css') !!}

    <style>
        #paterno,#materno,#nombre,#curp,#tutor {
            text-transform:uppercase
        }
        .select-label{
            pointer-events: none;
            position: absolute;
            opacity: 1;
            top: 0;
            -webkit-transform: translateY(15%);
                    transform: translateY(15%);
            z-index: 2;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding-left: 6px;
            color: #52a6e1;
            -webkit-transition: opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), -webkit-transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
            transition: opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), -webkit-transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
            transition: transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
            transition: transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), -webkit-transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
        }
    </style>
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-group"></i> Censo Nominal: {{ $data->nombre  }} {{ $data->apellido_paterno  }} {{ $data->apellido_materno  }} <i class="fa fa-angle-right text-danger"></i><small> Editar</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('persona.index') }}">
                        <i class="fa fa-chevron-circle-left" style="font-size:30px;"></i>
                    </a>
                </li>
                <li>
                    <a class="collapse-link">
                        
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('errors.msgAll') <!-- Mensages -->
            {!! Form::model($data, ['route' => ['persona.update', $data], 'method' => 'PUT', 'id' => 'personas-form', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => '']) !!}

                <div class="bt-form__wrapper">
                    <div class="bt-flabels__wrapper">
                        <span class="select-label">* Unidad de salud </span>
                        {!! Form::label('clue_id', '* Unidad de salud', ['for' => 'clue_id'] ) !!}
                        {!! Form::select('clue_id', [],  $data->clues_id, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clue_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                        <span class="bt-flabels__error-desc">Requerido</span>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('paterno', '* Apellido Paterno', ['for' => 'paterno'] ) !!}
                                {!! Form::text('paterno', $data->apellido_paterno, ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[2, 30]', 'id' => 'paterno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Paterno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 2 - Máx: 30 caracteres</span>                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('materno', '* Apellido Materno', ['for' => 'materno'] ) !!}
                                {!! Form::text('materno', $data->apellido_materno, ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[2, 30]', 'id' => 'materno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Materno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 2 - Máx: 30 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('nombre', '* Nombre(s)', ['for' => 'nombre'] ) !!}
                                {!! Form::text('nombre', $data->nombre, ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 50]', 'id' => 'nombre', 'autocomplete' => 'off', 'placeholder' => '* Nombre(s)' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 50 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_nacimiento', '* Fecha de nacimiento', ['for' => 'fecha_nacimiento'] ) !!}
                                {!! Form::text('fecha_nacimiento', $data->fecha_nacimiento, ['class' => 'form-control has-feedback-left fecha-nacimiento',  'data-parsley-required' => 'true', 'id' => 'fecha_nacimiento', 'autocomplete' => 'off', 'placeholder' => '* Fecha de nacimiento' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Género</span>
                                {!! Form::label('genero', '* Género', ['for' => 'genero'] ) !!}
                                {!! Form::select('genero', ['F' => 'F - Femenino', 'M' => 'M - Masculino'],  $data->genero, ['class' => 'form-control js-data-genero select2', 'data-parsley-required' => 'true', 'id' => 'genero',  'data-placeholder' => '* Género', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">* Entidad federativa de nacimiento</span>                              
                                {!! Form::label('entidad_federativa_nacimiento_id', '* Entidad federativa de nacimiento', ['for' => 'entidad_federativa_nacimiento_id'] ) !!}
                                {!! Form::select('entidad_federativa_nacimiento_id', $estados,  $data->entidades_federativas_nacimiento_id, ['class' => 'form-control js-data-estado select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'entidad_federativa_nacimiento_id',  'data-placeholder' => '* Entidad federativa de nacimiento', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('curp', '* CURP', ['for' => 'curp'] ) !!}
                                {!! Form::text('curp', $data->curp, ['class' => 'form-control', 'style' => 'font-size:x-large; color:tomato;', 'data-parsley-required' => 'true', 'id' => 'curp', 'autocomplete' => 'off', 'placeholder' => '* CURP' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 17 - Máx: 18 Caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                <span class="select-label">* Tipo de parto</span>
                                {!! Form::label('tipo_parto_id', '* Tipo de parto', ['for' => 'tipo_parto_id'] ) !!}
                                {!! Form::select('tipo_parto_id', $partos,  $data->tipos_partos_id, ['class' => 'form-control js-data-parto select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'tipo_parto_id',  'data-placeholder' => '* Tipo de parto', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('tutor', '* Nombre del tutor', ['for' => 'tutor'] ) !!}
                                {!! Form::text('tutor', $data->tutor, ['class' => 'form-control', 'id' => 'tutor', 'autocomplete' => 'off', 'placeholder' => '* Nombre del tutor' ]  ) !!}
                                <!--<span class="bt-flabels__error-desc">Requerido / Mín: 10 - Máx: 100 caracteres</span>-->
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_nacimiento_tutor', '* Fecha de nacimiento tutor', ['for' => 'fecha_nacimiento_tutor'] ) !!}
                                {!! Form::text('fecha_nacimiento_tutor', $data->fecha_nacimiento_tutor, ['class' => 'form-control has-feedback-left fecha-nacimiento-tutor', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'fecha_nacimiento_tutor', 'autocomplete' => 'off', 'placeholder' => '* Fecha de nacimiento tutor' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <!--<span class="bt-flabels__error-desc">Requerido</span>-->
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Municipio/domicilio</span>
                                {!! Form::label('municipio_id', '* Municipio/domicilio', ['for' => 'municipio_id'] ) !!}
                                {!! Form::select('municipio_id', $municipios,  $data->municipios_id, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipio_id',  'data-placeholder' => '* Municipio/domicilio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">* Localidad/domicilio</span> 
                                {!! Form::label('localidad_id', '* Localidad/domicilio', ['for' => 'localidad_id'] ) !!}
                                {!! Form::select('localidad_id', [], $data->localidades_id, ['class' => 'form-control js-data-localidad select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'localidad_id',  'data-placeholder' => '* Localidad/domicilio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">AGEB</span>
                                {!! Form::label('ageb_id', 'AGEB', ['for' => 'ageb_id'] ) !!}
                                {!! Form::select('ageb_id', [], $data->agebs_id, ['class' => 'form-control js-data-ageb select2', 'id' => 'ageb_id', 'data-placeholder' => 'Ageb', 'style' => 'width:100%'] ) !!}
                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('sector', 'Sector', ['for' => 'sector'] ) !!}
                                {!! Form::text('sector', $data->sector, ['class' => 'form-control', 'data-parsley-length' => '[1, 3]', 'id' => 'sector', 'autocomplete' => 'off', 'placeholder' => 'Sector' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 3 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">   
                                {!! Form::label('manzana', 'Manzana', ['for' => 'manzana'] ) !!}
                                {!! Form::text('manzana', $data->manzana, ['class' => 'form-control', 'data-parsley-length' => '[1, 3]', 'id' => 'manzana', 'autocomplete' => 'off', 'placeholder' => 'Manzana' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 3 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('descripcion_domicilio', 'Descripción domicilio', ['for' => 'descripcion_domicilio'] ) !!}
                                {!! Form::text('descripcion_domicilio', $data->descripcion_domicilio, ['class' => 'form-control', 'id' => 'descripcion_domicilio', 'autocomplete' => 'off', 'placeholder' => 'Descripción domicilio' ]  ) !!}
                                <!--<span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>-->
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('calle', '* Calle', ['for' => 'calle'] ) !!}
                                {!! Form::text('calle', $data->calle, ['class' => 'form-control', 'data-parsley-length' => '[1, 100]', 'data-parsley-required' => 'true', 'id' => 'calle', 'autocomplete' => 'off', 'placeholder' => '* Calle' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 1 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('numero', '* No.', ['for' => 'numero'] ) !!}
                                {!! Form::text('numero', $data->numero, ['class' => 'form-control', 'data-parsley-length' => '[1, 5]', 'data-parsley-required' => 'true', 'id' => 'numero', 'autocomplete' => 'off', 'placeholder' => '* No.' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 1 - Máx: 5 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">Colonia</span>
                                {!! Form::label('colonias_id', 'Colonia', ['for' => 'colonias_id'] ) !!}
                                {!! Form::select('colonias_id', [], $data->colonias_id, ['class' => 'form-control js-data-colonia select2', 'id' => 'colonias_id', 'data-placeholder' => 'Colonia', 'style' => 'width:100%'] ) !!}
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right"> 
                                {!! Form::label('codigo_postal', 'Código Postal.', ['for' => 'codigo_postal'] ) !!}
                                {!! Form::text('codigo_postal', $data->codigo_postal, ['class' => 'form-control', 'data-parsley-length' => '[1, 5]', 'id' => 'codigo_postal', 'autocomplete' => 'off', 'placeholder' => 'Código Postal' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 5 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">  
                                <span class="select-label">Afiliación</span> 
                                {!! Form::label('institucion_id', 'Afiliación', ['for' => 'institucion_id'] ) !!}
                                {!! Form::select('institucion_id', $instituciones,  $data->instituciones_id, ['class' => 'form-control js-data-institucion select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'institucion_id',  'data-placeholder' => '* Afilación', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">Código</span>                                                          
                                {!! Form::label('codigo_id', 'Código', ['for' => 'codigo_id'] ) !!}
                                {!! Form::select('codigo_id', $codigos, $data->codigos_censos_id, ['class' => 'form-control js-data-codigo select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'codigo_id',  'data-placeholder' => '* Código', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="x_panel">
                    <div class="x_title">
                        <h2 id="title-esquema"> Esquema</h2>
                        <ul class="nav navbar-right panel_toolbox">
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" id="content-esquema">
                        <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin esquema</h3></div>
                    </div>
                </div>              
                
                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-history"></i> Restaurar</button>
                    @permission('update.personas')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar Cambios</button>@endpermission
                </div>

            {!! Form::close() !!}            
        </div>
    </div>

    <!-- Modal detalles dosis -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header alert">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title" id="myModalLabel" style="color:white !important;"> <i class="fa fa-exclamation-circle" style="padding-right:15px;"></i>  Información de <span id="dosis" ></span> </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text text-success" style="font-size:x-large; font-weight:bold;">Esquema ideal</h2>
                        <h3 id="intervalos"></h3>
                        <h3 id="fecha-ideal"></h3>
                        <h3 id="dias-anticipacion"></h3>
                    </div>
                    <div class="col-md-6">
                        <h2 class="text text-danger" style="font-size:x-large; font-weight:bold;">Esquema desfasado</h2>
                        <h3 id="intervalos-ni"></h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-lg btn-detalle" data-dismiss="modal">Entendido!</button>
                <!--<button type="button" class="btn btn-danger btn-lg btn-confirm-delete" data-dismiss="modal">Sí, eliminar</button>-->
            </div>

            </div>
        </div>
    </div>
@endsection
@section('my_scripts')
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- jQuery Tags Input -->
    {!! Html::script('assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js') !!}    
    <!-- Bootstrap Colorpicker -->
    {!! Html::script('assets/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- File Input -->
    {!! Html::script('assets/mine/js/bootstrap.file-input.js') !!}
    <!-- Form Mine -->
    {!! Html::script('assets/mine/js/parsleyjs/2.1.2/parsley.min.js') !!}
    {!! Html::script('assets/mine/js/floating-labels.js') !!}
    {!! Html::script('assets/mine/js/myCheckBox.js') !!}
    {!! Html::script('assets/mine/js/myMessage.js') !!}
    {!! Html::script('assets/mine/js/myTags.js') !!}
    {!! Html::script('assets/mine/js/mx_CURP_RFC.js') !!}
    {!! Html::script('assets/mine/js/personaEdita.js') !!}

    <script>

        // Al editar reemplaza el municipio de la clue
        var esquema = $.parseJSON(escaparCharEspeciales('{{$esquema}}'));
        var aplicaciones_dosis = $.parseJSON(escaparCharEspeciales('{{json_encode($data->personasVacunasEsquemas)}}'));
        var persona = $.parseJSON(escaparCharEspeciales('{{$data}}'));
        // GUARADARÁ EL ESQUEMA SELECCIONADO
        var ultimo_esquema = ''; 
        var ultima_fecha_nacimiento = '{{$data->fecha_nacimiento}}';
        var original_fecha_nacimiento = '{{$data->fecha_nacimiento}}';
        // CARGA EL ESQUEMA CON BASE A LA FECHA 01-01-AÑO ACTUAL
        var clues = [{'id':persona.clue.id,'clues':persona.clue.clues,'text':persona.clue.nombre}];
        var colonias = [{ 'id': 0, 'text': 'Sin colonia' }];
        var agebs = [{ 'id': persona.ageb.id, 'text': persona.ageb.id.substr(-4)+' : '+persona.ageb.localidad.nombre+' '+persona.ageb.municipio.nombre }];
        var localidades = [{ 'id': persona.localidad.id, 'clave':'', 'text': persona.localidad.nombre }];
        if(persona.colonia==null){
            persona.colonia=colonias[0];
        }

        $(document).ready(function(){
            $(".js-data-ageb").select2({
                language: "es",
                data:agebs
            });
            $(".js-data-ageb").select2("trigger", "select", { 
                data: agebs[0] 
            });
        });
        
        setTimeout(function() {  
            var anio = original_fecha_nacimiento.split("-");       
            conseguirEsquema(anio[2],ultima_fecha_nacimiento);
        }, 500);
        
        $(".js-data-clue").select2({
            ajax: {
                url: "../../catalogo/clue",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
                },
                processResults: function (data, params) {            
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: $.map(data.data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
                        return {
                            id:        item.id,
                            clues:     item.clues,
                            text:      item.nombre
                        }
                    }),
                    pagination: {
                    more: (params.page * 30) < data.total_count
                    }
                };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 5,
            language: "es",
            placeholder: {
                id: persona.clue.id, 
                clues: persona.clue.clues,
                text: persona.clue.nombre
            },
            cache: true,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });

        $(".js-data-clue").select2("trigger", "select", { 
            data: clues[0] 
        }); 


        function formatRepo (clues) {
            if (!clues.id) { return clues.text; }
            var $clues = $(
                '<span class="">' + clues.clues + ' - '+ clues.text +'</span>'
            );
            return $clues;
        };
        function formatRepoSelection (clues) {
            if (!clues.id) { return clues.text; }
            var $clues = $(
                '<span class="results-select2"> ' + clues.clues+ ' - '+ clues.text +'</span>'
            );
            return $clues;
        };

        $(".js-data-colonia").select2({
            ajax: {
                url: "../../catalogo/colonia",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    q: params.term, // search term
                    municipios_id: $(".js-data-municipio").val(),
                    page: params.page
                };
                },
                processResults: function (data, params) {            
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: $.map(data.data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
                        return {
                            id:        item.id,
                            text:      item.nombre
                        }
                    }),
                    pagination: {
                    more: (params.page * 30) < data.total_count
                    }
                };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 5,
            language: "es",
            placeholder: {
                id: colonias[0].id, 
                text: colonias[0].nombre
            },
            cache: true,
            templateResult: formatRepoColonia, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelectionColonia // omitted for brevity, see the source of this page
        });

        function formatRepoColonia (colonias) {
            if (!colonias.id) { return colonias.text; }
            var $colonias = $(
                '<span class="">'+ colonias.text +'</span>'
            );
            return $colonias;
        };
        function formatRepoSelectionColonia (colonias) {
            if (!colonias.id) { return colonias.text; }
            var $colonias = $(
                '<span class="results-select2">'+ colonias.text +'</span>'
            );
            return $colonias;
        };   
        
        $(".js-data-localidad").select2({
            ajax: {
                url: "../../catalogo/localidad",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    q: params.term, // search term
                    municipios_id: $(".js-data-municipio").val(),
                    page: params.page
                };
                },
                processResults: function (data, params) {            
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: $.map(data.data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
                        return {
                            id:        item.id,
                            clave:     item.clave,
                            text:      item.nombre
                        }
                    }),
                    pagination: {
                    more: (params.page * 30) < data.total_count
                    }
                };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 5,
            language: "es",
            placeholder: {
                id: persona.localidad.id, 
                clave: persona.localidad.clave,
                text: persona.localidad.nombre
            },
            cache: true,
            templateResult: formatRepoLocalidad, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelectionLocalidad // omitted for brevity, see the source of this page
        });

        $(".js-data-localidad").select2("trigger", "select", { 
            language: "es",
            data: localidades[0] 
        });

        function formatRepoLocalidad (localidades) {
            if (!localidades.id) { return localidades.text; }
            var $localidades = $(
                '<span class="">' + localidades.clave + ' - '+ localidades.text +'</span>'
            );
            return $localidades;
        };
        function formatRepoSelectionLocalidad (localidades) {
            if (!localidades.id) { return localidades.text; }
            var $localidades = $(
                '<span class="results-select2"> ' + localidades.clave+ ' - '+ localidades.text +'</span>'
            );
            return $localidades;
        };

        $(".js-data-localidad").change(function(){
            if($('.js-data-localidad').val()==0){ } else {
                cargarAgeb($('.js-data-localidad').val());
            }
        });
        
        function cargarAgeb(localidad){
            $('.js-data-ageb').empty();
            agebs = [{ 'id': 0, 'text': 'AGEB' }];
            $.get('../../catalogo/ageb?localidades_id='+localidad, {}, function(response, status){
                if(response.data==null){
                    //notificar('Sin resultados','warning',2000);
                } else {      
                    while (agebs.length) { agebs.pop(); }                
                    agebs.push({ 'id': 0, 'text': 'AGEB' });           
                    if(response.data.length<=0){
                        //notificar('Información','No existen AGEBS','warning',2000);
                    } else {
                        console.log(response.data)
                        //notificar('Información','Cargando AGBES','info',2000);
                        $('.js-data-ageb').empty();                      
                        $.each(response.data, function( i, cont ) {
                            agebs.push({ 'id': cont.id, 'text':cont.id.substr(-4)+' : '+cont.localidad+' '+cont.municipio });
                        });  
                    }  
                    $(".js-data-ageb").select2({
                        language: "es",
                        data: agebs
                    });
                }
            }).fail(function(){ 
                notificar('Información','Falló carga de AGEBS','danger',2000);
                $(".js-data-ageb").select2({
                    language: "es",
                    data:agebs
                }); 
            });
        }

    </script>
@endsection