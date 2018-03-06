@extends('app')
@section('title')
    Dashboard
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
    <!-- Select2 -->
    {!! Html::style('assets/vendors/select2-4.0.3/dist/css/select2.css') !!}
    <!-- Switchery -->
    {!! Html::style('assets/vendors/switchery/dist/switchery.min.css') !!}
@endsection
@section('content') 
    @include('errors.msgAll')
    <!-- page content -->
    <div role="main">
    <!-- form -->
    <div class="row">
      @role('root|admin')
        <div class="col-md-12">
            {!!Form::select('jurisdicciones_id', [], 0, ['class' => 'form-control js-data-jurisdiccion select2', 'style' => 'width:100%'])!!}
        </div>
      @endrole
    </div>
    <!-- top tiles -->
    <div class="row tile_count">
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-keyboard-o"></i> Capturas totales</span>
        <div class="count total-capturas"> </div>
        <span class="count_bottom"> <i class="primary"><i class="fa fa-female"></i><i class="total-ninas"> </i> </i> Niñas <i class="green"><i class="fa fa-male"></i><i class="total-ninos"> </i> </i> Niños </span>
      </div>
      <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-clock-o"></i> Cap. Diaria</span>
        <div class="count">123.50</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
      </div> -->
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total contenedores</span>
        <div class="count green total-contenedores"> </div>
      </div>
      <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Females</span>
        <div class="count">4,567</div>
        <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
        <div class="count">2,315</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
        <div class="count">7,325</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div> -->
    </div>
    <!-- /top tiles -->
    <br />

    <div class="row">


      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel tile fixed_height_320">
          <div class="x_title">
            <h2>Contenedores/Estatus</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
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
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" id="contenedores" style="height:100%;">
          
          </div>
        </div>
      </div>

      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel tile fixed_height_320">
          <div class="x_title">
            <h2>Quick Settings</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
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
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          


          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- /page content -->
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
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
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
        $('body').removeClass('nav-md');
        $('body').addClass('nav-sm');
        // MASCARA TIPO DD-MM-AAAA
        $("#fecha").mask("99-99-9999");



        var te_contenedores = [];
        var jurisdicciones = [{ 'id': 0, 'text': 'Seleccionar una jurisdicción' }];
        $.get('catalogo/red-frio/estatus-contenedor', {}, function(response, status){ // Consulta
          if(response.data==null){
            notificar('Error','Sin datos','error',2000);
          } else { 
            te_contenedores = response.data;
          }
        }).fail(function(){ 
            notificar('Error','Error interno','error',2000);
        });

        $.get('catalogo/jurisdiccion', {}, function(response, status){ // Consulta
          if(response.data==null){
            notificar('Error','Sin datos','error',2000);
          } else { 
            while (jurisdicciones.length) { jurisdicciones.pop(); }                
            jurisdicciones.push({ 'id': 0, 'text': 'Seleccionar una jurisdicción' });           
            if(response.data.length<=0){
                notificar('Información','No existen jurisdicciones','warning',2000);
            } else {
                //notificar('Información','Cargando jurisdicciones','info',2000);
                $('.js-data-jurisdiccion').empty();                      
                $.each(response.data, function( i, cont ) {
                    jurisdicciones.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
                });  
            }  
            $(".js-data-jurisdiccion").select2({
                language: "es",
                data: jurisdicciones
            });
          }
        }).fail(function(){ 
            notificar('Error','Error interno','error',2000);
        });





        
        $(document).ready(function(){
          $(".js-data-jurisdiccion").select2({
            language: "es",
            data: jurisdicciones
          });
          capturas(); contenedoresEstatus();
        });
        

        var capturas = function() {
            $.get('dashboard/capturas', {}, function(response, status){ // Consulta
              if(response.data==null){
                notificar('Error','Sin datos','error',2000);
              } else { 
                construirCapturas(response.data[0]);
              }
            }).fail(function(){ 
                notificar('Error','Error interno','error',2000);
            });
        }

        var contenedoresEstatus = function(){
          $.get('dashboard/contenedores-status', {}, function(response, status){ // Consulta
            if(response.data==null){
              notificar('Error','Sin datos','error',2000);
            } else { 
              construirContenedores(response.data[0]);
            }
          }).fail(function(){ 
              notificar('Error','Error interno','error',2000);
          });
        }

        function construirCapturas(datos){
            $(".total-capturas").empty().html(numberWithCommas(parseInt(datos.todos)));
            $(".total-ninos").empty().html(numberWithCommas(parseInt(datos.ninos)));
            $(".total-ninas").empty().html(numberWithCommas(parseInt(datos.ninas)));
        }

        var construirContenedores = function(datos){
          $(".total-contenedores").empty().html(numberWithCommas(parseInt(datos.todos)));          
          var contenedores = echarts.init(document.getElementById('contenedores'));
          option = {
              tooltip: {
                  trigger: 'item',
                  formatter: "{a} <br/>{b}: {c} ({d}%)"
              },
              color:[te_contenedores[0].color,te_contenedores[1].color,te_contenedores[2].color,te_contenedores[3].color,te_contenedores[4].color],
              legend: {
                  orient: 'vertical',
                  x: 'left',
                  data:[te_contenedores[0].descripcion,te_contenedores[1].descripcion,te_contenedores[2].descripcion,te_contenedores[3].descripcion,te_contenedores[4].descripcion]
              },
              series: [
                  {
                      name:'Estatus',
                      type:'pie',
                      radius: ['50%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                          normal: {
                              show: false,
                              position: 'center'
                          },
                          emphasis: {
                              show: true,
                              textStyle: {
                                  fontSize: '30',
                                  fontWeight: 'bold'
                              }
                          }
                      },
                      labelLine: {
                          normal: {
                              show: false
                          }
                      },
                      data:[
                          {value:datos.danado, name:te_contenedores[0].descripcion},
                          {value:datos.funcionando, name:te_contenedores[1].descripcion},
                          {value:datos.desconectado, name:te_contenedores[2].descripcion},
                          {value:datos.espera, name:te_contenedores[3].descripcion},
                          {value:datos.sindatos, name:te_contenedores[4].descripcion}
                      ]
                  }
              ]
          };

          contenedores.setOption(option);
        }

    

        var  escaparCharEspeciales = function(str)
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

        var numberWithCommas = function (x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        

        function construirTabla() {

        }            
    </script>
@endsection