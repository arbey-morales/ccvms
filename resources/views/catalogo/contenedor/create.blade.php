@extends('app')
@section('title')
   Contenedore de biológico
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
            <h2><i class="fa fa fa-cube"></i> Contenedores de biológico <i class="fa fa-angle-right text-danger"></i><small> Nuevo</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('catalogo.contenedor-biologico.index') }}">
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
            {!! Form::open([ 'route' => 'catalogo.contenedor-biologico.store', 'id' => 'personas-form', 'method' => 'POST', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               
                <div class="bt-form__wrapper">  
                    <div class="uk-grid uk-grid-collapse">                        
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                            <span class="select-label">* Unidad de salud</span>
                            {!! Form::label('clues_id', '* Unidad de salud', ['for' => 'clues_id'] ) !!}
                            {!! Form::select('clues_id', $clues,  0, ['class' => 'form-control js-data-clues select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clues_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                            </div> 
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('serie', '* No. de serie', ['for' => 'serie'] ) !!}
                                {!! Form::text('serie', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[1, 25]', 'id' => 'serie', 'autocomplete' => 'off', 'placeholder' => '* No. de serie' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Min 1, Máx 25 caracteres</span>
                            </div>
                        </div>
                    </div>                    
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('folio', '* Folio', ['for' => 'folio'] ) !!}
                                {!! Form::text('folio', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'id' => 'folio', 'autocomplete' => 'off', 'placeholder' => '* Folio' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">* Tipos contenedores</span>
                                {!! Form::label('tipos_contenedores_id', '* Tipos contenedores', ['for' => 'tipos_contenedores_id'] ) !!}
                                {!! Form::select('tipos_contenedores_id', $tipos_contenedores,  0, ['class' => 'form-control js-data-tipo-contenedor select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'tipos_contenedores_id',  'data-placeholder' => '* Tipos contenedores', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper"> 
                                <span class="select-label">* Marca</span>
                                {!! Form::label('marcas_id', '* Marca', ['for' => 'marcas_id'] ) !!}
                                {!! Form::select('marcas_id', $marcas,  0, ['class' => 'form-control js-data-marca select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'marcas_id',  'data-placeholder' => '* Marca', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Modelo</span>                                                         
                                {!! Form::label('modelos_id', '* Modelo', ['for' => 'modelos_id'] ) !!}
                                {!! Form::select('modelos_id', $modelos, 0, ['class' => 'form-control js-data-modelo select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'modelos_id',  'data-placeholder' => '* Modelo', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper"> 
                                <span class="select-label">* Estatus</span>
                                {!! Form::label('estatus_contenedores_id', '* Estatus', ['for' => 'estatus_contenedores_id'] ) !!}
                                {!! Form::select('estatus_contenedores_id', $estatus,  0, ['class' => 'form-control js-data-marca select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'estatus_contenedores_id',  'data-placeholder' => '* Estatus', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                            <span class="select-label">* Tipo de mantenimiento</span>
                                {!! Form::label('tipos_mantenimiento', '* Tipo de mantenimiento', ['for' => 'tipos_mantenimiento'] ) !!}
                                {!! Form::select('tipos_mantenimiento', ['0' => '* Tipo de mantenimiento','DIA' => 'Diario','SEM' => 'Semanal','QUI' => 'Quincenal','MES' => 'Mensual','IND' => 'Indefinido'],  0, ['class' => 'form-control js-data-tipo-mantenimiento select2', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 3]', 'id' => 'tipos_mantenimiento',  'data-placeholder' => '* Tipo de mantenimiento', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>                                
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper"> 
                                {!! Form::label('temperatura_minima', 'Temperatura mínima', ['for' => 'temperatura_minima'] ) !!}
                                {!! Form::text('temperatura_minima', null , ['class' => 'form-control', 'data-parsley-type' => 'number', 'id' => 'temperatura_minima', 'autocomplete' => 'off', 'placeholder' => 'Temperatura mínima' ]  ) !!}
                                <span class="bt-flabels__error-desc">Sólo números</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('temperatura_maxima', 'Temperatura máxima', ['for' => 'temperatura_maxima'] ) !!}
                                {!! Form::text('temperatura_maxima', null , ['class' => 'form-control', 'data-parsley-type' => 'number', 'id' => 'temperatura_maxima', 'autocomplete' => 'off', 'placeholder' => 'Temperatura máxima' ]  ) !!}
                                <span class="bt-flabels__error-desc">Sólo números</span>                                
                            </div>
                        </div>
                    </div>
                </div>                
            
                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-eraser"></i> Limpiar</button>
                    @role('red-frio|root')
                        @permission('create.catalogos')
                            <button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar</button>
                        @endpermission
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
    <!-- Form Mine -->
    {!! Html::script('assets/mine/js/parsleyjs/2.1.2/parsley.min.js') !!}
    {!! Html::script('assets/mine/js/floating-labels.js') !!}

    <!-- Select2 personalizado -->
    <script> 
        $(".js-data-clues,.js-data-tipo-mantenimiento,.js-data-marca,.js-data-tipo-contenedor,.js-data-modelo,.js-data-estatus").select2();
    </script>
@endsection