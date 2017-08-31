@extends('app')
@section('title')
  Tablero
@endsection
@section('my_styles')
 <!-- Select2 -->
 {!! Html::style('assets/vendors/select2-4.0.3/dist/css/select2.css') !!}
 <!-- Switchery -->
 {!! Html::style('assets/vendors/switchery/dist/switchery.min.css') !!}
 <!-- Bootstrap Colorpicker -->
 {!! Html::style('assets/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') !!}
 <!-- Form Mine -->
 {!! Html::style('assets/mine/css/uikit.almost-flat.min.css') !!}
 {!! Html::style('assets/mine/css/form.css') !!}
@endsection
@section('content') 
    <div class="container">
      <div class="row">
        <div  class="col-md-4" id="graph_one" style="height:350px;"> </div>
        <div  class="col-md-4" id="capturas_por_biologico" style="height:350px;"> </div>
        <div  class="col-md-4" id="qw" style="height:350px;"> </div>
      </div>
      <div class="row">
        <div  class="col-md-4" id="capturass" style="height:350px;"> </div>
        <div  class="col-md-4" id="capturasss" style="height:350px;"> </div>
        <div  class="col-md-4" id="capturassss" style="height:350px;"> </div>
      </div>
    </div>
@endsection
@section('my_scripts')
    <!-- ECharts -->
    {!! Html::script('assets/vendors/echarts/dist/echarts.min.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/macarons.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/roma.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/shine.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/vintage.js') !!}
    {!! Html::script('assets/vendors/echarts/theme/infographic.js') !!}
    
    <script>
      $(document).ready(function(){
        $.get('dashboard', {}, function(response, status){ // Consulta CURP
            console.log(response);
        }).fail(function(){  // Calcula CURP                    
          notificar('Información','No se cargó información al Dashboard','info',4000);
        });
      });

      /*var graph_one = echarts.init(document.getElementById('graph_one'), 'roma');
        var option = {
            title : {
                text: 'Biologico',
                subtext: '...........',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:['Aplicadas','Faltantes','One','Two','three','Four']
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
                    name:'Grafica uno',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        {value:34, name:'Aplicadas'},
                        {value:3, name:'Faltantes'},
                        {value:34, name:'One'},
                        {value:3, name:'Two'},
                        {value:34, name:'Three'},
                        {value:3, name:'Four'}
                    ]
                }
            ]
        };
        graph_one.setOption(option);*/
    </script>
@endsection