@extends('app')
@section('title')
   Reportes
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
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-child"></i> Reporte <i class="fa fa-angle-right text-danger"></i><small> Generar</small></h2>
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
            {!! Form::open([ 'url' => 'persona-filtro-pdf', 'id' => 'personas-form', 'method' => 'GET', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               
               <div class="bt-form__wrapper">
                    <div class="bt-flabels__wrapper">
                        {!! Form::label('clue_id', 'Unidad de salud', ['for' => 'clue_id'] ) !!}
                        {!! Form::select('clue_id', $clues,  0, ['class' => 'form-control js-data-clue select2', 'id' => 'clue_id', 'data-placeholder' => 'Unidad de salud', 'style' => 'width:100%'] ) !!}                        
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('edad', 'Edades', ['for' => 'edad'] ) !!}
                                {!! Form::select('edad', [0 => 'Todas las edades', 1 => '1 Año', 2 => '2 Años', 3 => '3 Años', 4 => '4 Años', 5 => '5 Años', 6 => '6 Años', 7 => '7 Años', 8 => '8 Años', 9 => '9 Años', 10 => '10 Años'],  0, ['class' => 'form-control js-data-edad select2', 'id' => 'edad',  'data-placeholder' => 'Edades', 'style' => 'width:100%'] ) !!}                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">   
                                {!! Form::label('genero', 'Género', ['for' => 'genero'] ) !!}
                                {!! Form::select('genero', ['X' => 'Todos los géneros', 'F' => 'F - Femenino', 'M' => 'M - Masculino'], 'X', ['class' => 'form-control js-data-genero select2', 'id' => 'genero',  'data-placeholder' => 'Género', 'style' => 'width:100%'] ) !!}                                
                            </div>
                        </div>
                    </div>
                </div>                
                
                <div class="uk-text-center uk-margin-top pull-right">
                    @permission('show.personas')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-search"></i> Buscar</button>@endpermission
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
    <!-- bootstrap-daterangepicker -->
    {!! Html::script('assets/app/js/moment/moment.min.js') !!}
    {!! Html::script('assets/app/js/datepicker/daterangepicker.js') !!}
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
    {!! Html::script('assets/mine/js/myfileImage.js') !!}
    {!! Html::script('assets/mine/js/myTags.js') !!}
    {!! Html::script('assets/mine/js/myPicker.js') !!}
    {!! Html::script('assets/mine/js/mx_CURP_RFC.js') !!}

    <!-- Select2 personalizado -->
    <script>
        $(".js-data-clue,.js-data-ageb,.js-data-genero,.js-data-edad,.js-data-estado,.js-data-municipio,.js-data-codigo,.js-data-institucion,.js-data-localidad").select2();


        $(".js-data-clue").change(function(){
            var clue_id = $(this).val();
            $.get('../catalogo/clue/'+clue_id, function(response, status){ // Consulta CURP
                $(".js-data-estado").val(response.data.idEntidad).trigger("change");
                $(".js-data-municipio").val(response.data.idMunicipio).trigger("change");
                $(".js-data-localidad").val(response.data.idLocalidad).trigger("change");
            }).fail(function(){  // Calcula CURP                    
                new PNotify({
                    title: 'Info!',
                    text: 'No se consultó detalles de la unidad de salud',
                    type: 'warning',
                    styling: 'bootstrap3'
                });
            });
        });      
    </script>
@endsection