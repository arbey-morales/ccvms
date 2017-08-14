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
        <h2><i class="fa fa-group"></i> Censo nominal <i class="fa fa-angle-right text-danger"></i><small> Nuevo</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('persona.index') }}">
                        <i class="fa fa-long-arrow-left"></i>
                    </a>
                </li>
                <li>
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('errors.msgAll') <!-- Mensages -->
            {!! Form::open([ 'route' => 'persona.store', 'id' => 'personas-form', 'method' => 'POST', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               
               <div class="bt-form__wrapper">
                    <div class="bt-flabels__wrapper">
                        <span class="select-label">* Unidad de salud</span>
                        {!! Form::label('clue_id', '* Unidad de salud', ['for' => 'clue_id'] ) !!}
                        {!! Form::select('clue_id', $clues,  1, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clue_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                        <span class="bt-flabels__error-desc">Requerido</span>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('paterno', '* Apellido Paterno', ['for' => 'paterno'] ) !!}
                                {!! Form::text('paterno', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 30]', 'id' => 'paterno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Paterno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 30 caracteres</span>                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('materno', '* Apellido Materno', ['for' => 'materno'] ) !!}
                                {!! Form::text('materno', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 30]', 'id' => 'materno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Materno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 30 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('nombre', '* Nombre(s)', ['for' => 'nombre'] ) !!}
                                {!! Form::text('nombre', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 50]', 'id' => 'nombre', 'autocomplete' => 'off', 'placeholder' => '* Nombre(s)' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 50 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_nacimiento', '* Fecha de nacimiento', ['for' => 'fecha_nacimiento'] ) !!}
                                {!! Form::text('fecha_nacimiento', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'fecha_nacimiento', 'autocomplete' => 'off', 'placeholder' => '* Fecha de nacimiento' ]  ) !!}
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
                                {!! Form::select('genero', ['F' => 'F - Femenino', 'M' => 'M - Masculino'],  'F', ['class' => 'form-control js-data-genero select2', 'data-parsley-required' => 'true', 'id' => 'genero',  'data-placeholder' => '* Género', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">* Entidad federativa de nacimiento</span>                              
                                {!! Form::label('entidad_federativa_nacimiento_id', '* Entidad federativa de nacimiento', ['for' => 'entidad_federativa_nacimiento_id'] ) !!}
                                {!! Form::select('entidad_federativa_nacimiento_id', $estados,  $clue_selected->entidades_id, ['class' => 'form-control js-data-estado select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'entidad_federativa_nacimiento_id',  'data-placeholder' => '* Entidad federativa de nacimiento', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('curp', '* CURP', ['for' => 'curp'] ) !!}
                                {!! Form::text('curp', null , ['class' => 'form-control', 'style' => 'font-size:x-large; color:tomato;', 'data-parsley-required' => 'true', 'id' => 'curp', 'autocomplete' => 'off', 'placeholder' => '* CURP' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 17 - Máx: 18 Caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                <span class="select-label">* Tipo de parto</span>
                                {!! Form::label('tipo_parto_id', '* Tipo de parto', ['for' => 'tipo_parto_id'] ) !!}
                                {!! Form::select('tipo_parto_id', $partos,  1, ['class' => 'form-control js-data-parto select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'tipo_parto_id',  'data-placeholder' => '* Tipo de parto', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('tutor', '* Nombre del tutor', ['for' => 'tutor'] ) !!}
                                {!! Form::text('tutor', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[10, 100]', 'id' => 'tutor', 'autocomplete' => 'off', 'placeholder' => '* Nombre del tutor' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 10 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_nacimiento_tutor', '* Fecha de nacimiento tutor', ['for' => 'fecha_nacimiento_tutor'] ) !!}
                                {!! Form::text('fecha_nacimiento_tutor', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'fecha_nacimiento_tutor', 'autocomplete' => 'off', 'placeholder' => '* Fecha de nacimiento tutor' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Municipio</span>
                                {!! Form::label('municipio_id', '* Municipio', ['for' => 'municipio_id'] ) !!}
                                {!! Form::select('municipio_id', $municipios,  $clue_selected->municipios_id, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipio_id',  'data-placeholder' => '* Municipio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">   
                                <span class="select-label">* Localidad</span>
                                {!! Form::label('localidad_id', '* Localidad', ['for' => 'localidad_id'] ) !!}
                                {!! Form::select('localidad_id', $localidades, $clue_selected->localidades_id, ['class' => 'form-control js-data-localidad select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'localidad_id',  'data-placeholder' => '* Localidad', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">AGEB</span>
                                {!! Form::label('ageb_id', 'AGEB', ['for' => 'ageb_id'] ) !!}
                                {!! Form::select('ageb_id', $agebs, 0, ['class' => 'form-control js-data-ageb select2', 'id' => 'ageb_id', 'data-placeholder' => 'Ageb', 'style' => 'width:100%'] ) !!}
                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('sector', 'Sector', ['for' => 'sector'] ) !!}
                                {!! Form::text('sector', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 3]', 'id' => 'sector', 'autocomplete' => 'off', 'placeholder' => 'Sector' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 3 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">   
                                {!! Form::label('manzana', 'Manzana', ['for' => 'manzana'] ) !!}
                                {!! Form::text('manzana', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 3]', 'id' => 'manzana', 'autocomplete' => 'off', 'placeholder' => 'Manzana' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 3 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('descripcion_domicilio', 'Descripción domicilio', ['for' => 'descripcion_domicilio'] ) !!}
                                {!! Form::text('descripcion_domicilio', null , ['class' => 'form-control', 'id' => 'descripcion_domicilio', 'autocomplete' => 'off', 'placeholder' => 'Descripción domicilio' ]  ) !!}
                                <!--<span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>-->
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('calle', '* Calle', ['for' => 'calle'] ) !!}
                                {!! Form::text('calle', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 100]', 'data-parsley-required' => 'true', 'id' => 'calle', 'autocomplete' => 'off', 'placeholder' => '* Calle' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 1 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('numero', '* No.', ['for' => 'numero'] ) !!}
                                {!! Form::text('numero', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 5]', 'data-parsley-required' => 'true', 'id' => 'numero', 'autocomplete' => 'off', 'placeholder' => '* No.' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido /    Mín: 1 - Máx: 5 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('colonia', 'Colonia', ['for' => 'colonia'] ) !!}
                                {!! Form::text('colonia', null , ['class' => 'form-control', 'id' => 'colonia', 'autocomplete' => 'off', 'placeholder' => 'Colonia' ]  ) !!}
                                <!--<span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>-->
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right"> 
                                {!! Form::label('codigo_postal', 'Código Postal.', ['for' => 'codigo_postal'] ) !!}
                                {!! Form::text('codigo_postal', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 5]', 'id' => 'codigo_postal', 'autocomplete' => 'off', 'placeholder' => 'Código Postal' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 5 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">   
                                <span class="select-label">Afiliación</span>
                                {!! Form::label('institucion_id', 'Afiliación', ['for' => 'institucion_id'] ) !!}
                                {!! Form::select('institucion_id', $instituciones,  0, ['class' => 'form-control js-data-institucion select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'institucion_id',  'data-placeholder' => '* Afilación', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">Código</span>                                                         
                                {!! Form::label('codigo_id', 'Código', ['for' => 'codigo_id'] ) !!}
                                {!! Form::select('codigo_id', $codigos, 0, ['class' => 'form-control js-data-codigo select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'codigo_id',  'data-placeholder' => '* Código', 'style' => 'width:100%'] ) !!}
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
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-eraser"></i> Limpiar</button>
                    @permission('create.personas')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar</button>@endpermission
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
                <h3 class="modal-title" id="myModalLabel"> <i class="fa fa-excalmation-circle" style="padding-right:15px;"></i>  Información <span id="dosis"></span> </h3>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-lg btn-detalle" data-dismiss="modal">Ok!</button>
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

    <!-- Select2 personalizado -->
    <script>
        // MASCARA TIPO DD-MM-AAAA
        $("#fecha_nacimiento_tutor,#fecha_nacimiento").mask("99-99-9999");
        // GUARADARÁ EL ESQUEMA SELECCIONADO
        var ultimo_esquema = ''; 
        var ultima_fecha_nacimiento = '';

        // CARGA EL ESQUEMA CON BASE A LA FECHA 01-01-AÑO ACTUAL
        setTimeout(function() {          
            conseguirEsquema(moment().format('YYYY'),"01-01-"+moment().format('YYYY'));
        }, 500);
       
        $("#personas-form").submit(function(e){
            e.preventDefault();
            $.post($(this).attr('action'),$(this).serialize(), function(response, status){ // Envía formulario
                if(status=='success'){
                    notificar(response.titulo,response.texto,response.estatus,5000);
                    if(response.estatus=='success'){
                        $("#nombre,#paterno,#materno,#fecha_nacimiento,#curp,#sector,#manzana,#descripcion_domicilio,#calle,#numero,#codigo_postal,#fecha_nacimiento_tutor,#tutor").val('');
                        conseguirEsquema(moment().format('YYYY'),"01-01-"+moment().format('YYYY'));
                        $("#paterno").focus();
                    }
                } else {
                    notificar('Error','Error en el servidor','No se guardó el registro','error',3000);
                }
            }).fail(function(){ 
                notificar('Información','No se guardó el registro verifique los datos o recargue la página','error',4000);
            });
        });
        
        // EQUIVALENCIA DE CLAVES ESTADOS
        var estados_equivalencia = ["X","AS","BC","BS","CC","CL","CM","CS","CH","DF","DG","GT","GR","HG","JC","MC","MN","MS","NT","NL","OC","PL","QT","QR","SP","SL","SR","TC","TS","TL","VZ","YN","ZS"];
        var localidad = { 'id':null, 'nombre':'Localidad'};

        // INICIA SELECT2 PARA ESTOS SELECTORES
        $(".js-data-clue,.js-data-ageb,.js-data-genero,.js-data-parto,.js-data-estado,.js-data-municipio,.js-data-codigo,.js-data-institucion,.js-data-localidad").select2();
        
        // SI CAMBIAN ESTOS SELECTS VALIDAR LOS CAMPOS DE ENTRADA PARA VALIDAR CURP
        $(".js-data-estado,.js-data-genero").change(function(){
            setTimeout(function(){ validarCamposCURP(); }, 1000);
        });

        // OBTIENE VALUE DE FECHA DE APLICACIÓN Y SE ENVÍA A VALIDACIÓN
        function validaAplicacion(id_vacuna_esquema, index){ //id_esquema y key del arreglo en js
            if (moment($("#fecha_aplicacion"+id_vacuna_esquema).val(),'DD-MM-YYYY').isValid()) {
                comprobarFecha($("#fecha_aplicacion"+id_vacuna_esquema).val(), $("#fecha_aplicacion"+id_vacuna_esquema).attr('data-placeholder'), 3, index);
                /* @params: (fecha de aplicaión, texto que describe la aplicación, 3 = pertenece a aplicaciones, index del arreglo de vacunas esquemas )*/
            } else {
                notificar('Información','Verifique la fecha de aplicación de '+$("#fecha_aplicacion"+id_vacuna_esquema).attr('data-placeholder'),'info',3000);
            }            
        }

        // CADA QUE SE COLOCA UNA FECHA DE  NACIMIENTO DEL INFANTE SE ENVÍA A VALIDACIÓN
        $("#fecha_nacimiento").blur(function(){
            if (moment($(this).val(),'DD-MM-YYYY').isValid()) {
                 comprobarFecha($(this).val(), $(this).attr('placeholder'), 1, null);
            } else {
                $(this).val(ultima_fecha_nacimiento);
                notificar('Información','Verifique la fecha de nacimiento del infante.\n \n Se cargará la última fecha valida '+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').format('LL'),'info',5000);
            }            
        });

        // CADA QUE SE COLOCA UNA FECHA DE  DEL TUTOR SE ENVÍA A VALIDACIÓN
        $("#fecha_nacimiento_tutor").blur(function(){
            if (moment($(this).val(),'DD-MM-YYYY').isValid()) {
                 comprobarFecha($(this).val(), $(this).attr('placeholder'), 2, null);
            } else {
                notificar('Información','Verifique la fecha de nacimiento del tutor','info',3000);
            }            
        });
        
        // SI LA CLUE CAMBIA; SE SELECCIONAN SU LOCALIDAD Y MUNICIPIO
        $(".js-data-clue").change(function(){
            var clue_id = $(this).val();
            $.get('../catalogo/clue/'+clue_id, function(response, status){ // Consulta CURP
                $(".js-data-estado").val(response.data.entidades_id).trigger("change");
                $(".js-data-municipio").val(response.data.municipios_id).trigger("change");
                $(".js-data-localidad").val(response.data.localidades_id).trigger("change");
            }).fail(function(){  // Calcula CURP
                notificar('Información','No se consultaron los detalles de la unidad de salud','warning',2000);
            });
        });

        // CADA QUE ESTOS ELEMENTOS PIERDEN EL FOCO, SE VALIDAN PARA CONSULTAR LA CURP
        $("#fecha_nacimiento,#paterno,#materno,#nombre").blur(function(){            
            setTimeout(function(){ validarCamposCURP(); }, 1000);
        });

        // SE ENCARGA DE VALIDAR EL FORMATO Y LA EXISTENCIA DE LA FECHA PROPORCIONADA COMO PARAMETRO
        function comprobarFecha(fecha,texto,tipo_fecha,index){               
            var errors = 0;
            var mensaje = '';
            var titulo = '';            
            if(tipo_fecha==1){ // FECHA DE NACIMENTO 
                var temp = fecha.split("-"); // fecha recibida partida 
                if (moment(fecha,'DD-MM-YYYY') > moment())  {                          
                    errors++; mensaje='Marty McFly! No puedes agregar niños nacidos el '+moment(fecha,'DD-MM-YYYY').format('LL')+', Ellos aún no nacen! \n \n Se cargará la última fecha valida '+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').format('LL');
                    fecha = ultima_fecha_nacimiento;
                    temp = fecha.split("-");
                }
                if(fecha!=ultima_fecha_nacimiento){
                    conseguirEsquema(temp[2],fecha);
                }
            }
            if(tipo_fecha==2){ // FECHA DE NACIMENTO DEL TUTOR 
                if (moment(fecha,'DD-MM-YYYY') > moment() || moment(fecha,'DD-MM-YYYY') >= moment(ultima_fecha_nacimiento,'DD-MM-YYYY'))  {                          
                    errors++; mensaje='El tutor debe haber nacido ya y además antes que '+$("#nombre").val();
                }
            }
            if(tipo_fecha==3){ // APLICACIONES-DOSIS
                if (moment(ultima_fecha_nacimiento,'DD-MM-YYYY').isValid()) {
                    var dias_diferencia_nacimiento = moment(fecha,'DD-MM-YYYY').diff(moment(ultima_fecha_nacimiento,'DD-MM-YYYY'), 'days');
                    var dias_diferencia_hoy = moment(fecha,'DD-MM-YYYY').diff(moment(), 'days');
                    if(dias_diferencia_nacimiento>=0 && dias_diferencia_hoy<=0){ // SI ES MAYOR QUE EL MACIMIENTO Y MENOR A MAÑANA
                        if (ultimo_esquema[index]) { // sabemos que tiene un esquema y tiene datos a validar
                            var aplicacion_actual = ultimo_esquema[index];                       
                            // EDAD IDEAL DE APLICACIÓN, DÍAS DE DIREFENCIA ENTRE LA FECHA PRONOSTICADA COMO IDEAL Y LA FECHA DE NACIMIENTO
                            if(aplicacion_actual.menores.length){ // Tiene dosis menores y hay que ver si son ideales
                                var este = [];
                                $.each( ultimo_esquema, function( ins, apl ) {
                                    if(aplicacion_actual.menores[0].id==apl.id){
                                        este = apl;
                                        return false;
                                    }                                        
                                });
                                
                                var este_mayor = [];
                                if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES                                     
                                    $.each( ultimo_esquema, function( ins, apl ) {
                                        if(aplicacion_actual.mayores[0].id==apl.id){  
                                            este_mayor = apl; 
                                            return false;
                                        }                                        
                                    });
                                }

                                if(este.es_ideal) { // la dosis anterior es ideal, validar conforme a rango de dosis actual
                                    var dias_ideal = moment(fecha,'DD-MM-YYYY').diff(moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add(aplicacion_actual.edad_ideal, 'days'), 'days');
                                    console.log('anterior es ideal');
                                    if(dias_ideal<=0) {
                                        $("#intervalo_text"+aplicacion_actual.id).html(conseguirIntervalo(parseInt(aplicacion_actual.etiqueta_ideal)));
                                        ultimo_esquema[index].es_ideal = true;                            
                                        var dias_superior = moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_fin)), 'days').diff(moment(fecha,'DD-MM-YYYY'), 'days');
                                        var dias_inferior = moment(fecha,'DD-MM-YYYY').diff(moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_inicio) - parseInt(aplicacion_actual.margen_anticipacion)), 'days'), 'days');
                                        if(dias_inferior>=0 && dias_inferior<=parseInt(aplicacion_actual.intervalo_fin) && dias_superior>=0 && dias_superior<=parseInt(aplicacion_actual.intervalo_fin)) {                            
                                            // Si la fecha es valida                               
                                        } else { 
                                            errors++; mensaje='Se puede aplicar desde: '+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_inicio) - parseInt(aplicacion_actual.margen_anticipacion)), 'days').format('LL')+' hasta el '+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_fin)), 'days').format('LL');
                                        }
                                    } else { // LA DOSIS SIGUIENTE HAY QUE MOFIFICARLA
                                        //$("#intervalo_text"+aplicacion_actual.id).html(conseguirIntervalo(parseInt(aplicacion_actual.etiqueta_no_ideal)));
                                        ultimo_esquema[index].es_ideal = false;
                                        var dias_entre_aplicaciones = moment(fecha,'DD-MM-YYYY').diff(moment($("#fecha_aplicacion"+este.id).val(),'DD-MM-YYYY').add(parseInt(este.dias_entre_siguiente_dosis) - parseInt(aplicacion_actual.margen_anticipacion), 'days'), 'days');
                                        if(dias_entre_aplicaciones>0) {
                                        } else {
                                            errors++; mensaje='Se debe aplicar después del '+moment($("#fecha_aplicacion"+este.id).val(),'DD-MM-YYYY').add(parseInt(este.dias_entre_siguiente_dosis) - parseInt(aplicacion_actual.margen_anticipacion), 'days').format('LL');
                                        }
                                    }
                                } else { // la dosis anterior NO es ideal, validar conforme intervalo establecido
                                    console.log('anterior no es ideal');
                                    ultimo_esquema[index].es_ideal = false;
                                    $("#intervalo_text"+aplicacion_actual.id).html(conseguirIntervalo(parseInt(aplicacion_actual.etiqueta_no_ideal)));
                                    var dias_entre_aplicaciones = moment(fecha,'DD-MM-YYYY').diff(moment($("#fecha_aplicacion"+este.id).val(),'DD-MM-YYYY').add(parseInt(este.dias_entre_siguiente_dosis) - parseInt(aplicacion_actual.margen_anticipacion), 'days'), 'days');
                                    if(dias_entre_aplicaciones>0) {
                                    } else {
                                        errors++; mensaje='Se debe aplicar después del '+moment($("#fecha_aplicacion"+este.id).val(),'DD-MM-YYYY').add(parseInt(este.dias_entre_siguiente_dosis) - parseInt(aplicacion_actual.margen_anticipacion), 'days').format('LL');
                                    }
                                }  
                            } else { // Significa que es la primera aplicación de la vacuna
                                var este = [];                                
                                if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES                                     
                                    $.each( ultimo_esquema, function( ins, apl ) {
                                        if(aplicacion_actual.mayores[0].id==apl.id){                                            
                                            este = apl;
                                            return false;
                                        }                                        
                                    });
                                }
                                var dias_ideal = moment(fecha,'DD-MM-YYYY').diff(moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add(aplicacion_actual.edad_ideal, 'days'), 'days');
                                if(dias_ideal<0) { // No tiene menores y es ideal
                                    ultimo_esquema[index].es_ideal = true;
                                    // ES IDEAL: es decir la aplicación es antes de la edad máxima ideal, por lo tanto no se modifican la dosis siguientes 
                                    $("#intervalo_text"+aplicacion_actual.id).html(conseguirIntervalo(parseInt(aplicacion_actual.etiqueta_ideal)));
                                    if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES
                                        $("#intervalo_text"+este.id).html(conseguirIntervalo(parseInt(este.edad_ideal)));
                                    }
                                } else { // No tiene menores y NO es ideal
                                    ultimo_esquema[index].es_ideal = false;
                                    $("#intervalo_text"+aplicacion_actual.id).html(conseguirIntervalo(parseInt(aplicacion_actual.etiqueta_no_ideal)));
                                    if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES
                                        $("#intervalo_text"+este.id).html(conseguirIntervalo(parseInt(este.etiqueta_no_ideal)));
                                    }
                                }

                                //  Como no tiene menores se evalua con el mismo rango                             
                                var dias_superior = moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_fin)), 'days').diff(moment(fecha,'DD-MM-YYYY'), 'days');
                                var dias_inferior = moment(fecha,'DD-MM-YYYY').diff(moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_inicio) - parseInt(aplicacion_actual.margen_anticipacion)), 'days'), 'days');
                                if(dias_inferior>=0 && dias_inferior<=parseInt(aplicacion_actual.intervalo_fin) && dias_superior>=0 && dias_superior<=parseInt(aplicacion_actual.intervalo_fin)) {                            
                                    // Si la fecha es valida                               
                                } else { 
                                    errors++; mensaje='Se puede aplicar desde: '+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_inicio) - parseInt(aplicacion_actual.margen_anticipacion)), 'days').format('LL')+' hasta el '+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add((parseInt(aplicacion_actual.intervalo_fin)), 'days').format('LL');
                                }  
                            }                                                                                 
                        } else {
                            errors++; mensaje='No se encontraron los detalles de la dosis a aplicar';
                        }
                    } else {
                        errors++; mensaje='La fecha: '+moment(fecha,'DD-MM-YYYY').format('LL')+', que acaba de agregar debe ser mayor o igual a la fecha de nacimiento y menor al día de mañana';
                    }
                } else {
                    $("input[name*='fecha_aplicacion']").val('');
                    $("#fecha_nacimiento").focus();
                    errors++; mensaje='Seleccione una fecha de nacimiento primero';
                }
                
            }
            if(errors>0){
                notificar(texto , mensaje, 'danger', 4000);
            }
        }

        // CONSULTA POR :GET: EL ESQUEMA
        function conseguirEsquema(esquema,fecha_nacimiento) {
            $('#title-esquema').empty().html('Buscando esquema '+esquema);
            $('#content-esquema').empty().html('<div class="col-md-12 text-center"> <i class="fa fa-circle-o-notch fa-spin" style="font-size:x-large;"></i> </div> ');
            $.get('../catalogo/esquema/'+esquema, {fecha_nacimiento:fecha_nacimiento}, function(response, status){ // Consulta esquema
                if(response.data==null){
                    $('#fecha_nacimiento').val('');
                    notificar('Información','No se encontró el esquema que buscas','warning',4000);
                    $('#title-esquema').empty().html('No se encuentra el esquema: '+esquema+'. ');
                    $('#content-esquema').empty().html('<div class="col-md-12 text-center text-danger"> <h2 class="text-danger"> <i class="fa fa-info-circle text-info"></i> Imposible encontrar el esquema '+esquema+'. Seleccione otra fecha de nacimiento o asegurese que exista el esquema que busca. </h2></div> ');
                } else {                    
                    if(response.data.length<=0){
                        notificar('Información','Al esquema no se le han programado aplicaciones, verifique!','warning',4000);
                        $('#content-esquema').empty().html('Sin aplicaciones programadas, verifique!');
                    } else {
                        notificar('Información','Cargando esquema','info',2000);
                        $('#content-esquema').empty();
                    }  
                    $('#title-esquema').empty().html('<a class="btn btn-danger btn-lg"><i class="fa fa-calendar"></i> '+response.esquema.descripcion+'</a>  <a class="btn btn-lg btn-default">'+response.letra_edad+'</a>');
                    $('#fecha_nacimiento').val(fecha_nacimiento);
                    ultima_fecha_nacimiento = fecha_nacimiento;
                    generarEsquema(response.data);
                }
            }).fail(function(){ 
                $('#fecha_nacimiento').val('');                  
                $('#title-esquema').empty().html('No se encuentra el esquema: '+esquema+'. ');
                $('#content-esquema').empty().html('<div class="col-md-12 text-center text-danger"> <h2 class="text-danger"> <i class="fa fa-info-circle text-info"></i> Imposible encontrar el esquema '+esquema+'. Seleccione otra fecha de nacimiento o asegurese que exista el esquema que busca. </h2></div> ');
            });
        }

        // GENERA EL ESQUEMA DENTRO DEL DIV-CONTENT
        function generarEsquema(aplicaciones){
            ultimo_esquema = aplicaciones; // LAS VALIDACIONES DEL ESQUEMA ESTÁN AQUÍ
            var key_plus = 0;
            $.each(aplicaciones, function( key, ve ) {
                key_plus++;                        
                var col_md = 12;
                var plu_col_md = 0;
                $.each(aplicaciones,  function( k, v ){
                    if(ve.fila==v.fila){
                        plu_col_md++;
                    } 
                });
                col_md = 12 / plu_col_md; // numero de columnas por fila
                var tipo_aplicacion = '';
                var intervalo_inicio = '';
                if(ve.tipo_aplicacion==1) {
                    tipo_aplicacion = 'Dosis única';
                } 
                if(ve.tipo_aplicacion==2) {
                    tipo_aplicacion = '1a Dosis';
                } 
                if(ve.tipo_aplicacion==3) {
                    tipo_aplicacion = '2a Dosis';
                }
                if(ve.tipo_aplicacion==4){ 
                    tipo_aplicacion = '3a Dosis'; 
                }
                if(ve.tipo_aplicacion==5){ 
                    tipo_aplicacion = '4a Dosis'; 
                }
                if(ve.tipo_aplicacion==6) {
                    tipo_aplicacion = 'Refuerzo';
                }
                var placeholder = '';
                if(ve.etiqueta_ideal<30){
                    placeholder = ultima_fecha_nacimiento;
                }
                ve.es_ideal = true;
                intervalo_inicio = conseguirIntervalo(ve.etiqueta_ideal);                
                if(aplicaciones.length - 1 > key){ // último registro de esquemasvacunas
                    $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #'+ve.color_rgb+'; background-color:#'+ve.color_rgb+' !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipo_aplicacion+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#'+ve.color_rgb+'" id="intervalo_text'+ve.id+'">'+intervalo_inicio+'</span> </span> </div> </div> <div class="row"> <div class="bt-flabels__wrapper"> <input id="fecha_aplicacion'+ve.id+'" name="fecha_aplicacion'+ve.id+'" type="text" onBlur="validaAplicacion('+ve.id+','+key+')" placeholder="'+placeholder+'" data-placeholder="'+ve.clave+' '+tipo_aplicacion+'('+intervalo_inicio+')" value="" class="form-control has-feedback-left" aria-describedby="inputSuccess2Status" autocomplete="off" style="font-size:x-large; text-align:center;"> </div> </div> </div> </div>');
                    if(aplicaciones[key_plus].fila != ve.fila){
                        $('#content-esquema').append('<div class="clearfix"></div>');
                    }
                } else {
                    $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #'+ve.color_rgb+'; background-color:#'+ve.color_rgb+' !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipo_aplicacion+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#'+ve.color_rgb+'" id="intervalo_text'+ve.id+'">'+intervalo_inicio+'</span> </span> </div> </div> <div class="row"> <div class="bt-flabels__wrapper"> <input id="fecha_aplicacion'+ve.id+'" name="fecha_aplicacion'+ve.id+'" type="text" onBlur="validaAplicacion('+ve.id+','+key+')" placeholder="'+placeholder+'" data-placeholder="'+ve.clave+' '+tipo_aplicacion+'('+intervalo_inicio+')" value="" class="form-control has-feedback-left" aria-describedby="inputSuccess2Status" autocomplete="off" style="font-size:x-large; text-align:center;"> </div> </div> </div> </div>');
                }
            });

            // APLICA MASCARA DD-MM-AAAA PARA LAS FECHAS DE APLICACIÓN
            setTimeout(function() {
                $("input[name*='fecha_aplicacion']").mask("99-99-9999");
            }, 100);
        }

        // VERIFICA QUE LOS CAMPOS PARA LA CURP SEAN CORRECTOS
        function validarCamposCURP(){            
            var estado = $(".js-data-estado").val();
            var born_state =  estados_equivalencia[estado];
            if($(".js-data-genero").val()=="M") {
                var gender = 1;
            }
            if($(".js-data-genero").val()=="F") {
                var gender = 2;
            }
            var fn_validar = reemplazarTodo($('#fecha_nacimiento').val(),"-", "/");
            var born_date = fn_validar.split('/');
            var name = $('#nombre').val();
            var father_surname = $('#paterno').val();
            var mother_surname = $('#materno').val();
            var errors = 0;
            var warning = '';
            
            if(validarFormatoFecha(fn_validar)){ 
                if(!existeFecha(fn_validar)){
                        errors++; warning+= "La fecha que introdujo no existe. \n";
                }
            }else{
                errors++; warning+= "El formato de la fecha es incorrecto. \n";
            }

            if(gender=="" || gender==null){
                errors++; warning+= "El género debe ser Masculino o Femenino. \n";
            }

            if(name.length<2){
                errors++; warning+= "Longitud de nombre no válida. \n";
            }

            if(father_surname.length<2){
                errors++; warning+= "Longitud de apellido paterno no válida. \n";
            }

            if(mother_surname.length<2){
                errors++; warning+= "Longitud de apellido materno no válida. \n";
            }

            if(errors==0) {
                // Sending form
                var form = $("#personas-form");
                var data = form.serialize();
                $.get('curp', data, function(response, status){ // Consulta CURP
                    if(response.find==true){                        
                        $("#curp").val(response.curp);
                        notificar('Información','Se encontró la CURP, asegurese que sea correcta','info',4000);
                    }
                    if(response.find==false || response.curp==""){ 
                        calcularCURP(name, father_surname, mother_surname, born_date[0], born_date[1], born_date[2], born_state, gender);
                    }
                }).fail(function(){  // Calcula CURP                    
                    calcularCURP(name, father_surname, mother_surname, born_date[0], born_date[1], born_date[2], born_state, gender);
                });

            } else {
                if(errors<2){
                    notificar('Información',warning,'info',3000);
                }
            }
        }

        function verDetalles(id){
            var apl = ultimo_esquema[id];
            $("span#dosis").html(apl.color_rgb);
            $("button.btn-detalle").attr('style',  'background-color:#'+apl.color_rgb);
            $("div.modal-body").attr('style',  'color:#'+apl.color_rgb);
            $("div.modal-header").attr('style',  'background-color:#'+apl.color_rgb);
        }

        // DEVUELVE 'UN 1M, 3A, NAC, ...' TEXTO CON BASE A LOS DIAS QUE RECIBE
        function conseguirIntervalo(dias){
            if(dias<=29) {
                return 'Nac'; 
            } else {
                if((dias/30)<=23) { 
                    return Math.round((dias/30))+'M';
                } else { 
                    return Math.round(((dias/30)/12))+'A';
                }
            }
        }

        // USA UN SCRIPT PARA CALCULAR CURP
        function calcularCURP(name, father_surname, mother_surname, born_date_d, born_date_m, born_date_a, born_state, gender){
            var  curp = mxk.getCURP(name, father_surname, mother_surname, born_date_d, born_date_m, born_date_a, born_state, gender);
            $("#curp").val(curp);
            notificar('Información','Se CALCULÓ la CURP, verifique los datos','warning',3000);
        }

        // TRUE, SI LA FECHA TIENE FORMATO VALIDO
        function validarFormatoFecha(campo) {
            var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
            if ((campo.match(RegExPattern)) && (campo!='')) {
                    return true;
            } else {
                    return false;
            }
        }

        // VALIDA SI EXISTE LA FECHA
        function existeFecha(fecha){
            var fechaf = fecha.split("/");
            var d = fechaf[0];
            var m = fechaf[1];
            var y = fechaf[2];
            return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
        }

        // REEMPLAZA LO QUE SE LE PIDA EN UNA CADENA
        function reemplazarTodo(str, find, replace) {
            return str.replace(new RegExp(find, 'g'), replace);
        }                
        
    </script>
@endsection