@extends('app')
@section('title')
    Temperatura
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
    <!-- Select2 -->
    {!! Html::style('assets/vendors/select2-4.0.3/dist/css/select2.css') !!}
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
        <h2><i class="fa fa-sun-o"></i> Temperaturas <i class="fa fa-angle-right text-danger"></i><small> Filtros</small></h2>
            @permission('create.catalogos')<a class="btn btn-info btn-lg pull-right" href="{{ route('temperatura.create') }}" role="button">Cargar nueva medición</a>@endpermission
            <div class="clearfix"></div>
        </div>
        <div class="x-content">
            {!! Form::open([ 'route' => 'temperatura.index', 'id' => 'dato', 'name' => 'dato', 'method' => 'GET']) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::label('clues_id', '* Unidad de salud', ['for' => 'clues_id'] ) !!}
                        {!! Form::select('clues_id', [],  0, ['class' => 'form-control js-data-clues select2', 'data-parsley-required' => 'true', 'id' => 'clues_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('contenedores_id', '* Contenedor de biológico', ['for' => 'contenedores_id'] ) !!}
                        {!! Form::select('contenedores_id', [],  null, ['class' => 'form-control js-data-contenedores select2', 'data-parsley-required' => 'true', 'id' => 'contenedores_id', 'data-placeholder' => '* Contenedor de biológico', 'style' => 'width:100%'] ) !!}
                    </div>                
                </div>
                <div class="row">
                    <div class="col-md-4">   
                        {!! Form::label('fecha_inicial', 'Fecha inicial', ['for' => 'fecha_inicial'] ) !!}                 
                        {!! Form::text('fecha_inicial', null, ['class' => 'form-control search', 'id' => 'fecha_inicial', 'autocomplete' => 'off', 'placeholder' => '2017-01-02' ]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('fecha_final', 'Fecha final', ['for' => 'fecha_final'] ) !!}
                        {!! Form::text('fecha_final', null, ['class' => 'form-control search', 'id' => 'fecha_final', 'autocomplete' => 'off', 'placeholder' => '2017-01-02' ]) !!}
                    </div>
                    <div class="col-md-4">
                        <br>
                        <span id="cargando"></span>
                        @permission('show.catalogos')<button type="button" class="btn btn-primary js-buscar btn-lg pull-right"> <i class="fa fa-search"></i> Buscar  </button>@endpermission
                    </div>
                </div>
            {!! Form::close() !!} 
        </div>
        <div class="x_content"><br>
            <div class="alert alert-success" role="alert">
                <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                <span class="sr-only">Variación</span> Muestra todas las medidas de temperartura de un día, sólo si la fecha inicial y la final son la misma.
            </div>
            <div class="col-md-12" id="variaciones" style="height:700px;"></div> 
            <div class="clearfix"></div>
            <div class="alert alert-warning" role="alert">
                <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                <span class="sr-only">Máximas y mínimas</span> Muestra la máxima y mínima temperartura de cada día, especificando el contenedor de biológico.
            </div>
            <div class="col-md-12" id="maximas_minimas" style="height:700px;"></div>
                       
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
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- Mine -->
    {!! Html::script('assets/mine/js/images.js') !!}

    <script>
        // Al segundo busca los contenedores de la clue seleccionada
        $(document).ready(function(){
            //setTimeout(function(){ $(".js-data-clues").change(); }, 1000);
            $("#fecha_inicial,#fecha_final").mask("9999-99-99");
            $("#fecha_inicial").val(moment(new Date()).add(-1, 'days').format('YYYY-MM-DD'));
            $("#fecha_final").val(moment(new Date()).format('YYYY-MM-DD'));

            $("#fecha_inicial,#fecha_final").blur(function(e){
                if (moment($(this).val(),'YYYY-MM-DD',true).isValid()) {
                } else {                    
                    notificar('Verifique','La fecha no es válida','warning',1000);
                    $(this).focus();
                }
            });

        });
        // valor inicial del select contenedores
        var contenedores = [{ 'id': 0, 'text': 'Seleccionar contenedor' }];
        var clues = [{ 'id': 0, 'clues':'', 'text': '* Unidad de salud' }];
        $(".js-data-contenedores").select2();
        // Si la clues cambia debe  buscar sus contenedores
        $(".js-data-clues").change(function(){
            var clue_id = $(this).val();
            $.get('../catalogo/red-frio/contenedor-biologico/',{clues_id:clue_id}, function(response, status){ // Consulta        
                $('.js-data-contenedores').empty();
                while (contenedores.length) { contenedores.pop(); }                
                contenedores.push({ 'id': 0, 'text': 'Seleccionar contenedor' });  
                $.each(response.data, function( i, cont ) {
                    contenedores.push({ 'id': cont.id, 'text': cont.tipo_contenedor.nombre+': '+cont.modelo.marca.nombre+'/'+cont.modelo.nombre+'. Serie: '+cont.serie });
                });
                $(".js-data-contenedores").select2({
                    language: "es",
                    data: contenedores
                });   
                notificar('Información','Se cargaron '+(contenedores.length - 1)+' contenedores','success',1000);     
            }).fail(function(){  // Calcula CURP
                notificar('Información','No se consultaron los contenedores de la unidad seleccionada','warning',2000);
            });
        });

        $(".js-data-clues").select2({
            ajax: {
                url: "../catalogo/clue-contenedor",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
                },
                processResults: function (data, params) {            
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: $.map(data.data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
                        return {
                            id:        item.id,
                            clues:     item.clues,
                            text:      item.nombre
                        }
                    }),
                    pagination: {
                    more: (params.page * 30) < data.total_count
                    }
                };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 5,
            language: "es",
            placeholder: {
                id: clues[0].id, 
                clues: clues[0].clues,
                text: clues[0].text
            },
            cache: true,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });

        function formatRepo (clues) {
            if (!clues.id) { return clues.text; }
            var $clues = $(
                '<span class="">' + clues.clues + ' - '+ clues.text +'</span>'
            );
            return $clues;
        };
        function formatRepoSelection (clues) {
            if (!clues.id) { return clues.text; }
            var $clues = $(
                '<span class="results-select2"> ' + clues.clues+ ' - '+ clues.text +'</span>'
            );
            return $clues;
        };

        var buscando = false;
        // Botón buscar
        $(".js-buscar").click(function(){     
            if(!buscando) {      
                var dato = $("#dato");
                var url  = dato.attr('action');
                var dato = $("#dato").serialize();
                $(".js-buscar").attr('disabled','disabled');                
                //$(this).addClass('hidden');
                if($("#fecha_inicial").val()==null || $("#fecha_inicial").val()=="" || $("#fecha_final").val()==null || $("#fecha_final").val()=="" || $("#clues_id").val()==0 || $("#contenedores_id").val()==0){
                    notificar('Información','Debe agregar fecha inicial y final, además de seleccionar unidad de salud y contenedor de biológico','warning',2000);
                    $(".js-buscar").removeAttr('disabled');
                    $("#cargando").empty();
                } else{                
                    $("#cargando").empty().html('<br><i class="fa fa-circle-o-notch fa-spin"></i> Buscando...');
                    buscando = true;
                    $.get(url, dato, function(response, status){ // Consulta  
                        buscando = false;  
                        $("#cargando").empty();
                        $(".js-buscar").removeAttr('disabled');                  
                        if(response.data.variacion.data.length>0){
                            graficaVariacion(response.data.variacion.data, response.data.texto,response.data.sub_texto);
                        } else {
                            notificar('Información','Sin resultados en la gráfica de variación','info',2000);
                        }
                        
                        if(response.data.variacion.data.length>0){
                            graficaMaximaMinima(response.data.maxima_minima, response.data.texto,response.data.sub_texto);                    
                        } else {
                            notificar('Información','Sin resultados en la gráfica de máximas y mínimas','info',2000);
                        }
                    }).fail(function(){  // Calcula CURP
                        $(".js-buscar").removeAttr('disabled');
                        $("#cargando").empty();
                        buscando = false;
                        notificar('Información','No se consultaron los contenedores de la unidad seleccionada','warning',2000);
                    });
                }
            }
        });        
        // Inicializa select contenedores
        $(".js-data-contenedores").select2({
            language: "es",
            data: contenedores
        });
        
        function graficaVariacion(datos,texto,sub_texto){
            var data = [];
            $.each(datos, function( i, cont ) {
                data.push([cont.fecha+' '+cont.hora,cont.temperatura]);
            });
            
            var variaciones = echarts.init(document.getElementById('variaciones'), 'macarons');
            var opcionesVariacion = {
                title: {
                    text: texto,
                    subtext: 'Variaciones de temperatura del: '+sub_texto
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
                    left: 'right',
                    feature: {
                        restore : {show: true, title:'Restaurar'},
                        saveAsImage : {show: true, title:'Guardar imagen'}
                    }
                },
                dataZoom: [{
                    startValue: data[0][0] // '2014-06-01'
                }, {
                    type: 'inside'
                }],
                /*visualMap: {
                    top: 10,
                    right: 10,
                    pieces: [{
                        gt: 0,
                        lte: 2,
                        color: '#096'
                    }, {
                        gt: 2,
                        lte: 8,
                        color: '#ffde33'
                    }, {
                        gt: 8,
                        color: '#7e0023'
                    }],
                    outOfRange: {
                        color: '#999'
                    }
                },*/
                series: {
                    name: 'Temp',
                    type: 'line',
                    data: data.map(function (item) {
                        return item[1];
                    }),
                    markLine: {
                        silent: true,
                        data: [{
                            yAxis: 2
                        }, {
                            yAxis: 8
                        }, {
                            yAxis: 2
                        }]
                    }
                }            
            };

            variaciones.setOption(opcionesVariacion);
        }
        function graficaMaximaMinima(data,texto,sub_texto){
            var maximasMinimas = echarts.init(document.getElementById('maximas_minimas'), 'shine');
            var opcionesMaxMin = {
                title: {
                    text: texto,
                    subtext: 'Temperaturas máximas y mínimas del: '+sub_texto
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
                        magicType: {
                            title:{line:'lineas',bar:'Barras',stack:'lines',tiled:'lines',force:'lines',pie:'lines',chord:'lines',funnel:'lines'},
                            type: ['line', 'bar']
                        },
                        restore : {show: true, title:'Restaurar'},
                        saveAsImage : {show: true, title:'Guardar imagen'}
                    }
                },
                xAxis:  {
                    type: 'category',
                    boundaryGap: false,
                    data: data.estampas
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
                        data: data.maximas,
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
                        data: data.minimas,
                        markPoint: {
                            data: [
                                {name: 'Valor mínimo', value: -2, xAxis: 1, yAxis: -1.5}
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

            maximasMinimas.setOption(opcionesMaxMin);
        }
        // Escapa caracteres especiales
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