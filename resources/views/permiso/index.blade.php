@extends('app')
@section('title')
    Permisos
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
        <h2>Permisos  <i class="fa fa-angle-right text-danger"></i><small> Lista</small></h2>
        @role('root|red-frio')
            @permission('create.permisos')
                <!-- <a class="btn btn-default pull-right" href="{{ url('catalogo/red-frio/marca/create') }}" role="button">Nueva marca</a> -->
            @endpermission 
        @endrole
        <div class="clearfix"></div>
        </div>
        <div class="x_content">
             @include('permiso.list')
        </div>
    </div>
    <!-- Modal delete -->
    <!-- <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header alert-danger">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title" id="myModalLabel"> <i class="fa fa-question" style="padding-right:15px;"></i>  Confirmación </h3>
            </div>
            <div class="modal-body">
                <h3>Seguro que quiere eliminar la marca: <span id="modal-text" class="text text-danger"></span>?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                @role('red-frio|root')
                    @permission('delete.catalogos')
                        <button type="button" class="btn btn-danger btn-lg btn-confirm-delete" data-dismiss="modal">Sí, eliminar</button>
                    @endpermission 
                @endrole
            </div>

            </div>
        </div>
    </div>
    {!! Form::open(['route' => ['catalogo.red-frio.marca.destroy', ':ITEM_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) !!}
    {!! Form::close() !!} -->
@endsection
@section('my_scripts')
    <!-- Datatables -->
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.js') !!}
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/dataTables.responsive.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/responsive.bootstrap.js') !!}

    <!-- Datatables -->
    <script>
        var registro_borrar = null;
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                language: {
                    url: '/assets/mine/js/dataTables/es-MX.json'
                }
            });

            // Delete on Ajax 
            // $('.btn-delete').click(function(e){
            //     e.preventDefault();
            //     var row = $(this).parents('tr');
            //     registro_borrar = row.data('id');
            //     $("#modal-text").html(row.data('nombre'));
            // });
        });

        // Confirm delete on Ajax
        // $('.btn-confirm-delete').click(function(e){
        //     var row = $("tr#"+registro_borrar);
        //     var form = $("#form-delete");
        //     var url_delete = form.attr('action').replace(":ITEM_ID", registro_borrar);
        //     var data = $("#form-delete").serialize();
        //     $.post(url_delete, data, function(response, status){
        //         if (response.code==1) {
        //             notificar(response.title,response.text,response.type,3000);
        //             if(response.type=='success') {
        //                 row.fadeOut();
        //             }
        //         }
        //         if (response.code==0) {
        //             notificar('Error','Ocurrió un error al intentar borrar el registro, verifique!','error',3000);
        //         }
        //     }).fail(function(){
        //         notificar('Error','No se procesó la eliminación del registro','error',3000);
        //         row.fadeIn();
        //     });
        // });
    </script>
@endsection