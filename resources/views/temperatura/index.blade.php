@extends('app')
@section('title')
    Temperatura
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
    <!-- Switchery -->
    {!! Html::style('assets/vendors/switchery/dist/switchery.min.css') !!}
    <style>
        .search{
            font-weight : normal;
            color : #000; 
            font-size: x-large; 
            text-align: center ;
            height: 45px ;       
        }
    </style>
@endsection
@section('content') 
    @include('errors.msgAll')
    <div class="x_panel">
        <div class="x_title">
            {!! Form::open([ 'route' => 'temperatura.index', 'id' => 'form', 'method' => 'GET']) !!}
                <div class="col-md-2">                    
                    {!! Form::text('fecha_inicial', $fecha_inicial, ['class' => 'form-control search', 'id' => 'fecha_inicial', 'autocomplete' => 'off', 'placeholder' => '01-02-2017' ]) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::text('fecha_final', $fecha_final, ['class' => 'form-control search', 'id' => 'fecha_final', 'autocomplete' => 'off', 'placeholder' => '01-02-2017' ]) !!}
                </div>
               <!-- <div class="col-md-2">
                    {!! Form::checkbox('por_dia', 'SI', $por_dia, ['class' => 'js-switch', 'id' => 'por_dia'] ) !!} 
                    {!! Form::label('por-dia', 'Por días', ['for' => 'por_dia', 'style' => 'font-size:large; padding-right:10px;'] ) !!}
                </div>  -->
                @permission('create.catalogos')<a class="btn btn-info btn-lg pull-right" href="{{ route('temperatura.create') }}" role="button">Cargar datos</a>@endpermission
            {!! Form::close() !!}            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-12" id="temp_encontrada" style="height:700px;"></div>
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
    <!-- Pdfmake -->
    {!! Html::script('assets/vendors/pdfmake/build/pdfmake.min.js') !!}
    {!! Html::script('assets/vendors/pdfmake/build/vfs_fonts.js') !!}
    <!-- ECharts -->
    {!! Html::script('assets/vendors/echarts/dist/echarts.min.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/macarons.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/roma.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/shine.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/vintage.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/infographic.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- Mine -->
    {!! Html::script('assets/mine/js/images.js') !!}

    <script>

        /*** Máximos y mínimos ***/
        var data = $.parseJSON(escaparCharEspeciales('{{json_encode($data)}}'));
        var estampas = $.parseJSON(escaparCharEspeciales('{{json_encode($estampas)}}'));
        var maximas = $.parseJSON(escaparCharEspeciales('{{json_encode($maximas)}}'));
        var minimas = $.parseJSON(escaparCharEspeciales('{{json_encode($minimas)}}'));
        var textoMaxMin = escaparCharEspeciales('{{json_encode($texto_max_min)}}'); 

        /*** ... ***/
        console.log(data);
        var tempMaxMin = echarts.init(document.getElementById('temp_encontrada'), 'roma');
        /*var opcionesMaxMin = {
            title: {
                text: 'Beijing AQI'
            },
            tooltip: {
                trigger: 'axis'
            },
            xAxis: {
                data: data.map(function (item) {
                    return item[0];
                })
            },
            yAxis: {
                splitLine: {
                    show: false
                }
            },
            toolbox: {
                left: 'center',
                feature: {
                    dataZoom: {
                        yAxisIndex: 'none'
                    },
                    restore: {},
                    saveAsImage: {}
                }
            },
            dataZoom: [{
                startValue: '2014-06-01'
            }, {
                type: 'inside'
            }],
            visualMap: {
                top: 10,
                right: 10,
                pieces: [{
                    gt: 0,
                    lte: 50,
                    color: '#096'
                }, {
                    gt: 50,
                    lte: 100,
                    color: '#ffde33'
                }, {
                    gt: 100,
                    lte: 150,
                    color: '#ff9933'
                }, {
                    gt: 150,
                    lte: 200,
                    color: '#cc0033'
                }, {
                    gt: 200,
                    lte: 300,
                    color: '#660099'
                }, {
                    gt: 300,
                    color: '#7e0023'
                }],
                outOfRange: {
                    color: '#999'
                }
            },
            series: {
                name: 'Beijing AQI',
                type: 'line',
                data: data.map(function (item) {
                    return item[1];
                }),
                markLine: {
                    silent: true,
                    data: [{
                        yAxis: 50
                    }, {
                        yAxis: 100
                    }, {
                        yAxis: 150
                    }, {
                        yAxis: 200
                    }, {
                        yAxis: 300
                    }]
                }
            }
        };*/
        var opcionesMaxMin = {
            title: {
                text: 'Temperaturas',
                subtext: textoMaxMin
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['Máxima','Mínima']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: {type: ['line', 'bar']},
                    restore: {},
                    saveAsImage: {}
                }
            },
            xAxis:  {
                type: 'category',
                boundaryGap: false,
                data: estampas
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: '{value} °C'
                }
            },
            series: [
                {
                    name:'Máxima',
                    type:'line',
                    data: maximas,
                    markPoint: {
                        data: [
                            {type: 'max', name: 'Máx'},
                            {type: 'min', name: 'Mín'}
                        ]
                    },
                    markLine: {
                        data: [
                            {type: 'average', name: 'Promedio'}
                        ]
                    }
                },
                {
                    name:'Mínima',
                    type:'line',
                    data: minimas,
                    markPoint: {
                        data: [
                            {name: 'Semana mínimo', value: -2, xAxis: 1, yAxis: -1.5}
                        ]
                    },
                    markLine: {
                        data: [
                            {type: 'average', name: 'Promedio'},
                            [{
                                symbol: 'none',
                                x: '90%',
                                yAxis: 'max'
                            }, {
                                symbol: 'circle',
                                label: {
                                    normal: {
                                        position: 'start',
                                        formatter: 'Valor máximo'
                                    }
                                },
                                type: 'max',
                                name: 'Punto más alto'
                            }]
                        ]
                    }
                }
            ]
        };
        tempMaxMin.setOption(opcionesMaxMin);

        $('#por_dia').change(function() {
            if ($(this).is(':checked')){
                $("#form").submit();
            } else {
                $("#fecha_inicial").focus();
            }
        });

        $('#form').keyup(function(e) {
            if(e.keyCode == 13) {
                $("#form").submit();
            }
        });

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
@endsection