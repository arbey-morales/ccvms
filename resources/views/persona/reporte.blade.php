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
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('errors.msgAll') <!-- Mensages -->
            {!! Form::open([ 'url' => 'persona/reporte', 'id' => 'personas-form', 'method' => 'GET', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               
               <div class="bt-form__wrapper">
                    <div class="bt-flabels__wrapper">
                        {!! Form::label('clue_id', 'Unidad de salud', ['for' => 'clue_id'] ) !!}
                        {!! Form::select('clue_id', $clues,  $clue_id, ['class' => 'form-control js-data-clue select2', 'id' => 'clue_id', 'data-placeholder' => 'Unidad de salud', 'style' => 'width:100%'] ) !!}                        
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('edad', 'Edades', ['for' => 'edad'] ) !!}
                                {!! Form::select('edad', [0 => 'Todas las edades', 1 => '1 Año', 2 => '2 Años', 3 => '3 Años', 4 => '4 Años', 5 => '5 Años', 6 => '6 Años', 7 => '7 Años', 8 => '8 Años', 9 => '9 Años', 10 => '10 Años'],  $edad, ['class' => 'form-control js-data-edad select2', 'id' => 'edad',  'data-placeholder' => 'Edades', 'style' => 'width:100%'] ) !!}                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">   
                                {!! Form::label('genero', 'Género', ['for' => 'genero'] ) !!}
                                {!! Form::select('genero', ['X' => 'Todos los géneros', 'F' => 'F - Femenino', 'M' => 'M - Masculino'], $genero, ['class' => 'form-control js-data-genero select2', 'id' => 'genero',  'data-placeholder' => 'Género', 'style' => 'width:100%'] ) !!}                                
                            </div>
                        </div>
                    </div>
                </div>  
                 
                <div class="uk-text-center uk-margin-top pull-right">
                    @permission('show.personas')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-search"></i> Buscar</button>@endpermission
                </div>

            {!! Form::close() !!}
        </div>

        <br>
        @if(count($data)>0)              
            @include('partials.layout.export')        
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">CURP</th>
                        <th class="text-left">Nacimiento</th>
                        <th class="text-left">Dirección</th>
                        <th class="text-left">Unidad de salud</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key=>$item)
                        <tr id="{{ $item->id }}" data-id="{{ $item->id }}" data-nombre="{{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}" data-toggle="tooltip" data-placement="top">
                            <td class="text-center"><strong> {{ ++$key }} </strong></td>
                            <td class="text-left"> <strong> {{ $item->genero }} </strong> / {{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}</td>
                            <td class="text-left"><strong>{{ $item->curp }}</strong></td>
                            <td class="text-left">{{$item->fecha_nacimiento}}</td>
                            <td class="text-left">{{ $item->calle }} {{ $item->numero }}, @if($item->colonia){{ $item->colonia->nombre }},@endif {{ $item->localidad->nombre }}, {{ $item->municipio->nombre }} </td>
                            <td class="text-left"><strong>{{$item->clue->clues}}</strong>, {{$item->clue->nombre}}</td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="6">
                               <?php $vacunas = array();?>
                                @if(count($item->aplicaciones)>0)
                                    @foreach($item->aplicaciones as $key=>$value)  
                                        @if(in_array($value->vacunas_id, $vacunas))
                                            <!--Si ya tenemos esta vacuna-->
                                        @else
                                        <?php array_push($vacunas, $value->vacunas_id); ?> 
                                        @endif                                       
                                        {{$value->clave}} {{$value->nombre}}
                                    @endforeach
                                @else
                                    Sin aplicaciones
                                @endif 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="col-md-12 text-center"> Sin resultados </div> 
        @endif
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
    {!! Html::script('assets/mine/js/myMessage.js') !!}
    <!-- Pdfmake -->
    {!! Html::script('assets/vendors/pdfmake/build/pdfmake.min.js') !!}
    {!! Html::script('assets/vendors/pdfmake/build/vfs_fonts.js') !!}
    <!-- Mine -->
    {!! Html::script('assets/mine/js/images.js') !!}

    <!-- Select2 personalizado -->
    <script>
        $(".js-data-clue,.js-data-genero,.js-data-edad").select2();   
        var data = $.parseJSON(escaparCharEspeciales('{{$data}}'));
        var usuario = $.parseJSON(escaparCharEspeciales('{{$user}}'));
        var documentoDefinicion = construirTabla(); 
        function verPdf()
        {
            pdfMake.createPdf(documentoDefinicion).open('Censo Nominal '+moment().format('DD-MM-YYYY')+'.pdf');
        }

        function imprimirPdf()
        {
            pdfMake.createPdf(documentoDefinicion).print('Censo Nominal '+moment().format('DD-MM-YYYY')+'.pdf');
        }

        function descargarPdf()
        {
            pdfMake.createPdf(documentoDefinicion).download('Censo Nominal '+moment().format('DD-MM-YYYY')+'.pdf');
        }

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
        
        function construirTabla() {
            var body = [];
            body.push([
                        {'text':'Nombre', 'style':'celda_header'},
                        {'text':'Nacimiento', 'style':'celda_header'},
                        {'text':'Género', 'style':'celda_header'},
                        {'text':'CURP', 'style':'celda_header'},
                        {'text':'Tutor', 'style':'celda_header'},
                        {'text':'Parto', 'style':'celda_header'},
                        {'text':'Dirección', 'style':'celda_header'},
                        {'text':'CLUE', 'style':'celda_header'},
                        {'text':'AGEB', 'style':'celda_header'},
                        {'text':'Sector', 'style':'celda_header'},
                        {'text':'Mz', 'style':'celda_header'},
                        {'text':'Código', 'style':'celda_header'},
                        {'text':'Afiliación', 'style':'celda_header'}
                    ]);
            $.each(data, function( indice, row ) { 
                var data_row = [];
                data_row.push({'text':row.nombre+' '+row.apellido_paterno+' '+row.apellido_materno, 'style':'celda_body'});
                data_row.push({'text':row.fecha_nacimiento, 'style':'celda_body'});
                data_row.push({'text':row.genero, 'style':'celda_body'});
                data_row.push({'text':row.curp, 'style':'celda_body'});
                data_row.push({'text':row.tutor, 'style':'celda_body'});
                data_row.push({'text':row.tipo_parto.descripcion, 'style':'celda_body'});
                var colonia = '';
                if(row.colonias_id!=null){
                    colonia = row.colonia.nombre+', ';
                }
                data_row.push({'text':row.calle+' '+row.numero+', '+colonia+' '+row.localidad.nombre+', '+row.municipio.nombre, 'style':'celda_body'});
                data_row.push({'text':row.clue.clues+' '+row.clue.nombre, 'style':'celda_body'});
                var ageb = '';
                if(row.agebs_id!=null){
                    ageb = row.ageb.id;
                    ageb = ageb.substr(-4);
                }
                data_row.push({'text':ageb, 'style':'celda_body'});
                data_row.push({'text':row.sector, 'style':'celda_body'});
                data_row.push({'text':row.manzana, 'style':'celda_body'});
                var codigo = '';
                if(row.codigos_id!=null){
                    codigo = row.codigo.nombre;
                }
                data_row.push({'text':codigo, 'style':'celda_body'});
                var afiliacion = '';
                if(row.codigos_id!=null){
                    afiliacion = row.afiliacion.nombre_corto;
                }
                data_row.push({'text':afiliacion, 'style':'celda_body'});
                body.push(data_row);
                var apli_row = [];
                var aplicaciones = 'S/A';
                if(row.aplicaciones.length>0){
                    $.each(row.aplicaciones, function( index, apli ) { 
                        aplicaciones= aplicaciones+' '+apli.clave;
                    });
                    aplicaciones = 'Construyendo...';
                }
                
                apli_row.push({'text':aplicaciones, 'colSpan':13, 'style':'celda_body'});
                body.push(apli_row);
                
            });
            return documentoDefinicion = {
                // a string or { width: number, height: number } OFICIO PIXELS: { width: 1285, height: 816 }
                pageSize: 'LEGAL',
                // by default we use portrait, you can change it to landscape if you wish
                pageOrientation: 'landscape',
                pageMargins: [ 40, 70, 40, 70 ],
                header: {
                    margin: [ 40, 30, 40, 30 ],
                    columns: [
                        { image: logo_sm, width: 85 },
                        { text: 'Censo Nominal \n Jurisdicción '+usuario.jurisdiccion.clave+' '+usuario.jurisdiccion.nombre, width: 790, alignment: 'center', bold: true },
                        { image: censia, width: 50 }
                    ]
                },
                footer: {
                    margin: [ 40, 30, 40, 30 ],                
                    columns: [
                        { text: 'Generó: '+usuario.nombre+' '+usuario.paterno+' '+usuario.materno+' / '+usuario.email, alignment: 'left' },
                        { text: moment().format('LL'), alignment: 'right' }
                    ]
                },
                content: [
                    {
                        layout: 'lightHorizontalLines', // optional
                        table: {
                            body
                        }
                    }
                ],
                styles: {
                    celda_header: {
                        fontSize: 10,
                        bold: true,
                        aligment: 'center'
                    },
                    celda_body: {
                        fontSize: 7,
                        italic: true,
                        alignment: 'left'
                    }
                }
            }
        }

    </script>
@endsection