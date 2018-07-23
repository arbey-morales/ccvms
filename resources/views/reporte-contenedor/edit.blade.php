@extends('app')
@section('title')
    Reportes Contenedores   
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
        <h2><i class="fa fa-bell-o"></i> Reportes Contenedores <i class="fa fa-angle-right text-danger"></i><small> Seguimiento</small></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <a class="" href="{{ route('reporte-contenedor.index') }}">
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
            {!! Form::model($data, ['route' => ['reporte-contenedor.update', $data], 'method' => 'PUT', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => '']) !!}
                <div class="row tile_count">
                    <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                        <span class="count_top"><i class="fa fa-hospital-o"></i> Unidad de salud</span>
                        <div class="count">{{$data->contenedor->clue->clues}}</div>
                        <span class="count_bottom"><i class="green"> {{$data->contenedor->clue->nombre}} </i>  <span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                        <span class="count_top"><i class="fa fa-bullhorn"></i> Reportó: </span>
                        <div class="count">{{$data->reporto}}</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-user"></i>Registró: </i> {{$data->usuario_id}} </span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                        <span class="count_top"><i class="fa fa-calendar"></i> Fecha: </span>
                        <div class="count">{{$data->fecha}}</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-clock-o"></i>Hora: </i> {{$data->hora}} </span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                        <span class="count_top"><i class="fa fa-cube"></i> Contenedor de biólogico</span>
                        <div class="count">{{$data->contenedor->serie}} </div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>Tipo: {{$data->contenedor->tipoContenedor->clave}} </i> {{$data->contenedor->tipoContenedor->nombre}} </span>
                    </div>
                </div>
                
                <br>

                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <h3 class="blue"><i class="fa fa-history"></i> Seguimiento del reporte</h3><hr>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <h3 class="blue"><i class="fa fa-camera-retro"></i> Evidencia fotográfica</h3><hr>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="bt-form__wrapper">
                            <!-- <div class="uk-grid uk-grid-collapse"> -->
                                <!-- <div class="uk-width-1-2"> -->
                                    <div class="bt-flabels__wrapper">
                                        <span class="select-label">* Estatus  del reporte</span>
                                        {!! Form::label('estatus_seguimiento', '* Estatus  del reporte', ['for' => 'estatus_seguimiento'] ) !!}
                                        {!! Form::select('estatus_seguimiento', [], 0, ['class' => 'form-control js-data-estatus select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'estatus_seguimiento',  'data-placeholder' => '* Estatus  del reporte', 'style' => 'width:100%'] ) !!}
                                        <span class="bt-flabels__error-desc">Requerido</span>
                                    </div>
                                <!-- </div> -->
                                <!-- <div class="uk-width-1-2"> -->
                                    <div class="bt-flabels__wrapper">
                                        {!! Form::label('observaciones', '* Observación o actividad realizada', ['for' => 'observaciones'] ) !!}
                                        {!! Form::text('observaciones', null, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'observaciones', 'autocomplete' => 'off', 'placeholder' => '* Observación o actividad realizada' ]  ) !!}
                                        <span class="bt-flabels__error-desc">Requerido</span>
                                    </div>

                                    <div class="bt-flabels__wrapper"> 
                                        <span class="select-label">* Estatus después de está actividad</span>
                                        {!! Form::label('estatus_contenedores_id', '* Estatus después de está actividad', ['for' => 'estatus_contenedores_id'] ) !!}
                                        {!! Form::select('estatus_contenedores_id', $estatus,  $data->estatus_contenedor_id, ['class' => 'form-control js-data-estatus-2 select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'estatus_contenedores_id',  'data-placeholder' => '* Estatus después de está actividad', 'style' => 'width:100%'] ) !!}
                                        <span class="bt-flabels__error-desc">Requerido</span>
                                    </div>
                                <!-- </div> -->
                            <!-- </div> -->
                        </div>
          
                        <div class="uk-text-center uk-margin-top pull-right">
                            <!-- <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-history"></i> Restaurar</button> -->
                            @role('red-frio|root')
                                @permission('update.catalogos')
                                    <button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Registrar el Seguimiento</button>
                                @endpermission
                            @endrole
                        </div>
        
                        <br><br><br>
                        <hr>

                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-left">Fecha</th>
                                <th class="text-left"></th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Observación o actividad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->seguimientosReportesContenedores as $key=>$item)
                                <tr>
                                    <td>{{$data->created_at}}</td>
                                    <td>
                                        <ul class="list-inline prod_color">
                                        @if($item->estatus_seguimiento==1)                                            
                                            <li>
                                                <div class="color bg-orange"></div>
                                            </li>
                                        @endif
                                        @if($item->estatus_seguimiento==2)                                            
                                            <li>
                                                <div class="color bg-blue"></div>
                                            </li>
                                        @endif
                                        @if($item->estatus_seguimiento==3)                                            
                                            <li>
                                                <div class="color bg-orange"></div>
                                            </li>
                                        @endif
                                        @if($item->estatus_seguimiento==4)                                            
                                            <li>
                                                <div class="color bg-red"></div>
                                            </li>
                                        @endif
                                        @if($item->estatus_seguimiento==5)                                            
                                            <li>
                                                <div class="color bg-green"></div>
                                            </li>
                                        @endif
                                        @if($item->estatus_seguimiento==6)                                            
                                            <li>
                                                <div class="color bg-red"></div>
                                            </li>
                                        @endif
                                        </ul>
                                    </td>
                                    <td>
                                        @if($item->estatus_seguimiento==1)                                            
                                            Reporte en espera de atención
                                        @endif


                                        @if($item->estatus_seguimiento==2)                                            
                                            Actualmente en seguimiento
                                        @endif
                                        @if($item->estatus_seguimiento==3)                                            
                                            En espera(Por falta de refacciones o trámites burocráticas)
                                        @endif


                                        @if($item->estatus_seguimiento==4)                                            
                                            Descartado
                                        @endif
                                        @if($item->estatus_seguimiento==5)                                            
                                            Finalizado con éxito
                                        @endif
                                        @if($item->estatus_seguimiento==6)                                            
                                            Finalizado sin éxito
                                        @endif                                  
                                    </td>
                                    <td>{{$item->observaciones}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        @if($data->foto==NULL && $data->foto2==NULL)
                            Sin evidencia fotográfica
                        @endif
                        @if($data->foto!=NULL)
                            <img id="img_destino" src="{{ url('storage/reporte-contenedor/'.$data->foto) }}" class="img-rounded" border="0px" width="auto" alt="">
                        @endif

                        @if($data->foto2!=NULL)
                            <img id="img_destino" src="{{ url('storage/reporte-contenedor/'.$data->foto2) }}" class="img-rounded" border="0px" width="auto" alt="">
                        @endif
                    </div>
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
        var estatus = [
                        // Seguimiento
                        // { 'id': 1, 'text': 'Reporte en espera de atención' },
                    
                        // Proceso
                        { 'id': 2, 'text': 'Actualmente en seguimiento' },
                        { 'id': 3, 'text': 'En espera(Por falta de refacciones o trámites burocráticas)' },

                        // Finalización
                        { 'id': 4, 'text': 'Descartado' },
                        { 'id': 5, 'text': 'Finalizado con éxito' },
                        { 'id': 6, 'text': 'Finalizado sin éxito' }
                    ];
        
        $(document).ready(function(){
            $(".js-data-estatus").select2({
                language: "es",
                data:estatus
            }); 
            $(".js-data-estatus-2").select2({
                language: "es"
            });
        });
        // var data = $.parseJSON(escaparCharEspeciales('{{$data}}'));
        // var clues = [{ 'id': data.clue.id, 'clues':data.clue.clues, 'text': data.clue.nombre }];
        // var modelos = [{ 'id': data.modelo.id, 'marca':data.modelo.marca.nombre, 'text': data.modelo.nombre }];
        // $(".js-data-status,.js-data-tipo-contenedor,.js-data-estatus").select2();
        // $(document).ready(function (e) {
        //     cambia_label(data.unidades_medidas_id);
        // });
        // $(".js-data-tipo-contenedor").change(function(e) {
        //     cambia_label($(this).val());
        // });

        // function cambia_label(val){
        //     if(val==4){
        //         $("#capacidad").attr('placeholder', '* Capacidad en Litros');
        //         $("#capacidad-label").text('* Capacidad en Litros');
        //     } else {
        //         $("#capacidad").attr('placeholder', '* Capacidad en Pies');
        //         $("#capacidad-label").text('* Capacidad en Pies');
        //     }
        // }
        // $(".js-data-clue").select2({
        //     ajax: {
        //         url: "/catalogo/clue",
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //         return {
        //             q: params.term, // search term
        //             page: params.page
        //         };
        //         },
        //         processResults: function (data, params) {            
        //         // parse the results into the format expected by Select2
        //         // since we are using custom formatting functions we do not need to
        //         // alter the remote JSON data, except to indicate that infinite
        //         // scrolling can be used
        //         params.page = params.page || 1;

        //         return {
        //             results: $.map(data.data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
        //                 return {
        //                     id:        item.id,
        //                     clues:     item.clues,
        //                     text:      item.nombre
        //                 }
        //             }),
        //             pagination: {
        //             more: (params.page * 30) < data.total_count
        //             }
        //         };
        //         },
        //         cache: true
        //     },
        //     escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        //     minimumInputLength: 5,
        //     language: "es",
        //     placeholder: {
        //         id: clues[0].id, 
        //         clues: clues[0].clues,
        //         text: clues[0].text
        //     },
        //     cache: true,
        //     templateResult: formatRepo, // omitted for brevity, see the source of this page
        //     templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        // });

        // $(".js-data-clue").select2("trigger", "select", { 
        //     data: clues[0] 
        // });

        // function formatRepo (clues) {
        //     if (!clues.id) { return clues.text; }
        //     var $clues = $(
        //         '<span class="">' + clues.clues + ' - '+ clues.text +'</span>'
        //     );
        //     return $clues;
        // };
        // function formatRepoSelection (clues) {
        //     if (!clues.id) { return clues.text; }
        //     var $clues = $(
        //         '<span class="results-select2"> ' + clues.clues+ ' - '+ clues.text +'</span>'
        //     );
        //     return $clues;
        // };

        
        // $(".js-data-modelo").select2({
        //     ajax: {
        //         url: "/catalogo/red-frio/modelo",
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //         return {
        //             q: params.term, // search term
        //             page: params.page
        //         };
        //         },
        //         processResults: function (data, params) {            
        //         // parse the results into the format expected by Select2
        //         // since we are using custom formatting functions we do not need to
        //         // alter the remote JSON data, except to indicate that infinite
        //         // scrolling can be used
        //         params.page = params.page || 1;

        //         return {
        //             results: $.map(data.data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
        //                 return {
        //                     id:        item.id,
        //                     marca:     item.marca_nombre,
        //                     text:      item.nombre
        //                 }
        //             }),
        //             pagination: {
        //             more: (params.page * 30) < data.total_count
        //             }
        //         };
        //         },
        //         cache: true
        //     },
        //     escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        //     minimumInputLength: 2,
        //     language: "es",
        //     placeholder: {
        //         id: modelos[0].id, 
        //         marca: modelos[0].marca,
        //         text: modelos[0].text
        //     },
        //     cache: true,
        //     templateResult: formatRepoModelo, // omitted for brevity, see the source of this page
        //     templateSelection: formatRepoModeloSelection // omitted for brevity, see the source of this page
        // });

        // $(".js-data-modelo").select2("trigger", "select", { 
        //     data: modelos[0] 
        // });

        // function formatRepoModelo (modelos) {
        //     if (!modelos.id) { return modelos.text; }
        //     var $modelos = $(
        //         '<span class="">' + modelos.text + ' - '+ modelos.marca +'</span>'
        //     );
        //     return $modelos;
        // };
        // function formatRepoModeloSelection (modelos) {
        //     if (!modelos.id) { return modelos.text; }
        //     var $modelos = $(
        //         '<span class="results-select2"> ' + modelos.text+ ' - '+ modelos.marca +'</span>'
        //     );
        //     return $modelos;
        // };
        

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