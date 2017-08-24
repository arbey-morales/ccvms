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
                <div class="col-md-2 col-md-offset-2">
                    <a class="btn btn-info" href="#" onClick="verPdf()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Vista previa"> <i class="fa fa-file-pdf-o"></i></a>
                    <a class="btn btn-success" href="#" onClick="descargarPdf()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Descargar"> <i class="fa fa-cloud-download"></i></a>
                    <a class="btn btn-warning" href="#" onClick="imprimirPdf()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Imprimir"> <i class="fa fa-print"></i></a>
                </div>
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

    <!-- Datatables -->
    <script>
    var registro_borrar = null;
    var data = $.parseJSON(escaparCharEspeciales('{{$data}}'));

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
        console.log(data);
    }

    function imprimirPdf()
    {
        console.log(data);
    }

    function descargarPdf()
    {
        var documentoDefinicion = {
            // a string or { width: number, height: number } OFICIO PIXELS: { width: 1285, height: 816 }
            pageSize: 'LEGAL',

            // by default we use portrait, you can change it to landscape if you wish
            pageOrientation: 'landscape',

            // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
            pageMargins: [ 40, 60 ],
            header: {
                columns: [
                    { text: 'CCVMS V1.0', alignment: 'left', bold: true, margin: [ 40, 30 ] },
                    { text: 'Censo Nominal', alignment: 'right', bold: true, margin: [ 40, 30 ] }
                ]
            },
            footer: {
                columns: [
                    { text: 'Leftt part', alignment: 'left' },
                    { text: 'Right part', alignment: 'right' }
                ]
            },
            content: [
                {
                    layout: 'lightHorizontalLines', // optional
                    table: {
                        // headers are automatically repeated if the table spans over multiple pages
                        // you can declare how many rows should be treated as headers
                        headerRows: 1,
                        widths: [ '*', 'auto', 100, '*' ],

                        body: [
                            [ 'First', 'Second', 'Third', 'The last one' ],
                            [ 'Value 1', 'Value 2', 'Value 3', 'Value 4' ],
                            [ { text: 'Bold value', bold: true }, 'Val 2', 'Val 3', 'Val 4' ]
                        ]
                    }
                }
            ]
        }
        pdfMake.createPdf(documentoDefinicion).open('Censo Nominal '+moment().format('DD-MM-YYYY')+'.pdf');
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
        
    </script>
    <!-- /Datatables -->
@endsection