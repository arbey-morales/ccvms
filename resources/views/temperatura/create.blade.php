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
        {!! Form::open([ 'route' => 'temperatura.store', 'method' => 'POST', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on']) !!}
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('clues_id', '* Unidad de salud', ['for' => 'clues_id'] ) !!}
                    {!! Form::select('clues_id', $clues,  0, ['class' => 'form-control js-data-clues select2', 'data-parsley-required' => 'true', 'id' => 'clues_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('contenedores_id', '* Contenedor de biológico', ['for' => 'contenedores_id'] ) !!}
                    {!! Form::select('contenedores_id', [],  null, ['class' => 'form-control js-data-contenedores select2', 'data-parsley-required' => 'true', 'id' => 'contenedores_id', 'data-placeholder' => '* Contenedor de biológico', 'style' => 'width:100%'] ) !!}
                </div>                
            </div>
            <div class="row">
                <div class="col-md-2"><br>
                    {!! Form::checkbox('desde_archivo', 'SI', false, ['class' => 'js-switch', 'id' => 'desde_archivo'] ) !!} 
                    {!! Form::label('desde_archivo', 'Cargar un archivo', ['for' => 'desde_archivo', 'style' => 'font-size:large; padding-right:10px;'] ) !!}
                </div>
                <div id="manual" class="col-md-5 show">
                    {!! Form::label('temperatura', '* Temperatura', ['for' => 'temperatura'] ) !!}
                    {!! Form::text('temperatura', null , ['class' => 'form-control', 'style' => 'width:200px', 'id' => 'temperatura', 'autocomplete' => 'off', 'placeholder' => '4.0' ]  ) !!}
                </div>
                <div id="archivo" class="col-md-5 hidden">
                    {!! Form::label('archivo', 'Archivo de temperaturas', ['for' => 'archivo'] ) !!}
                    {!! Form::file('archivo', null , ['class' => 'form-control', 'id' => 'archivo', 'accept' => '.txt'] ) !!}
                </div>
                <div class="col-md-4"><br>
                    @permission('create.catalogos')<button type="submit" class="btn btn-primary btn-lg js-submit pull-right"> <i class="fa fa-save"></i> Guardar! </button>@endpermission
                </div>
            </div> 
            {!! Form::close() !!}            
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
    {!! Html::script('assets/mine/js/myTags.js') !!}

    <script>
        $(document).ready(function(){
            setTimeout(function(){ $(".js-data-clues").change(); }, 1000);
        });
        var contenedores = [{ 'id': 0, 'text': 'Seleccionar contenedor' }];
        $(".js-data-clues,.js-data-contenedores").select2();
        $('#desde_archivo').change(function() {
            if ($(this).is(':checked')){
                $("#archivo").removeClass("hidden");
                $("#manual").removeClass("show");
                $("#manual").addClass("hidden");
                $("#archivo").addClass("show");

                $("#temperatura").val("");

            } else {
                $("#archivo").removeClass("show");
                $("#manual").removeClass("hidden");
                $("#manual").addClass("show");
                $("#archivo").addClass("hidden");

                $("#temperatura").focus();
                $("#archivo").val("") ;
            }
        });

        $(".js-data-clues").change(function(){
            var clue_id = $(this).val();
            $.get('../catalogo/contenedor-biologico/',{clues_id:clue_id}, function(response, status){ // Consulta        
                $('.js-data-contenedores').empty();
                while (contenedores.length) { contenedores.pop(); }                
                contenedores.push({ 'id': 0, 'text': 'Seleccionar contenedor' });  
                $.each(response.data, function( i, cont ) {
                    contenedores.push({ 'id': cont.id, 'text': cont.tipo_contenedor.nombre+': '+cont.marca.nombre+'/'+cont.modelo.nombre+'. Serie: '+cont.serie });
                });
                $(".js-data-contenedores").select2({
                    language: "es",
                    data: contenedores
                });  
                notificar('Información','Se cargaron '+(contenedores.length - 1)+' contenedores','success',1000); 
                           
            }).fail(function(){  // Calcula CURP
                notificar('Información','No se consultaron los contenedores de la unidad seleccionada','warning',2000);
            });
        });

        $(".js-data-contenedores").select2({
            language: "es",
            data: contenedores
        });


    </script>
@endsection