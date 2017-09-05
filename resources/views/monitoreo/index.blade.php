@extends('app')
@section('title')
    Usuarios
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
    <style>
        .search{
            font-weight : bold;
            color : #000; 
            font-size: xx-large; 
            text-align: center ;
            height: 45px ;       
        }
    </style>
@endsection
@section('content') 
    @include('errors.msgAll')
    <div class="x_panel">
        <div class="x_title">
            <div class="col-md-2">
                {!! Form::open([ 'route' => 'monitoreo.index', 'method' => 'GET']) !!}
                    {!! Form::text('fecha', $fecha, ['class' => 'form-control search', 'id' => 'fecha', 'autocomplete' => 'off', 'placeholder' => '01-02-2017' ]) !!}
                {!! Form::close() !!}
            </div>
            @if(count($data)>0)
                <a class="btn btn-primary btn-lg pull-right" href="#" onClick="descargarPdf()" role="button"> <i class="fa fa-cloud-download"></i> Descargar</a>
            @endif
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
             @include('monitoreo.list')
        </div>
    </div>
@endsection
@section('my_scripts')
    <!-- Datatables -->
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.js') !!}
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/dataTables.responsive.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/responsive.bootstrap.js') !!}
    <!-- Pdfmake -->
    {!! Html::script('assets/vendors/pdfmake/build/pdfmake.min.js') !!}
    {!! Html::script('assets/vendors/pdfmake/build/vfs_fonts.js') !!}
    <!-- Mine -->
    {!! Html::script('assets/mine/js/images.js') !!}

    <script>
        var data = $.parseJSON(escaparCharEspeciales('{{json_encode($data)}}'));
        var documentoDefinicion = construirTabla();

        // MASCARA TIPO DD-MM-AAAA
        $("#fecha").mask("99-99-9999");
        $("#fecha").focus();

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