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
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
        <h2><i class="fa fa-group"></i> Censo nominal <i class="fa fa-angle-right text-danger"></i><small> Detalles </small></h2>
            
             @permission('update.personas')<a href="{{ route('persona.edit', $data->id) }}" class="btn btn-primary pull-right"><i class="fa fa-edit m-right-xs"></i> Hacer Cambios</a>@endpermission
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="col-xs-3 col-sm-2 col-md-1">
                <!-- required for floating -->
                <!-- Nav tabs -->
                <ul class="nav nav-tabs tabs-left">
                <li class="active"><a href="#home" data-toggle="tab">General</a>
                </li>
                <li><a href="#profile" data-toggle="tab">Esquema</a>
                </li>
                <!--<li><a href="#messages" data-toggle="tab">Estadísticas</a>
                </li>
                <li><a href="#settings" data-toggle="tab">Otros</a>
                </li>-->
                </ul>
            </div>

            <div class="col-xs-9 col-sm-10 col-md-11">
                <!-- Tab panes -->
                <div class="tab-content">
                <div class="tab-pane active" id="home">   
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <h1>
                                 </i> {{$data->nombre}} {{$data->apellido_paterno}} {{$data->apellido_materno}} <small> / {{$data->tipoParto->descripcion}}</small>
                                <small class="pull-right">Nacimiento: {{$data->fecha_nacimiento}}</small>
                            </h1>
                        </div>        
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="row">                        
                        <div class="col-md-4">
                            <div class="btn btn-default col-md-12" style="font-size:x-large;">
                                <h2>@if($data->genero=='M') <i class="fa fa-male" style="color:#4d81bf; font-size:xx-large;"></i> @endif @if($data->genero=='F') <i class="fa fa-female" style="color:#ed1586; font-size:xx-large;"></i>  @endif  <i class="fa fa-qrcode "></i> {{$data->curp}}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btn btn-success col-md-12" style="font-size:x-large;">
                                <h2><i class="fa fa-birthday-cake" style="font-size:xx-large;"></i> {{$data->edad}}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btn btn-info col-md-12" style="font-size:x-large;">
                                <h2><i class="fa fa-hospital-o" style="font-size:xx-large;"></i> {{$data->clue->clues}} </h2>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <strong>DETALLES DEL DOMICILIO</strong>
                            <address>
                                <br>Calle: {{ $data->calle }} 
                                <br>Número: {{ $data->numero }} 
                                <br>Colonia: @if($data->colonia){{ $data->colonia->nombre }}@endif 
                                <br>Localidad: {{ $data->localidad->nombre }} 
                                <br>Municipio: {{ $data->municipio->nombre }}
                            </address>
                        </div>
                        <div class="col-sm-4 invoice-col">
                            <strong>DATOS DEL TUTOR</strong>
                            <address>
                                <br>{{$data->tutor}}
                                <br>{{$data->fecha_nacimiento_tutor}}
                            </address>
                        </div>
                        <div class="col-sm-4 invoice-col">
                            <strong>UNIDAD DE SALUD</strong>
                            <address>
                                <br>{{$data->clue->clues}} - {{$data->clue->nombre}}
                            </address>
                        </div>
                    </div>
                    <br>
                    <!--@permission('update.personas')<a href="{{ route('persona.edit', $data->id) }}" class="btn btn-primary pull-right"><i class="fa fa-edit m-right-xs"></i> Hacer Cambios</a>@endpermission
                    --><br>
                    <div class="col-md-5" id="esquema_ideal" style="height:350px;"></div>
                    <div class="col-md-5" id="esquema_real" style="height:350px;"></div>
                    <!--<div class="col-md-4" id="esquema_biologico" style="height:350px;"></div>-->
                </div>
                <div class="tab-pane" id="profile">
                    <h2 id="title-esquema"> Esquema</h2>
                    <div class="x_content" id="content-esquema">
                        <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin esquema</h3></div>
                    </div>
                </div>
                <div class="tab-pane" id="messages"> 
                    Construyendo...
                </div>
                <div class="tab-pane" id="settings">Construyendo...</div>
                </div>
            </div>

            <div class="clearfix"></div>

            </div>
        </div>
    </div>

    <!-- Modal detalles dosis -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header alert">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title" id="myModalLabel" style="color:white !important;"> <i class="fa fa-exclamation-circle" style="padding-right:15px;"></i>  Información de <span id="dosis" ></span> </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text text-success" style="font-size:x-large; font-weight:bold;">Esquema ideal</h2>
                        <h3 id="intervalos"></h3>
                        <h3 id="fecha-ideal"></h3>
                        <h3 id="dias-anticipacion"></h3>
                    </div>
                    <div class="col-md-6">
                        <h2 class="text text-danger" style="font-size:x-large; font-weight:bold;">Esquema desfasado</h2>
                        <h3 id="intervalos-ni"></h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-lg btn-detalle" data-dismiss="modal">Entendido!</button>
                <!--<button type="button" class="btn btn-danger btn-lg btn-confirm-delete" data-dismiss="modal">Sí, eliminar</button>-->
            </div>

            </div>
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
    <!-- ECharts -->
    {!! Html::script('assets/vendors/echarts/dist/echarts.min.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/macarons.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/roma.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/shine.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/vintage.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/infographic.js') !!}
    <!-- Mine -->
    {!! Html::script('assets/mine/js/personaShow.js') !!}

    <!-- Datatables -->
    <script type="text/javascript">
        var esquema = $.parseJSON(escaparCharEspeciales('{{$esquema}}'));
        var aplicaciones_dosis = $.parseJSON(escaparCharEspeciales('{{json_encode($data->personasVacunasEsquemas)}}'));
        var aplicaciones_reales = $.parseJSON(escaparCharEspeciales('{{json_encode($data->aplicaciones)}}'));
        var esquema_detalle = $.parseJSON(escaparCharEspeciales('{{json_encode($data->esquema_detalle)}}'));
        var persona = $.parseJSON(escaparCharEspeciales('{{$data}}'));
        // GUARADARÁ EL ESQUEMA SELECCIONADO
        var ultimo_esquema = ''; 
        var ultima_fecha_nacimiento = '{{$data->fecha_nacimiento}}';
        var original_fecha_nacimiento = '{{$data->fecha_nacimiento}}';
        // CARGA EL ESQUEMA CON BASE A LA FECHA 01-01-AÑO ACTUAL
        setTimeout(function() {  
            var anio = original_fecha_nacimiento.split("-");       
            conseguirEsquema(anio[2],ultima_fecha_nacimiento);
        }, 500);
        
        var esquemaIdeal = echarts.init(document.getElementById('esquema_ideal'), 'roma');
        var optionIdeal = {
            title : {
                text: 'Esquema Ideal',
                subtext: 'Seguimiento de aplicaciones',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:['Aplicadas: '+aplicaciones_reales.length,'Faltantes: '+(esquema_detalle.length - aplicaciones_reales.length)]
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false, title:'Ver como texto', lang: ['Esquema ideal','Cerrar','Actualizar']},
                    magicType : {
                        show: true,                         
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true, title:'Restaurar'},
                    saveAsImage : {show: true, title:'Guardar imagen'}
                }
            },
            calculable : true,
            series : [
                {
                    name:'Esquema ideal '+esquema.id+' contiene '+esquema_detalle.length+' dosis',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        {value:aplicaciones_reales.length, name:'Aplicadas: '+aplicaciones_reales.length},
                        {value:(esquema_detalle.length - aplicaciones_reales.length), name:'Faltantes: '+(esquema_detalle.length - aplicaciones_reales.length)}
                    ]
                }
            ]
        };
        esquemaIdeal.setOption(optionIdeal);

        var esquemaReal = echarts.init(document.getElementById('esquema_real'), 'macarons');
        var optionReal = {
            title : {
                text: 'Esquema Real',
                subtext: 'Seguimiento de aplicaciones',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:['Aplicadas: '+aplicaciones_reales.length,'Faltantes: '+(esquema.vacunas_esquemas.length - aplicaciones_reales.length)]
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false, title:'Ver como texto', lang: ['Esquema real','Cerrar','Actualizar']},
                    magicType : {
                        show: true, 
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true, title:'Restaurar'},
                    saveAsImage : {show: true, title:'Guardar imagen'}
                }
            },
            calculable : true,
            series : [
                {
                    name:'Esquema completo '+esquema.id+' contiene '+esquema.vacunas_esquemas.length+' dosis',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        {value:aplicaciones_reales.length, name:'Aplicadas: '+aplicaciones_reales.length},
                        {value:(esquema.vacunas_esquemas.length - aplicaciones_reales.length), name:'Faltantes: '+(esquema.vacunas_esquemas.length - aplicaciones_reales.length)}
                    ]
                }
            ]
        };
        esquemaReal.setOption(optionReal);

        /*var aplicacionesBiologico = echarts.init(document.getElementById('esquema_biologico'), 'macarons');
        var Biologico = [];
        var dataBiologico = [];
        console.log(esquema_detalle);
        $.each(esquema_detalle, function( ins, apl ) {
            if(ins==0){
                var plus = 0;
                $.each(aplicaciones_reales, function( ind, apli ) {
                    if(apli.vacunas_id==apl.vacunas_id){
                        plus++;
                    }
                });
                Biologico.push(apl.clave);
                dataBiologico.push({'value':plus,'name':apl.clave});
            } else {
                if(esquema_detalle[(ins-1)].vacunas_id!=apl.vacunas_id){
                    var plus = 0;
                    $.each(aplicaciones_reales, function( ind, apli ) {
                        if(apli.vacunas_id==apl.vacunas_id){
                            plus++;
                        }
                    });
                    Biologico.push(apl.clave);
                    dataBiologico.push({'value':plus,'name':apl.clave});
                }
            }
        });
        
        var optionBiologico = {
            title : {
                text: 'Biológico',
                subtext: 'Dosis aplicadas',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data: dataBiologico
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true, 
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            series : [
                {
                    name:'Esquema completo '+esquema.id+' contiene '+esquema.vacunas_esquemas.length+' dosis',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:dataBiologico
                }
            ]
        };
        aplicacionesBiologico.setOption(optionBiologico);*/
    </script>
    <!-- /Datatables -->
@endsection