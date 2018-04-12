@extends('app')
@section('title')
   Pedido
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
        <h2><i class="fa fa-file-text"></i> Pedido <span id="anio"></span> </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ url('pedido') }}">
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
            {!! Form::open([ 'route' => 'pedido.store', 'id' => 'nuevo', 'method' => 'POST', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               <div class="bt-form__wrapper">
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('anio', '* Pedido anual', ['for' => 'anio'] ) !!}
                                {!! Form::select('anio', [], '', ['class' => 'form-control js-data-anio select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'anio',  'data-placeholder' => '* Pedido anual', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('clues_id', '* Unidad de salud', ['for' => 'clues_id'] ) !!}
                                {!! Form::select('clues_id', [],  0, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clues_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('descripcion', '* Descripción ', ['for' => 'descripcion'] ) !!}
                                {!! Form::text('descripcion', null, ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[10, 255]', 'id' => 'descripcion', 'autocomplete' => 'off', 'placeholder' => '* Descripción ' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha', '* Fecha', ['for' => 'fecha'] ) !!}
                                {!! Form::text('fecha', null , ['class' => 'fecha form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'fecha', 'autocomplete' => 'off', 'placeholder' => '* Fecha' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                </div>             
                
                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-eraser"></i> Limpiar</button>
                    @permission('create.pedidos')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar</button>@endpermission
                </div>
                
            {!! Form::close() !!}
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

    <!-- Select2 personalizado -->
    <script>
        var anios = [];
        var clues = [{ 'id': 0, 'clues':'', 'text': '* Unidad de salud' }];
        var anio = moment().format('YYYY');
        var anio_actual = parseInt(anio);
        for (inicio = (anio_actual-10); inicio < (anio_actual + 2); inicio++) {
            anios.push({ 'id': inicio, 'text': 'Pedido anual '+inicio  });
        }
        
        $(document).ready(function(){
            $(".js-data-anio").select2({
                language: "es",
                data: anios
            });
            $(".js-data-anio").val(anio_actual).trigger("change");

            var clues_id = $(".js-data-clue").val();
            
        });

        // SUBMIT DEL FORMULARIO

        /*$("#nuevod").submit(function(e) {
            e.preventDefault();
            console.log($(this).attr('action'))
            $.post($(this).attr('action')+'/store',$(this).serialize(), function(response, status){ // Envía formulario
                if(status=='success'){
                    notificar(response.titulo,response.texto,response.estatus,5000);
                    if(response.estatus=='success'){
                        $("#nombre,#paterno,#materno,#fecha_nacimiento,#curp,#sector,#manzana,#descripcion_domicilio,#calle,#numero,#codigo_postal,#fecha_nacimiento_tutor,#tutor").val('');
                        conseguirEsquema(moment().format('YYYY'),"01-01-"+moment().format('YYYY'));
                        $("#paterno").focus();
                    }
                } else {
                    notificar('Error','Error en el servidor','No se guardó el registro','error',3000);
                }
            }).fail(function(){ 
                notificar('Información','No se guardó el registro verifique los datos o recargue la página','error',4000);
            });
        });*/

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

        // SI LA CLUE CAMBIA; SE SELECCIONAN SU LOCALIDAD Y MUNICIPIO
        $(".js-data-clue,.js-data-anio").change(function(){
            var data = $("#piramide-form").serialize();
            $.get('/catalogo/vacunacion/piramide-poblacional/clue-detalle', data , function(response, status){ // Consulta  
                if(response.data.length>0){
                    var dato = response.data[0];
                    for (let index = 0; index < 11; index++) {
                        $("input#hombres_"+index).val(dato["hombres_"+index+""]);
                        $("input#mujeres_"+index).val(dato["mujeres_"+index+""]);
                    }
                } else {
                    $("input.numero").val(0);
                }
            }).fail(function(){  // Calcula CURP
                notificar('Información','No se consultaron los detalles de la unidad de salud','warning',2000);
            });
        });
    </script>
@endsection