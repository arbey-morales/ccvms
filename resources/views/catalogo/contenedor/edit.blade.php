@extends('app')
@section('title')
   Contenedores de biológico
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
        <h2><i class="fa fa fa-cube"></i> Contenedores de biológico <i class="fa fa-angle-right text-danger"></i><small> Editar</small></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <a class="" href="{{ route('catalogo.contenedor-biologico.index') }}">
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
            {!! Form::model($data, ['route' => ['catalogo.contenedor-biologico.update', $data], 'method' => 'PUT', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => '']) !!}
            <div class="bt-form__wrapper">  
                    <div class="uk-grid uk-grid-collapse">                        
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                            <span class="select-label">* Unidad de salud</span>
                            {!! Form::label('clues_id', '* Unidad de salud', ['for' => 'clues_id'] ) !!}
                            {!! Form::select('clues_id', [],  0, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clues_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                            </div> 
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('serie', '* No. de serie', ['for' => 'serie'] ) !!}
                                {!! Form::text('serie', $data->serie, ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[1, 25]', 'id' => 'serie', 'autocomplete' => 'off', 'placeholder' => '* No. de serie' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Min 1, Máx 25 caracteres</span>
                            </div>
                        </div>
                    </div>                    
                    <div class="uk-grid uk-grid-collapse">                        
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Modelo</span>                                                         
                                {!! Form::label('modelos_id', '* Modelo', ['for' => 'modelos_id'] ) !!}
                                {!! Form::select('modelos_id', [], $data->modelos_id, ['class' => 'form-control js-data-modelo select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'modelos_id',  'data-placeholder' => '* * Modelo', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper"> 
                                <span class="select-label">* Estatus</span>
                                {!! Form::label('estatus_contenedores_id', '* Estatus', ['for' => 'estatus_contenedores_id'] ) !!}
                                {!! Form::select('estatus_contenedores_id', $estatus,  $data->estatus_contenedor_id, ['class' => 'form-control js-data-estatus select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'estatus_contenedores_id',  'data-placeholder' => '* Estatus', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">  
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">* Tipos contenedores</span>
                                {!! Form::label('tipos_contenedores_id', '* Tipos contenedores', ['for' => 'tipos_contenedores_id'] ) !!}
                                {!! Form::select('tipos_contenedores_id', $tipos_contenedores,  $data->tipos_contenedores_id, ['class' => 'form-control js-data-tipo-contenedor select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'tipos_contenedores_id',  'data-placeholder' => '* Tipos contenedores', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>                      
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('capacidad', '* Capacidad en Pies', ['for' => 'capacidad', 'id' => 'capacidad-label'] ) !!}
                                {!! Form::text('capacidad', $data->capacidad, ['class' => 'form-control', 'data-parsley-type' => 'number', 'id' => 'capacidad', 'autocomplete' => 'off', 'placeholder' => '* Capacidad en Pies' ]  ) !!}
                                <span class="bt-flabels__error-desc">Sólo números</span>                                
                            </div>
                        </div>
                    </div>
                </div>  
                    
                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-history"></i> Restaurar</button>
                    @role('red-frio|root')
                        @permission('update.catalogos')
                            <button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar Cambios</button>
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
        var data = $.parseJSON(escaparCharEspeciales('{{$data}}'));
        var clues = [{ 'id': data.clue.id, 'clues':data.clue.clues, 'text': data.clue.nombre }];
        var modelos = [{ 'id': data.modelo.id, 'marca':data.modelo.marca.nombre, 'text': data.modelo.nombre }];
        $(".js-data-status,.js-data-tipo-contenedor,.js-data-estatus").select2();
        $(document).ready(function (e) {
            cambia_label(data.unidades_medidas_id);
        });
        $(".js-data-tipo-contenedor").change(function(e) {
            cambia_label($(this).val());
        });

        function cambia_label(val){
            if(val==4){
                $("#capacidad").attr('placeholder', '* Capacidad en Litros');
                $("#capacidad-label").text('* Capacidad en Litros');
            } else {
                $("#capacidad").attr('placeholder', '* Capacidad en Pies');
                $("#capacidad-label").text('* Capacidad en Pies');
            }
        }
        $(".js-data-clue").select2({
            ajax: {
                url: "/catalogo/clue",
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
                id: clues[0].id, 
                clues: clues[0].clues,
                text: clues[0].text
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

        
        $(".js-data-modelo").select2({
            ajax: {
                url: "/catalogo/modelo",
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
                            marca:     item.marca_nombre,
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
            minimumInputLength: 2,
            language: "es",
            placeholder: {
                id: modelos[0].id, 
                marca: modelos[0].marca,
                text: modelos[0].text
            },
            cache: true,
            templateResult: formatRepoModelo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoModeloSelection // omitted for brevity, see the source of this page
        });

        $(".js-data-modelo").select2("trigger", "select", { 
            data: modelos[0] 
        });

        function formatRepoModelo (modelos) {
            if (!modelos.id) { return modelos.text; }
            var $modelos = $(
                '<span class="">' + modelos.text + ' - '+ modelos.marca +'</span>'
            );
            return $modelos;
        };
        function formatRepoModeloSelection (modelos) {
            if (!modelos.id) { return modelos.text; }
            var $modelos = $(
                '<span class="results-select2"> ' + modelos.text+ ' - '+ modelos.marca +'</span>'
            );
            return $modelos;
        };
        

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