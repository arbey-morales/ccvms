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
        <h2><i class="fa fa-hospital-o"></i> CLUE <i class="fa fa-angle-right text-danger"></i><small> Nuevo</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('catalogo.clue.index') }}">
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
            {!! Form::open([ 'route' => 'catalogo.clue.store', 'id' => 'personas-form', 'method' => 'POST', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               
               <div class="bt-form__wrapper">                    
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('clues', '* Clave clues', ['for' => 'clues'] ) !!}
                                {!! Form::text('clues', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'clues', 'autocomplete' => 'off', 'placeholder' => '* Clave clues' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('nombre', '* Nombre', ['for' => 'nombre'] ) !!}
                                {!! Form::text('nombre', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 50]', 'id' => 'nombre', 'autocomplete' => 'off', 'placeholder' => '* Nombre' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 50 caracteres</span>
                            </div>
                        </div>
                    </div>
                   <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Jurisdicción</span>                              
                                {!! Form::label('jurisdicciones_id', '* Jurisdicción', ['for' => 'jurisdicciones_id'] ) !!}
                                {!! Form::select('jurisdicciones_id', $jurisdicciones,  1, ['class' => 'form-control js-data-jurisdiccion select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'jurisdicciones_id',  'data-placeholder' => '* Jurisdicción', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                               <span class="select-label">* Municipio</span>
                                {!! Form::label('municipios_id', '* Municipio', ['for' => 'municipios_id'] ) !!}
                                {!! Form::select('municipios_id', $municipios,  1, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipios_id',  'data-placeholder' => '* Municipio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Localidad</span>
                                {!! Form::label('localidades_id', '* Localidad', ['for' => 'localidades_id'] ) !!}
                                {!! Form::select('localidades_id', $localidades, 1, ['class' => 'form-control js-data-localidad select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'localidades_id',  'data-placeholder' => '* Localidad', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">   
                                <span class="select-label">Institución</span>
                                {!! Form::label('instituciones_id', 'Institución', ['for' => 'instituciones_id'] ) !!}
                                {!! Form::select('instituciones_id', $instituciones,  1, ['class' => 'form-control js-data-institucion select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'instituciones_id',  'data-placeholder' => '* Afilación', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper"> 
                                <span class="select-label">Tipología</span>                                                         
                                {!! Form::label('tipologias_id', 'Tipología', ['for' => 'tipologias_id'] ) !!}
                                {!! Form::select('tipologias_id', $tipologias, 1, ['class' => 'form-control js-data-tipologia select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'tipologias_id',  'data-placeholder' => '* Tipología', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                
                            </div>
                        </div>
                    </div>
                </div>                
            
                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-eraser"></i> Limpiar</button>
                    @role('admin|root')
                        @permission('create.catalogos')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar</button>@endpermission
                    @endrole
                </div>
            {!! Form::close() !!}
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
               
        $("#personas-form").submit(function(e){
            e.preventDefault();
            $.post($(this).attr('action'),$(this).serialize(), function(response, status){ // Envía formulario
                if(status=='success'){
                    notificar(response.titulo,response.texto,response.estatus,5000);
                } else {
                    notificar('Error','Error en el servidor','No se guardó el registro','error',3000);
                }
            }).fail(function(){ 
                notificar('Información','No se guardó el registro verifique los datos o recargue la página','error',4000);
            });
        });
   
        // INICIA SELECT2 PARA ESTOS SELECTORES
        $(".js-data-jurisdiccion,.js-data-municipio,.js-data-tipologia,.js-data-institucion,.js-data-localidad").select2();
            
        
    </script>
@endsection