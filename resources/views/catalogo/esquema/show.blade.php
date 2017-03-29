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
                @if(count($vacunas_esquemas)>0)
                  <div class="x_panel">
                    <div class="x_title">
                        <h2 id="title-esquema"><i class="fa fa-calendar text-success"></i> {{ $esquema->descripcion }} </h2>
                        <ul class="nav navbar-right panel_toolbox">
                        <!--<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Settings 1</a>
                            </li>
                            <li><a href="#">Settings 2</a>
                            </li>
                            </ul>
                        </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>-->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" id="content-esquema">
                    <?php
                        $is_primer_md = false;
                        $is_last_md = false;
                        $total_md = 1;
                        $increment_md = 0;
                    ?>
                    @foreach($vacunas_esquemas as $key=>$ve)   
                        <?php 
                            $key_plus = $key; 
                            $key_plus = $key_plus + 1; 
                            $i_actual = $ve->intervalo; 
                            if((count($vacunas_esquemas) - 1) == $key) {
                                $i_siguiente = 'none';
                            } else {
                                $i_siguiente = $vacunas_esquemas[$key_plus]->intervalo;
                            }
                            $col_md = 1; $plu_col_md = 0;
                            foreach ($vacunas_esquemas as $k => $v) {
                                if($ve->intervalo==$v->intervalo)
                                    $plu_col_md++;
                            }
                            $col_md = 12 / $plu_col_md;

                            if($increment_md==0) {
                                $is_primer_md = true;
                            } else {
                                $is_primer_md = false;
                            }

                            if($col_md==6)
                                if($is_primer_md)
                                    $col_md = 3; 
                                else
                                    $col_md = 9;
                            
                            if($col_md==4)
                                if($is_primer_md)
                                    $col_md = 6; 
                                else
                                    $col_md = 3;
                        

                            $total_md = $plu_col_md;
                            $increment_md++;
                        ?>

                        @if($key==0)
                            <div class="col-md-12">
                        @endif
                            <div class="animated flipInY col-lg-{{$col_md}} col-md-{{$col_md}} col-sm-{{$col_md}} col-xs-12"><br>
                                <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #{{$ve->vacuna->color_rgb}}; background-color:#{{$ve->vacuna->color_rgb}} !important;">
                                    <div class="row">
                                        <div class="col-md-12"> <span style="font-size:large;font-weight:bold;"> {{$ve->vacuna->clave}} <small> @if($ve->tipo_aplicacion==1) Única @endif @if($ve->tipo_aplicacion==2) 1a Dosis @endif @if($ve->tipo_aplicacion==3) 2a Dosis @endif @if($ve->tipo_aplicacion==4) 3a Dosis @endif @if($ve->tipo_aplicacion==5) 4a Dosis @endif @if($ve->tipo_aplicacion==6) Refuerzo @endif  </small> </span> <span style="font-size:medium;" class="pull-right"> @if($ve->intervalo<=29) Nacimiento @else  @if(($ve->intervalo/30)<=23){{($ve->intervalo/30)}} Meses @else {{round((($ve->intervalo/30)/12))}} Años @endif @endif  </span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center" style="background-color:#fff; font-size:x-large; color:#000;">
                                         <i class="fa fa-calendar" style="color:#{{$ve->vacuna->color_rgb}};"></i> 
                                           <br>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        
                        @if((count($vacunas_esquemas)-1) == $key)
                            </div>
                        @else
                            @if($key!=0)
                                @if($i_actual!=$i_siguiente)
                                    <?php 
                                        $is_primer_md = false;
                                        $is_last_md = false;
                                        $total_md = 1;
                                        $increment_md = 0;
                                    ?>
                                    </div> <div class="col-md-12">
                                @endif
                            @endif
                        @endif
                    @endforeach
                    </div>
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