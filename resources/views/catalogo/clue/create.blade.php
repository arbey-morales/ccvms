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
                                {!! Form::text('clues', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status',  'data-parsley-length' => '[5, 11]', 'data-parsley-required' => 'true', 'id' => 'clues', 'autocomplete' => 'off', 'placeholder' => '* Clave clues' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 5 - Máx: 11 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('nombre', '* Nombre', ['for' => 'nombre'] ) !!}
                                {!! Form::text('nombre', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 100]', 'id' => 'nombre', 'autocomplete' => 'off', 'placeholder' => '* Nombre' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="bt-flabels__wrapper">
                        {!! Form::label('domicilio', '* Domicilio', ['for' => 'domicilio'] ) !!}
                        {!! Form::text('domicilio', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'data-parsley-length' => '[5, 200]', 'id' => 'domicilio', 'autocomplete' => 'off', 'placeholder' => '* Domicilio' ]  ) !!}
                        <span class="bt-flabels__error-desc">Requerido / Mín 5 - Máx 200 caracteres</span>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                            {!! Form::label('codigo_postal', '* Código postal', ['for' => 'codigo_postal'] ) !!}
                                {!! Form::number('codigo_postal', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[5, 5]', 'id' => 'codigo_postal', 'autocomplete' => 'off', 'placeholder' => '* Código postal' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / 5 números</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Tipo de unidad</span>                              
                                {!! Form::label('tipos_unidades_id', '* Tipo de unidad', ['for' => 'tipos_unidades_id'] ) !!}
                                {!! Form::select('tipos_unidades_id', $tipos_unidades, 0, ['class' => 'form-control js-data-tipos-unidad select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'tipos_unidades_id',  'data-placeholder' => '* Tipo de unidad', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Jurisdicción</span>                              
                                {!! Form::label('jurisdicciones_id', '* Jurisdicción', ['for' => 'jurisdicciones_id'] ) !!}
                                {!! Form::select('jurisdicciones_id', [], '', ['class' => 'form-control js-data-jurisdiccion select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'jurisdicciones_id',  'data-placeholder' => '* Jurisdicción', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                               <span class="select-label">* Municipio</span>
                                {!! Form::label('municipios_id', '* Municipio', ['for' => 'municipios_id'] ) !!}
                                {!! Form::select('municipios_id', [],  '', ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipios_id',  'data-placeholder' => '* Municipio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Localidad</span>
                                {!! Form::label('localidades_id', '* Localidad', ['for' => 'localidades_id'] ) !!}
                                {!! Form::select('localidades_id', [], '', ['class' => 'form-control js-data-localidad select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'localidades_id',  'data-placeholder' => '* Localidad', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">   
                                <span class="select-label">* Institución</span>
                                {!! Form::label('instituciones_id', '* Institución', ['for' => 'instituciones_id'] ) !!}
                                {!! Form::select('instituciones_id', $instituciones,  1, ['class' => 'form-control js-data-institucion select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'instituciones_id',  'data-placeholder' => '* Institución', 'style' => 'width:100%'] ) !!}
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
                            <span class="select-label">Estatus</span>                                                         
                                {!! Form::label('estatus_id', 'Estatus', ['for' => 'tipologias_id'] ) !!}
                                {!! Form::select('estatus_id', $estatus, 1, ['class' => 'form-control js-data-estatus select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'estatus_id',  'data-placeholder' => '* Estatus', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('numero_longitud', 'Número longitud', ['for' => 'numero_longitud'] ) !!}
                                {!! Form::text('numero_longitud', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'numero_longitud', 'autocomplete' => 'off', 'placeholder' => 'Número longitud' ]  ) !!}
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('numero_latitud', 'Número latitud', ['for' => 'numero_latitud'] ) !!}
                                {!! Form::text('numero_latitud', null , ['class' => 'form-control', 'id' => 'numero_latitud', 'autocomplete' => 'off', 'placeholder' => 'Número latitud' ]  ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('consultorios', 'No. de consultorios', ['for' => 'consultorios'] ) !!}
                                {!! Form::text('consultorios', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'consultorios', 'autocomplete' => 'off', 'placeholder' => 'No. de consultorios' ]  ) !!}
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('camas', 'No. de camas', ['for' => 'camas'] ) !!}
                                {!! Form::number('camas', null , ['class' => 'form-control', 'id' => 'camas', 'autocomplete' => 'off', 'placeholder' => 'No. de camas' ]  ) !!}                                
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('fecha_construccion', 'Fecha de construcción', ['for' => 'fecha_construccion'] ) !!}
                                {!! Form::text('fecha_construccion', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'fecha_construccion', 'autocomplete' => 'off', 'placeholder' => 'Fecha de construcción' ]  ) !!}
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_inicio_operacion', 'Fecha de incio de operación', ['for' => 'fecha_inicio_operacion'] ) !!}
                                {!! Form::text('fecha_inicio_operacion', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'fecha_inicio_operacion', 'autocomplete' => 'off', 'placeholder' => 'Fecha de incio de operación' ]  ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('telefono1', 'Teléfono ', ['for' => 'telefono1'] ) !!}
                                {!! Form::text('telefono1', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'telefono1', 'autocomplete' => 'off', 'placeholder' => 'Teléfono ' ]  ) !!}
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('telefono2', 'Otro teléfono', ['for' => 'telefono2'] ) !!}
                                {!! Form::text('telefono2', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'telefono2', 'autocomplete' => 'off', 'placeholder' => 'Otro teléfono' ]  ) !!}
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
    <!-- Form Mine -->
    {!! Html::script('assets/mine/js/parsleyjs/2.1.2/parsley.min.js') !!}
    {!! Html::script('assets/mine/js/floating-labels.js') !!}

    <!-- Select2 personalizado -->
    <script> 
        var jurisdicciones = $.parseJSON(escaparCharEspeciales('{{json_encode($jurisdicciones)}}'));
        var municipios = $.parseJSON(escaparCharEspeciales('{{json_encode($municipios)}}'));
        var municipio_selected = $.parseJSON(escaparCharEspeciales('{{$municipio_selected}}'));
        // INICIA SELECT2 PARA ESTOS SELECTORES
        $(".js-data-tipologia,.js-data-estatus,.js-data-tipos-unidad,.js-data-institucion").select2();
        $(".js-data-jurisdiccion").change(function(){
            var jurisdiccion_id = $(this).val();
            $.get('../../catalogo/jurisdiccion/'+jurisdiccion_id, function(response, status){
                $("#municipios_id").html('').select2().trigger("change");
                $(".js-data-municipio").select2({ 'data': mapeoSubData(response.data.municipios) }).val(response.data.municipios[0].id).trigger("change");
            }).fail(function(){  
                notificar('Información','No se consultaron los detalles de la jurisdicción','warning',2000);
            });
        });
        $(".js-data-municipio").change(function(){
            setTimeout(function(){
                var municipio_id = $(".js-data-municipio").val();
                $.get('../../catalogo/municipio/'+municipio_id, function(response, status){
                    //$(".js-data-localidad").select2({ 'data': []}).trigger("change");
                    $("#localidades_id").html('').select2().trigger("change");
                    $(".js-data-localidad").select2({ 'data': mapeoSubData(response.data.localidades) }).val(response.data.localidades[0].id).trigger("change");
                }).fail(function(){  
                    notificar('Información','No se consultaron los detalles del municipio','warning',2000);
                });
            }, 50);            
        });

        function mapeoData (data) {
            var results = [];
            $.map(data, function( val, i ) {
                results.push({'id':i,'text':val});
            });
            return results;
        }

        function mapeoSubData (data) {
            var results = [];
            $.map(data, function( val, i ) {
                results.push({'id':val.id,'text':val.nombre});
            });
            return results;
        }

        $(".js-data-municipio").select2({ 'data': mapeoData(municipios) }).val(municipio_selected).trigger("change");
        $(".js-data-jurisdiccion").select2({ 'data': mapeoData(jurisdicciones) });

        function escaparCharEspeciales(str)
        {
            var map =
            {
                '&amp;': '&',
                '&lt;': '<',
                '&gt;': '>',
                '&quot;': '"',
                '&#039;': "'"
            };
            return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
        }
    </script>
@endsection