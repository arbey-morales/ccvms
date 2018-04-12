@extends('app')
@section('title')
    Pedido
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
    <!-- Select2 -->
    {!! Html::style('assets/vendors/select2-4.0.3/dist/css/select2.css') !!}
    <!-- Switchery -->
    {!! Html::style('assets/vendors/switchery/dist/switchery.min.css') !!}
@endsection
@section('content') 
    @include('errors.msgAll')
    <div class="x_panel">
        <div class="x_title">
        <div class="row">
            <div class="col-md-4">
                <h2>Pedido  <i class="fa fa-cloud"></i> </h2> 
            </div>
            <div class="col-md-4">
                {!! Form::open([ 'route' => 'pedido.index', 'id' => 'form', 'method' => 'GET']) !!}
                    {!! Form::select('anio', [], '', ['class' => 'form-control js-data-anio select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'anio',  'data-placeholder' => 'Año', 'style' => 'width:100%'] ) !!}
                {!! Form::close() !!}
            </div>
            <div class="col-md-4">
                @role('root|admin|captura')
                    @permission('create.catalogos')
                        <a class="btn btn-default pull-right" href="{{ url('pedido/create') }}" role="button">Agregar pedido</a>
                    @endpermission 
                @endrole
            </div>
        </div>
        <div class="clearfix"></div>
        </div>
        <div class="x_content">
             @include('pedido.list')
        </div>
    </div>
    {!! Form::open(['route' => ['pedido.destroy', ':ITEM_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) !!}
    {!! Form::close() !!}
@endsection
@section('my_scripts')
    <!-- Datatables -->
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.js') !!}
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/dataTables.responsive.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/responsive.bootstrap.js') !!}
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- Pdfmake -->
    {!! Html::script('assets/vendors/pdfmake/build/pdfmake.min.js') !!}
    {!! Html::script('assets/vendors/pdfmake/build/vfs_fonts.js') !!}
    <!-- Mine -->
    {!! Html::script('assets/mine/js/images.js') !!}

    <script>
        var anios = [];
        var anio_selected = '{{$anio}}';
        var anio = moment().format('YYYY');
        var anio_actual = parseInt(anio);
        for (inicio = (anio_actual-10); inicio < (anio_actual + 2); inicio++) {
            anios.push({ 'id': inicio, 'text': 'Pedido del año: '+inicio  });
        }
        $(".js-data-anio").change(function(){
            if(anio_selected!=$(this).val()){
                $("#form").submit();
            }
        });
        $(document).ready(function(){
            $('#datatable-responsive').DataTable({
                language: {
                    url: '/assets/mine/js/dataTables/es-MX.json'
                }
            });
            $(".js-data-anio").select2({
                language: "es",
                data: anios
            });
            
            $(".js-data-anio").val(anio_selected).trigger("change");

            // Delete on Ajax 
            $('.btn-delete').click(function(e){
                e.preventDefault();
                var row = $(this).parents('tr');
                var id = row.data('id');
                var form = $("#form-delete");
                var url_delete = form.attr('action').replace(":ITEM_ID", id);
                var data = form.serialize();
              
                // Sending form
                $.post(url_delete, data, function(response, status){
                    if (response.code==1) {
                        new PNotify({
                            title: response.title,
                            text: response.text,
                            type: response.type,
                            styling: response.styling
                        });
                        if(response.type=='success') {
                            row.fadeOut();
                        }
                    }
                    if (response.code==0) {
                        new PNotify({
                            title: 'Oh No!',
                            text: 'Ocurrió un error al intentar borrar el registro, verifique!',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                        row.fadeIn();
                    }
                }).fail(function(){
                    new PNotify({
                        title: 'Lo sentimos!',
                        text: 'No se procesó la eliminación del registro',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                    row.fadeIn();
                });
            });
        });
    </script>
    <!-- /Datatables -->
@endsection