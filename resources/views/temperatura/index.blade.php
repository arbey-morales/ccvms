@extends('app')
@section('title')
    Temperatura
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
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
        @permission('create.catalogos')<a class="btn btn-default btn-lg pull-right" href="{{ route('temperatura.create') }}" role="button">Cargar datos</a>@endpermission
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-12" id="temp_encontrada" style="height:750px;"></div>
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
    <!-- Mine -->
    {!! Html::script('assets/mine/js/images.js') !!}

    <script>
        var data = $.parseJSON(escaparCharEspeciales('{{json_encode($data)}}'));
        var dateList = data.map(function (item) {
    return item[0];
});
var valueList = data.map(function (item) {
    return item[1];
});
        var tempEncontrada = echarts.init(document.getElementById('temp_encontrada'), 'roma');
        var optionIdeal = {
            
                // Make gradient line here
                visualMap: [{
                    show: false,
                    type: 'continuous',
                    seriesIndex: 0,
                    min: 0,
                    max: 400
                }, {
                    show: false,
                    type: 'continuous',
                    seriesIndex: 1,
                    dimension: 0,
                    min: 0,
                    max: dateList.length - 1
                }],
            
            
                title: [{
                    left: 'center',
                    text: 'Gradient along the y axis'
                }, {
                    top: '55%',
                    left: 'center',
                    text: 'Gradient along the x axis'
                }],
                tooltip: {
                    trigger: 'axis'
                },
                xAxis: [{
                    data: dateList
                }, {
                    data: dateList,
                    gridIndex: 1
                }],
                yAxis: [{
                    splitLine: {show: false}
                }, {
                    splitLine: {show: false},
                    gridIndex: 1
                }],
                grid: [{
                    bottom: '60%'
                }, {
                    top: '60%'
                }],
                series: [{
                    type: 'line',
                    showSymbol: false,
                    data: valueList
                }, {
                    type: 'line',
                    showSymbol: false,
                    data: valueList,
                    xAxisIndex: 1,
                    yAxisIndex: 1
                }]
            };
        tempEncontrada.setOption(optionIdeal);

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