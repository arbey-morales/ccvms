@extends('app')
@section('title')
   Temperatura
@endsection
@section('my_styles')
    <!-- Select2 -->
    {!! Html::style('assets/vendors/select2-4.0.3/dist/css/select2.css') !!}
    <!-- Switchery -->
    {!! Html::style('assets/vendors/switchery/dist/switchery.min.css') !!}
    <!-- Form Mine -->
    {!! Html::style('assets/mine/css/uikit.almost-flat.min.css') !!}
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-user"></i> Temperatura <i class="fa fa-angle-right text-danger"></i><small> Agregar</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('temperatura.index') }}">
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
            <div class="row">
                <div class="col-md-6">
                    {!! Form::open([ 'route' => 'temperatura.store','' => 'form-input', 'method' => 'POST', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
                        <h2>Agregar temperatura actual</h2>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::hidden('tipo_envio', 1, array('id' => 'tipo_envio')) !!}
                                {!! Form::text('temperatura', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'id' => 'temperatura', 'autocomplete' => 'off', 'placeholder' => '4.0' ]  ) !!}
                            </div>
                            <div class="col-md-10">
                                @permission('create.catalogos')<button type="submit" class="btn btn-success btn-large js-submit"> <i class="fa fa-save"></i> Guardar temperatura</button>@endpermission 
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-6">
                    {!! Form::open([ 'route' => 'temperatura.store','' => 'form-file', 'method' => 'POST', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
                        <h2>Agregar temperaturas desde archivo, <small>Descargue el archivo del DataLogger y cárguelo aquí</small> </h2>
                        <div class="row">
                            <div class="col-md-10">
                                {!! Form::hidden('tipo_envio', 2, array('id' => 'tipo_envio')) !!}
                                {!! Form::file('archivo', null , ['class' => 'form-control img-load', 'id' => 'foto', 'accept' => '.txt'] ) !!}
                            </div>
                            <div class="col-md-2">
                                @permission('create.catalogos')<button type="submit" class="btn btn-primary btn-large js-submit"> <i class="fa fa-cloud-upload"></i> Subir archivo</button>@endpermission 
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <br>
            @include('errors.msgAll')            
        </div>
    </div>
@endsection
@section('my_scripts')
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- jQuery Tags Input -->
    {!! Html::script('assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- File Input -->
    {!! Html::script('assets/mine/js/bootstrap.file-input.js') !!}
    <!-- Form Mine -->
    {!! Html::script('assets/mine/js/parsleyjs/2.1.2/parsley.min.js') !!}
    {!! Html::script('assets/mine/js/floating-labels.js') !!}
    {!! Html::script('assets/mine/js/myCheckBox.js') !!}
    {!! Html::script('assets/mine/js/myMessage.js') !!}
    {!! Html::script('assets/mine/js/myfileImage.js') !!}
    {!! Html::script('assets/mine/js/myTags.js') !!}

    <script>
        $(document).ready(function(e){
            $("#password").prop('placeholder','Escribir un password');
            $("#password_confirmation").prop('placeholder','Repetir password');
        });
    </script>
    <!--<script>
        $(document).ready(function(e){
            $('.swRole').change(function(e){
                e.preventDefault();
                var row = $(this).parents('h4');
                var id = row.data('id');
                if($(this).is(':checked')) {
                    $("input[id*='role-"+id+"-permiso-']").prop('checked',true);
                    console.log(1);
                } else {
                    console.log(0);
                    $("input[id*='role-"+id+"-permiso-']").prop('checked',false);
                }
            });

            $("#password").prop('placeholder','Escribir un password');
            $("#password_confirmation").prop('placeholder','Repetir password');
        });
    </script>-->

    <!-- Select2 personalizado -->
    <script>
        //var localidad = { 'id':null, 'nombre':'Seleccionar una localidad', 'municipio':'','estado':'' };
        $(".js-data-jurisdiccion").select2();
        /*$(".js-data-localidad-ajax").select2({
            ajax: {
                url: "/catalogo/localidad/search",
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
                    results: $.map(data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
                        return {
                            id:        item.id,
                            nombre: item.nombre,
                            municipio: item.municipio.nombre,
                            estado: item.municipio.estado.abreviatura
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
                id: localidad.id, 
                nombre: localidad.nombre,
                municipio: localidad.municipio,
                estado: localidad.estado
            },
            cache: true,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });

        function formatRepo (localidad) {
            if (!localidad.id) { return localidad.nombre; }
            var $localidad = $(
                '<span class=""><strong><i class="fa fa-globe"></i> ' + localidad.nombre + '</strong>, '+ localidad.municipio +', '+ localidad.estado +'</span>'
            );
            return $localidad;
        };
        function formatRepoSelection (localidad) {
            if (!localidad.id) { return localidad.nombre; }
            var $localidad = $(
                '<span class="results-select2"><strong><i class="fa fa-globe"></i> ' + localidad.nombre + '</strong>, '+ localidad.municipio +', '+ localidad.estado +'</span>'
            );
            return $localidad;
        };*/
    </script>
@endsection