@extends('app')
@section('title')
    Censo nominal
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
@endsection
@section('content') 
    @include('errors.msgAll')
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-group"></i> Censo nominal <i class="fa fa-angle-right text-danger"></i><small> Lista</small></h2>

            {!! Form::open([ 'route' => 'persona.index', 'class' => 'col-md-4', 'method' => 'GET']) !!}
                    {!! Form::text('q', $q, ['class' => 'form-control', 'id' => 'q', 'autocomplete' => 'off', 'placeholder' => 'Buscar por Nombre y CURP ' ]) !!}
            {!! Form::close() !!}
            @if(count($data)>0)                
                @include('partials.layout.export')
            @endif
            @permission('create.personas')<a class="btn btn-default pull-right" href="{{ route('persona.create') }}" role="button">Agregar Persona</a>@endpermission
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
             @include('persona.list')
        </div>
        <br>
    </div>
    <!-- Modal delete -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header alert-danger">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title" id="myModalLabel"> <i class="fa fa-question" style="padding-right:15px;"></i>  Confirmación </h3>
            </div>
            <div class="modal-body">
                <h3>Seguro que quiere eliminar lo datos de <span id="modal-text" class="text text-danger"></span>?</h3>
                <h4>Además borrará todo registro de aplicaciones realizadas. Si esta de acuerdo presione "Sí, eliminar".</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger btn-lg btn-confirm-delete" data-dismiss="modal">Sí, eliminar</button>
            </div>

            </div>
        </div>
    </div>
    {!! Form::open(['route' => ['persona.destroy', ':ITEM_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) !!}
    {!! Form::close() !!}
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

    <!-- Datatables -->
    <script>
    var registro_borrar = null;
    var data = $.parseJSON(escaparCharEspeciales('{{$data}}'));
    var usuario = $.parseJSON(escaparCharEspeciales('{{$user}}'));
    var documentoDefinicion = construirTabla();

    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            language: {
                url: '/assets/mine/js/dataTables/es-MX.json'
            }
        });

        // Delete on Ajax 
        $('.btn-delete').click(function(e){
            e.preventDefault();
            var row = $(this).parents('tr');
            registro_borrar = row.data('id');
            $("#modal-text").html(row.data('nombre'));
        });      
    });

    // Confirm delete on Ajax
    $('.btn-confirm-delete').click(function(e){
        var row = $("tr#"+registro_borrar);
        var form = $("#form-delete");
        var url_delete = form.attr('action').replace(":ITEM_ID", registro_borrar);
        var data = $("#form-delete").serialize();
        $.post(url_delete, data, function(response, status){
            if (response.code==1) {
                notificar(response.title,response.text,response.type,3000);
                if(response.type=='success') {
                    row.fadeOut();
                }
            }
            if (response.code==0) {
                notificar('Error','Ocurrió un error al intentar borrar el registro, verifique!','error',3000);
            }
        }).fail(function(){
            notificar('Error','No se procesó la eliminación del registro','error',3000);
            row.fadeIn();
        });
    });

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
                    { image:ccvms, width: 85 },
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
    <!-- /Datatables -->
@endsection