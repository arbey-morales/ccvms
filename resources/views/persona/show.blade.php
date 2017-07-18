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
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-group"></i> Censo nominal <i class="fa fa-angle-right text-danger"></i><small> Detalles </small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('persona.index') }}">
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
            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                <div class="btn btn-default col-md-12" style="font-size:x-large;">
                    <h2>@if($data->genero=='M') <i class="fa fa-male" style="color:#4d81bf; font-size:xx-large;"></i> @endif @if($data->genero=='F') <i class="fa fa-female" style="color:#ed1586; font-size:xx-large;"></i>  @endif  <i class="fa fa-qrcode "></i> {{$data->curp}}</h2>
                </div>
                <h4>{{$data->nombre}} {{$data->apellido_paterno}} {{$data->apellido_materno}}</h4>
                <h5> <i class="fa fa-calendar text-warning"></i> Nacimiento:  <strong>{{$data->fecha_nacimiento}}</strong> / <strong class="text-primary">{{$data->tipoParto->clave}}: {{$data->tipoParto->descripcion}}</strong> </h5>
                <h5> <i class="fa fa-map-marker text-danger"></i> {{ $data->calle }} {{ $data->numero }}, {{ $data->colonia }}, {{ $data->localidad->nombre }}, {{ $data->municipio->nombre }} </h5>
                <h5 class="text-danger"> <i class="fa fa-legal text-primary"></i> Tutor:  {{$data->tutor}} / <strong> {{$data->fecha_nacimiento_tutor}} </strong> </h5>
                <h5> <i class="fa fa-hospital-o text-success"></i> {{$data->clue->clues}} - {{$data->clue->nombre}}</h5>

                <br>
                @permission('update.personas')<a href="{{ route('persona.edit', $data->id) }}" class="btn btn-primary pull-right"><i class="fa fa-edit m-right-xs"></i> Hacer Cambios</a>@endpermission
                <br>

                <!-- start skills -->
                <h4>...</h4>
                <ul class="list-unstyled user_data">
                <li>
                    <p>....</p>
                    <div class="progress progress_sm">
                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50" style="width: 50%;" aria-valuenow="49"></div>
                    </div>
                </li>
                <li>
                    <p>....</p>
                    <div class="progress progress_sm">
                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="70" style="width: 70%;" aria-valuenow="69"></div>
                    </div>
                </li>
                <li>
                    <p>....</p>
                    <div class="progress progress_sm">
                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="30" style="width: 30%;" aria-valuenow="29"></div>
                    </div>
                </li>
                <li>
                    <p>...</p>
                    <div class="progress progress_sm">
                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50" style="width: 50%;" aria-valuenow="49"></div>
                    </div>
                </li>
                </ul>
                <!-- end of skills -->

            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                @if(count($vacunas_esquemas)>0)
                    <div class="x_panel">
                        <div class="x_title">
                            <h2 id="title-esquema"><i class="fa fa-calendar text-success"></i> @if(count($esquema)>0){{ $esquema->descripcion }} @endif</h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" id="content-esquema">
                        @foreach($vacunas_esquemas as $key=>$ve)   
                            <?php $key_plus = $key; $key_plus = $key_plus + 1; $col_md = 12; $plu_col_md = 0; ?>
                            @if(count($vacunas_esquemas) - 1 > $key)
                                @foreach ($vacunas_esquemas as $k => $v)
                                    @if($ve->fila==$v->fila)
                                        <?php $plu_col_md++; ?>
                                    @endif 
                                @endforeach 
                                <?php $col_md = round(12 / $plu_col_md); $fecha_ap = ''; ?> 

                                @foreach($data->personasVacunasEsquemas as $index=>$valor)
                                    @if($valor->vacunas_esquemas_id==$ve->id)
                                        <?php
                                            $fecha_ap = explode("-", trim(substr($valor->fecha_aplicacion, 0, -8))); 
                                            $fecha_ap = $fecha_ap[2].'-'.$fecha_ap[1].'-'.$fecha_ap[0];
                                            break;
                                        ?>
                                    @endif
                                @endforeach
                                <div class="animated flipInY col-md-{{$col_md}} col-xs-12"><br>
                                    <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #{{$ve->color_rgb}}; background-color:#{{$ve->color_rgb}} !important;">
                                        <div class="row">
                                            <div class="col-md-12"> <span style="font-size:large;font-weight:bold;"> {{$ve->clave}} <small> @if($ve->tipo_aplicacion==1) Única @endif @if($ve->tipo_aplicacion==2) 1a Dosis @endif @if($ve->tipo_aplicacion==3) 2a Dosis @endif @if($ve->tipo_aplicacion==4) 3a Dosis @endif @if($ve->tipo_aplicacion==5) 4a Dosis @endif @if($ve->tipo_aplicacion==6) Refuerzo @endif  </small> </span> <span style="font-size:medium;" class="pull-right"> @if($ve->intervalo_inicio<=29) Nacimiento @else  @if(($ve->intervalo_inicio/30)<=23){{($ve->intervalo_inicio/30)}} Meses @else {{round((($ve->intervalo_inicio/30)/12))}} Años @endif @endif  </span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center" style="background-color:#fff; font-size:x-large; color:#000;">
                                            <i class="fa fa-calendar" style="color:#{{$ve->color_rgb}};"></i>                                          
                                                @foreach($data->personasVacunasEsquemas as $index=>$valor)
                                                    @if($valor->vacunas_esquemas_id==$ve->id)
                                                        {{ $fecha_ap }}
                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($vacunas_esquemas[$key_plus]->fila != $ve->fila)
                                    <div class="clearfix"></div>
                                @endif
                            @endif
                        @endforeach
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