@extends('app')
@section('title')
   Población CONAPO
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
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-cloud"></i> Población CONAPO <span id="anio"></span> </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('catalogo.poblacion-conapo.index') }}">
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
            {!! Form::open([ 'url' => 'catalogo/poblacion-conapo/importar', 'id' => 'poblacion-conapo-form',  'files' => 'true', 'method' => 'POST', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::select('anio', [], '', ['class' => 'form-control js-data-anio select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'anio',  'data-placeholder' => '* Tipología', 'style' => 'width:100%'] ) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('archivo', 'Archivo de temperaturas', ['for' => 'archivo'] ) !!}
                        {!! Form::file('archivo', null , ['class' => 'form-control', 'id' => 'archivo', 'accept' => '.txt'] ) !!}
                    </div>
                    <div class="col-md-2"><br>
                        @permission('create.catalogos')<button type="submit" class="btn btn-primary btn-lg js-submit pull-right"> <i class="fa fa-save"></i> Guardar! </button>@endpermission
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
    {!! Html::script('assets/mine/js/myTags.js') !!}
    {!! Html::script('assets/mine/js/myPicker.js') !!}
    {!! Html::script('assets/mine/js/mx_CURP_RFC.js') !!}

    <!-- Select2 personalizado -->
    <script>
        var anios = [];
        var anio = moment().format('YYYY');
        var anio_actual = parseInt(anio);
        for (inicio = (anio_actual-10); inicio < (anio_actual + 2); inicio++) {
            anios.push({ 'id': inicio, 'text': 'Población objetivo CONAPO '+inicio  });
        }
        
        $(document).ready(function(){
            $(".js-data-anio").select2({
                language: "es",
                data: anios
            });
            $(".js-data-anio").val(anio_actual).trigger("change");
        });
    </script>
@endsection