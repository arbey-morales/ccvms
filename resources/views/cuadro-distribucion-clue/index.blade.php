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

             {!! Form::open([ 'route' => 'persona.index', 'class' => 'col-md-4 col-md-offset-1', 'method' => 'GET']) !!}
                    {!! Form::text('q', $q, ['class' => 'form-control', 'id' => 'q', 'autocomplete' => 'off', 'placeholder' => 'Buscar por Nombre y CURP ' ]) !!}
             {!! Form::close() !!}
             @if(count($personas)>0)
                <a target="_blank" class="btn btn-info col-md-1 col-md-offset-3" href="{{ url('persona-pdf') }}" class="button"> <i class="fa fa-file-pdf-o"></i> Ver .pdf </a>
             @endif
             @permission('create.personas')<a class="btn btn-default pull-right" href="{{ route('persona.create') }}" role="button">Agregar Persona</a>@endpermission
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
             @include('persona.list')
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

    <!-- Datatables -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                language: {
                    url: '/assets/mine/js/dataTables/es_MX.json'
                }
            });

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