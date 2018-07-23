@extends('app')
@section('title')
Reportes Contenedores
@endsection
@section('my_styles')
    <!-- Select2 -->
    {!! Html::style('assets/vendors/select2-4.0.3/dist/css/select2.css') !!}
    <!-- Switchery -->
    {!! Html::style('assets/vendors/switchery/dist/switchery.min.css') !!}
    <!-- Form Mine -->
    {!! Html::style('assets/mine/css/uikit.almost-flat.min.css') !!}
    {!! Html::style('assets/mine/css/form.css') !!}

    <style>
    .titulo{ font-size: 12pt; font-weight: bold; height: 30pt;}
#marcoVistaPrevia{
    border: 1px solid #008000;
    width: 400px;
    height: 400px;
}
#vistaPrevia{
    max-width: 400px;
    max-height: 400px;            
}

</style>
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-bell-o"></i> Reportes Contenedores <i class="fa fa-angle-right text-danger"></i><small> Agregar</small></h2>
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
        {!! Form::open([ 'route' => 'reporte-contenedor.store', 'id'=>'form', 'method' => 'POST', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
            
            <div class="bt-form__wrapper">
                <div class="uk-grid uk-grid-collapse">
                    <div class="uk-width-1-2">
                        <div class="bt-flabels__wrapper">
                            {!! Form::label('clues_id', '* Unidad de salud', ['for' => 'clues_id'] ) !!}
                            {!! Form::select('clues_id', [],  0, ['class' => 'form-control js-data-clues select2', 'data-parsley-required' => 'true', 'id' => 'clues_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <div class="bt-flabels__wrapper">
                            {!! Form::label('contenedores_id', '* Contenedor de biológico', ['for' => 'contenedores_id'] ) !!}
                            {!! Form::select('contenedores_id', [],  null, ['class' => 'form-control js-data-contenedores select2', 'data-parsley-required' => 'true', 'id' => 'contenedores_id', 'data-placeholder' => '* Contenedor de biológico', 'style' => 'width:100%'] ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                        </div>
                    </div>
                </div>
                <div class="uk-grid uk-grid-collapse">
                    <div class="uk-width-1-2">
                        <div class="bt-flabels__wrapper">
                            {!! Form::label('fallas_contenedores_id', '* Falla o incidente del contenedor de biológico', ['for' => 'fallas_contenedores_id'] ) !!}
                            {!! Form::select('fallas_contenedores_id', [],  null, ['class' => 'form-control js-data-fallas-contenedores select2', 'data-parsley-required' => 'true', 'id' => 'fallas_contenedores_id', 'data-placeholder' => '* Falla o incidente del contenedor de biológico', 'style' => 'width:100%'] ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <div class="bt-flabels__wrapper">
                            {!! Form::label('reporto', '* Nombre de quien reporta', ['for' => 'reporto'] ) !!}
                            {!! Form::text('reporto', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'reporto', 'autocomplete' => 'off', 'placeholder' => '* Nombre de quien reporta' ]  ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                        </div>
                    </div>
                </div>
                <div class="uk-grid uk-grid-collapse">
                    <div class="uk-width-1-2">
                        <div class="bt-flabels__wrapper">
                            {!! Form::label('fecha', '* Fecha', ['for' => 'fecha'] ) !!}
                            {!! Form::text('fecha', null , ['class' => 'fechaYMD fecha form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'fecha', 'autocomplete' => 'off', 'placeholder' => '* Fecha' ]  ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <div class="bt-flabels__wrapper">
                            {!! Form::label('hora', '* Hora', ['for' => 'hora'] ) !!}
                            {!! Form::text('hora', null , ['class' => 'hora form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'hora', 'autocomplete' => 'off', 'placeholder' => '* Hora' ]  ) !!}
                            <span class="bt-flabels__error-desc">Requerido</span>
                        </div>
                    </div>
                </div>
                <div class="bt-flabels__wrapper">
                    {!! Form::label('observacion', '* Observación o nota de la incidencia', ['for' => 'observacion'] ) !!}
                    {!! Form::text('observacion', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'observacion', 'autocomplete' => 'off', 'placeholder' => '* Observación o nota de la incidencia' ]  ) !!}
                    <span class="bt-flabels__error-desc">Requerido</span>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    {!! Form::file('foto', null , ['class' => 'form-control img-load', 'id' => 'foto', 'accept' => 'image/jpeg,image/jpg'] ) !!}
                    <p class="text-info">- Use formato: .jpeg o .jpg. <br>- La imagén seleccionada será redimensionada!</p>
                    <br><br>
                    @permission('create.catalogos')<button type="submit" class="btn btn-primary btn-lg js-submit pull-right"> <i class="fa fa-save"></i> Guardar </button>@endpermission
                </div>
                <div class="col-md-6">
                    <div id="yesimage" class="show">
                        <div style="display:inline-block; margin-right:50px;" class="text-center">
                            <img id="img_destino" src="{{ url('storage/propietario/profile/user-default.png') }}" class="img-rounded" border="0px" width="auto" alt="">
                        </div>
                    </div>
                    <div id="noimage" class="text-center hidden">
                        <h3 class="text-danger">Seleccione una imagen</h3>
                    </div>
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
    {!! Html::script('assets/mine/js/myPicker.js') !!}

    <script>
        var contenedores = [{ 'id': 0, 'text': 'Seleccionar contenedor' }];
        var fallasContenedores = [{ 'id': 0, 'text': 'Seleccionar una falla o incidente' }];
        var clues = [{ 'id': 0, 'clues':'', 'text': '* Unidad de salud' }];
        var fecha = moment().format("YYYY-MM-DD");
        var hora = moment().format("hh:mm:s");
        $(".fecha").val(fecha);
        $(".hora").val(hora);

        $(".js-data-clues").select2({
            ajax: {
                url: "../catalogo/clue-contenedor",
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
                       // console.log(item)
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

        $(".js-data-clues").change(function(){
            var clue_id = $(this).val();
            $.get('../catalogo/red-frio/contenedor-biologico/',{clues_id:clue_id}, function(response, status){ // Consulta        
                $('.js-data-contenedores').empty();
                while (contenedores.length) { contenedores.pop(); }                
                //contenedores.push({ 'id': 0, 'text': 'Seleccionar contenedor' });  
                $.each(response.data, function( i, cont ) {
                    contenedores.push({ 'id': cont.id, 'text': cont.tipo_contenedor.nombre+': '+cont.modelo.marca.nombre+'/'+cont.modelo.nombre+'. Serie: '+cont.serie });
                });
                $(".js-data-contenedores").select2({
                    language: "es",
                    data: contenedores
                });  
                notificar('Información','Se cargaron '+(contenedores.length)+' contenedores','success',1000); 
                           
            }).fail(function(){  // Calcula CURP
                notificar('Información','No se consultaron los contenedores de la unidad seleccionada','warning',2000);
            });
        });

        $(".js-data-contenedores").select2({
            language: "es",
            data: contenedores
        });

        // $("#form").submit(function(e){
        //     e.preventDefault();
        //     $(".js-submit").attr('disabled','disabled');
        //     $(".js-submit").empty().html('<i class="fa fa-circle-o-notch fa-spin"></i> Guardando...');

        //     $.ajax({
        //         url: $("#form").attr('action'),
        //         type : "POST",
        //         dataType : 'json',
        //         data : $("#form").serialize(),
        //         success : function(response, status) {
        //             $(".js-submit").removeAttr('disabled');
        //             $(".js-submit").empty().html('<i class="fa fa-save"></i> Guardar!');
        //         },
        //         error: function(xhr, resp, text) {
        //             $(".js-submit").removeAttr('disabled');
        //             $(".js-submit").empty().html('<i class="fa fa-save"></i> Guardar!');
        //             notificar('Información','No se guardó el registro verifique los datos o recargue la página','error',4000);
        //         }
        //     });
        // });

        $(document).ready(function() {
            $.get('../catalogo/red-frio/falla-contenedor/',{ }, function(response, status){ // Consulta        
                $('.js-data-fallas-contenedores').empty();
                while (fallasContenedores.length) { fallasContenedores.pop(); }                
                //fallasContenedores.push({ 'id': 0, 'text': 'Seleccionar una falla o incidente' });  
                $.each(response.data, function( i, cont ) {
                    fallasContenedores.push({ 'id': cont.id, 'text': cont.descripcion });
                });
                $(".js-data-fallas-contenedores").select2({
                    language: "es",
                    data: fallasContenedores
                });           
            }).fail(function(){  // Calcula CURP
                notificar('Información','No se consultó el catálogo de fallas de los contenedores','warning',2000);
            });
        });

        $('input[type=file]').change(function(e){
            mostrar(this,this.name);
        });

        var extensiones_permitidas = new Array("jpg", "jpeg");

        function mostrar(input,name) {
            
            $('#yes').removeClass('hidden show');
            $('#no').removeClass('hidden show');
            var permitida = false;
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) { 
                    var ext = input.files[0].name;
                    ext = ext.split(".");
                    var extension = ext[1];
                    for (var i = 0; i < extensiones_permitidas.length; i++) { 
                        if (extensiones_permitidas[i] == extension) { 
                            permitida = true; 
                            break; 
                        } 
                    }
                    console.log(e.target.result)
                    $('#img_destino').attr('src', e.target.result);
                    if (!permitida) {
                        $('#yes').addClass('hidden');
                        $('#no').addClass('show');
                    } else {
                        $('#no').addClass('hidden');
                        $('#yes').addClass('show');
                    }
                    
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
       
    </script>
@endsection