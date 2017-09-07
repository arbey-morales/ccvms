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
            font-weight : normal;
            color : #000; 
            font-size: x-large; 
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
            @if(count($data2)>0)
                <div class="col-md-3 pull-right">
                    <a class="btn btn-info btn-lg" href="#" onClick="verPdf()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-file-pdf-o"></i> Vista Previa </a>
                    <a class="btn btn-warning btn-lg" href="#" onClick="imprimirPdf()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-print"></i> Imprimir</a>
                </div>
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
        var data2   = $.parseJSON(escaparCharEspeciales('{{json_encode($data2)}}'));
        var usuario = $.parseJSON(escaparCharEspeciales('{{json_encode($usuario)}}'));
        var documentoDefinicion = construirTabla();

        // MASCARA TIPO DD-MM-AAAA
        $("#fecha").mask("99-99-9999");

        function verPdf()
        {
            pdfMake.createPdf(documentoDefinicion).open('Monitoreo '+moment().format('DD-MM-YYYY')+'.pdf');
        }

        function imprimirPdf()
        {
            pdfMake.createPdf(documentoDefinicion).print('Monitoreo '+moment().format('DD-MM-YYYY')+'.pdf');
        }

        function descargarPdf()
        {
            pdfMake.createPdf(documentoDefinicion).download('Monitoreo '+moment().format('DD-MM-YYYY')+'.pdf');
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
            var columns = 0;
            $.each(data2, function( indice, row ) { 
                if(row.usuarios.length>columns){
                    columns = row.usuarios.length;
                }
            });

            var porcent = Math.round(100 / (columns + 3));
            $.each(data2, function( indice, row ) { 
                var data_row = [];    
                var cj = parseInt(row.captura_jurisdiccion);            
                data_row.push({'text':row.nombre, 'style':'celda_body'});
                data_row.push({'text':''+row.captura_jurisdiccion+'', 'style':'total_jur'});
                data_row.push({'text':'Usuarios: \n \n Capturas:', 'style':'celda_body'});
                var col = 0;
                $.each(row.usuarios, function( ind, row_usuarios ) {
                    col++;
                    data_row.push({'text':row_usuarios.nombre+' '+row_usuarios.paterno+' '+row_usuarios.materno+' \n '+row_usuarios.email+' \n '+row_usuarios.captura, 'style':'celda_body'});
                });

                for (var i = (col + 1); i < (columns + 1); i++) {
                    data_row.push({'text':' ', 'style':'celda_body'});
                }
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
                        { text: 'Monitoreo de capturas del '+moment($("#fecha").val(),'DD-MM-YYYY').format('LL'), width: 770, alignment: 'center', bold: true },
                        { image: censia, width: 50 }
                    ]
                },
                footer: {
                    margin: [ 40, 30, 40, 30 ],                
                    columns: [
                        { text: usuario.nombre+' '+usuario.paterno+' '+usuario.materno+' / '+usuario.email, alignment: 'left' },
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
                        width: ''+porcent+'%',
                        italic: true,
                        alignment: 'left'
                    },
                    total_jur: {
                        fontSize: 9,
                        width: ''+porcent+'%',
                        alignment: 'center',
                        fontWeight: 'bold'
                    }
                }
            }
        }            
    </script>
@endsection