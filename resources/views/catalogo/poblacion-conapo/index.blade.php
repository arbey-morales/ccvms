@extends('app')
@section('title')
    Población CONAPO
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
        <h2>Población CONAPO  <i class="fa fa-cloud"></i> <span id="anio"></span> </h2>
        @role('root|admin')
            @permission('create.catalogos')
                @if($nuevo==1)
                    <a class="btn btn-default pull-right" href="{{ route('catalogo.poblacion-conapo.create') }}" role="button">Agregar población</a>
                @endif
            @endpermission 
        @endrole
        <div class="clearfix"></div>
        </div>
        <div class="x_content">
             @include('catalogo.poblacion-conapo.list')
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

    <!-- Datatables -->
    <script type="text/javascript">
        var anio = moment().format('YYYY');
        $("#anio").html('para el año '+anio);
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                language: {
                    url: '/assets/mine/js/dataTables/es-MX.json'
                }
            });
        });
    </script>
    <!-- /Datatables -->
@endsection