@extends('app')
@section('title')
    Esquemas
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
@endsection
@section('content') 
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-calendar"></i> Esquemas <i class="fa fa-angle-right text-danger"></i><small> Detalles </small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('catalogo.esquema.index') }}">
                        <i class="fa fa-long-arrow-left"></i>
                    </a>
                </li>
                <li>
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>


        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
                @if(count($esquema)>0)
                    <div class="x_panel">
                        <div class="x_title">
                            <h2 id="title-esquema"><a class="btn btn-danger btn-lg"><i class="fa fa-calendar"></i> {{ $esquema->descripcion }}</a> </h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" id="content-esquema">
                            @if(count($data)>0)
                                @foreach($data as $key=>$ve)
                                    <?php $key_plus = $key; $key_plus = $key_plus + 1; $col_md = 12; $plu_col_md = 0; ?>
                                    @if(count($data) - 1 > $key)
                                        @foreach ($data as $k => $v)
                                            @if($ve->fila==$v->fila)
                                                <?php $plu_col_md++; ?>
                                            @endif 
                                        @endforeach 
                                        <?php $col_md = round(12 / $plu_col_md); ?>                                 
                                        <div class="animated flipInY col-md-2 col-xs-12"><br>
                                            <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #{{$ve->color_rgb}}; background-color:#{{$ve->color_rgb}} !important;">
                                                <div class="row">
                                                    <div class="col-md-12"> <span style="font-size:large;font-weight:bold;"> {{$ve->clave}} <small> @if($ve->tipo_aplicacion==1) Única @endif @if($ve->tipo_aplicacion==2) 1a Dosis @endif @if($ve->tipo_aplicacion==3) 2a Dosis @endif @if($ve->tipo_aplicacion==4) 3a Dosis @endif @if($ve->tipo_aplicacion==5) 4a Dosis @endif @if($ve->tipo_aplicacion==6) Refuerzo @endif  </small> </span> <span style="font-size:large;" class="pull-right"> @if($ve->intervalo_inicio<=29) Nacimiento @else  @if(($ve->intervalo_inicio/30)<=23){{($ve->intervalo_inicio/30)}} Meses @else {{round((($ve->intervalo_inicio/30)/12))}} Años @endif @endif  </span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="bt-flabels__wrapper  text-center">
                                                        <div class="clearfix"></div>
                                                        <div style="padding:2px; background-color:white;"><i class="fa fa-calendar" style="color:#{{$ve->color_rgb}}; font-size:large;"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($data[$key_plus]->fila != $ve->fila)
                                            <div class="clearfix"></div>
                                        @endif
                                    @endif
                                @endforeach
                            @else
                                <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin aplicaciones para el esquema {{$esquema->descripcion}} </h3></div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin esquema</h3></div>
                @endif
            </div>
        </div>





        <div class="x_content">
            
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
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                language: {
                    url: '/assets/mine/js/dataTables/es_MX.json'
                }
            });
        });
    </script>
    <!-- /Datatables -->
@endsection