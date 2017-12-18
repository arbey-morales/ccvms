@extends('app')
@section('title')
   Localidades
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
        <h2><i class="fa fa-globe"></i> localidad <i class="fa fa-angle-right text-danger"></i><small> Editar</small></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <a class="" href="{{ route('catalogo.localidad.index') }}">
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
            {!! Form::model($data, ['route' => ['catalogo.localidad.update', $data], 'method' => 'PUT', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => '']) !!}

                <div class="bt-form__wrapper">  
                    <div class="uk-grid uk-grid-collapse">                        
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('clave', '* Clave', ['for' => 'clave'] ) !!}
                                {!! Form::text('clave', $data->clave, ['class' => 'form-control', 'data-parsley-required' => 'true', 'id' => 'clave', 'autocomplete' => 'off', 'placeholder' => '* Clave' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>                                
                            </div> 
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('nombre', '* Nombre', ['for' => 'nombre'] ) !!}
                                {!! Form::text('nombre', $data->nombre, ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 100]', 'id' => 'nombre', 'autocomplete' => 'off', 'placeholder' => '* Nombre' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                    </div>                    
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Municipio</span>
                                {!! Form::label('municipios_id', '* Municipio', ['for' => 'municipios_id'] ) !!}
                                {!! Form::select('municipios_id', $municipios,  $data->municipios_id, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipios_id',  'data-placeholder' => '* Municipio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right"> 
                                {!! Form::label('clave_carta', 'Clave carta', ['for' => 'clave_carta'] ) !!}
                                {!! Form::text('clave_carta', $data->clave_carta, ['class' => 'form-control', 'id' => 'clave_carta', 'autocomplete' => 'off', 'placeholder' => 'Clave carta' ]  ) !!}
                                <span class="bt-flabels__error-desc"> </span>                                 
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper"> 
                                {!! Form::label('numero_latitud', 'Número latitud', ['for' => 'numero_latitud'] ) !!}
                                {!! Form::text('numero_latitud', $data->numero_latitud, ['class' => 'form-control', 'id' => 'numero_latitud', 'autocomplete' => 'off', 'placeholder' => 'Número latitud' ]  ) !!}
                                <span class="bt-flabels__error-desc"> </span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('numero_longitud', 'Número longitud', ['for' => 'numero_longitud'] ) !!}
                                {!! Form::text('numero_longitud', $data->numero_longitud, ['class' => 'form-control', 'id' => 'numero_longitud', 'autocomplete' => 'off', 'placeholder' => 'Número longitud' ]  ) !!}
                                <span class="bt-flabels__error-desc"> </span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper"> 
                                {!! Form::label('numero_altitud', 'Número altitud', ['for' => 'numero_altitud'] ) !!}
                                {!! Form::text('numero_altitud', $data->numero_altitud, ['class' => 'form-control', 'id' => 'numero_altitud', 'autocomplete' => 'off', 'placeholder' => 'Número altitud' ]  ) !!}
                                <span class="bt-flabels__error-desc"> </span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('hidde', ' ', ['for' => ' '] ) !!}
                                {!! Form::text('hd-input', null , ['class' => 'form-control', 'id' => 'df', 'autocomplete' => 'off', 'readonly' => 'true', 'placeholder' => ' ' ]  ) !!}
                                <span class="bt-flabels__error-desc"> </span>
                            </div>
                        </div>
                    </div>
                </div>   
                    
                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-history"></i> Restaurar</button>
                    @permission('update.catalogos')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar Cambios</button>@endpermission
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('my_scripts')
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- Form Mine -->
    {!! Html::script('assets/mine/js/parsleyjs/2.1.2/parsley.min.js') !!}
    {!! Html::script('assets/mine/js/floating-labels.js') !!}

    <!-- Select2 personalizado -->
    <script> 
        $(".js-data-municipio").select2();
    </script>
@endsection