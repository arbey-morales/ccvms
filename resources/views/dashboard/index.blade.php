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
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
@endsection
@section('content') 
    @include('errors.msgAll')
    
    <!-- page content -->
    <div role="main">
    <!-- form -->
    <div class="row">
      
        {!! Form::open([ 'url' => 'dashboard/vacunacion', 'id' => 'vacunacion-form', 'method' => 'GET']) !!}
          <div class="row">
            @role('root|admin|red-frio')
              <div class="col-md-4 col-sm-6 col-xs-12">
                {!!Form::select('jurisdicciones_id', [], 0, ['class' => 'form-control js-data-jurisdiccion select2', 'style' => 'width:100%'])!!}
              </div>
            @endrole
            <div class="col-md-4 col-sm-6 col-xs-12"> 
              {!!Form::select('vacunas_id', [], 0, ['class' => 'form-control js-data-vacuna select2', 'style' => 'width:100%'])!!}
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12"> 
              {!!Form::select('tipo_aplicacion', [], 0, ['class' => 'form-control js-data-tipo-aplicacion select2', 'style' => 'width:100%'])!!}
            </div>
          </div>
        {!! Form::close() !!}
     
    </div>

    <br />

    <div class="row">
      @role('root|admin|captura')
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel tile fixed_height_340">
          <div class="x_title">
            <h2>VACUNACIÓN</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <!-- <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Settings 1</a>
                  </li>
                  <li><a href="#">Settings 2</a>
                  </li>
                </ul>
              </li> -->
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            
            <!-- top tiles -->
            <div class="row tile_count">
              <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-keyboard-o"></i> Capturas totales</span>
                <div class="count total-capturas"> </div>
                <span class="count_bottom"> <i class="primary"><i class="fa fa-female"></i><i class="total-ninas"> </i> </i> Niñas <i class="green"><i class="fa fa-male"></i><i class="total-ninos"> </i> </i> Niños </span>
              </div>
            </div>
            <!-- /top tiles -->
            
          </div>
        </div>
      </div>
      @endrole

      @role('root|red-frio')
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel tile fixed_height_340">
          <div class="x_title">
            <h2>RED DE FRÍO</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <!-- <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Settings 1</a>
                  </li>
                  <li><a href="#">Settings 2</a>
                  </li>
                </ul>
              </li> -->
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- start accordion -->
            <div class="accordion col-md-4 col-sm-4 col-xs-12" id="accordion" role="tablist" aria-multiselectable="true">            
            </div>
            <!-- end of accordion -->
            <div class="col-md-8 col-sm-8 col-xs-12">
              <div id="map" style="width:100%; height:550px;"></div>
              <div class="jumbotron" style="text-align:center;">
                <span type="button" id="total-contenedores" class="btn" style="background-color:white; border-radius: 200px; font-size:80px; font-weight:thin; padding:15px 20px;">
                </span>
                <p>Contenedores</p>
              </div>
            </div>
            
          </div>
        </div>
      </div>
      @endrole

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
      var map;
      function initMap() {
        // Styles a map in night mode.
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 16.3074731, lng: -92.8639654},
          zoom: 8,
          styles: [
            {
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#1d2c4d"
                }
              ]
            },
            {
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#8ec3b9"
                }
              ]
            },
            {
              "elementType": "labels.text.stroke",
              "stylers": [
                {
                  "color": "#1a3646"
                }
              ]
            },
            {
              "featureType": "administrative",
              "elementType": "geometry",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "administrative.country",
              "elementType": "geometry.stroke",
              "stylers": [
                {
                  "color": "#4b6878"
                }
              ]
            },
            {
              "featureType": "administrative.land_parcel",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#64779e"
                }
              ]
            },
            {
              "featureType": "administrative.province",
              "elementType": "geometry.stroke",
              "stylers": [
                {
                  "color": "#4b6878"
                }
              ]
            },
            {
              "featureType": "landscape.man_made",
              "elementType": "geometry.stroke",
              "stylers": [
                {
                  "color": "#334e87"
                }
              ]
            },
            {
              "featureType": "landscape.natural",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#023e58"
                }
              ]
            },
            {
              "featureType": "poi",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "poi",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#283d6a"
                }
              ]
            },
            {
              "featureType": "poi",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#6f9ba5"
                }
              ]
            },
            {
              "featureType": "poi",
              "elementType": "labels.text.stroke",
              "stylers": [
                {
                  "color": "#1d2c4d"
                }
              ]
            },
            {
              "featureType": "poi.park",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "color": "#023e58"
                }
              ]
            },
            {
              "featureType": "poi.park",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#3C7680"
                }
              ]
            },
            {
              "featureType": "road",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#304a7d"
                }
              ]
            },
            {
              "featureType": "road",
              "elementType": "labels.icon",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "road",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#98a5be"
                }
              ]
            },
            {
              "featureType": "road",
              "elementType": "labels.text.stroke",
              "stylers": [
                {
                  "color": "#1d2c4d"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#2c6675"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "geometry.stroke",
              "stylers": [
                {
                  "color": "#255763"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#b0d5ce"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "labels.text.stroke",
              "stylers": [
                {
                  "color": "#023e58"
                }
              ]
            },
            {
              "featureType": "transit",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "transit",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#98a5be"
                }
              ]
            },
            {
              "featureType": "transit",
              "elementType": "labels.text.stroke",
              "stylers": [
                {
                  "color": "#1d2c4d"
                }
              ]
            },
            {
              "featureType": "transit.line",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "color": "#283d6a"
                }
              ]
            },
            {
              "featureType": "transit.station",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#3a4762"
                }
              ]
            },
            {
              "featureType": "water",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#0e1626"
                }
              ]
            },
            {
              "featureType": "water",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#4e6d70"
                }
              ]
            }
          ]
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAG20do9iIKewhzw2MPwKGQmcdqYg9F6U&callback=initMap"
    async defer></script>
    <script>
        // Colapsa el menu de la izquierda
        $('body').removeClass('nav-md');
        $('body').addClass('nav-sm');

        // Inicio de valores iniciales
        var jurisdicciones = [{ 'id': 0, 'text': 'Todas las Jurisdicciones' }];
        var dosis = [
                      { 'id': 0, 'text': 'Todas las aplicaciones/dosis' },
                      { 'id': 1, 'text': 'Dosis única' },
                      { 'id': 2, 'text': '1a dosis' },
                      { 'id': 3, 'text': '2a dosis' },
                      { 'id': 4, 'text': '3a dosis' },
                      { 'id': 5, 'text': '4a dosis' },
                      { 'id': 6, 'text': 'Refuerzo' }
                    ];
        var dosis_vacunas = [];
        var vacunas = [{ 'id': 0, 'text': 'Todas las Vacunas' }];

        $(document).ready(function(){
          $(".js-data-jurisdiccion").select2({
            language: "es",
            data: jurisdicciones
          });
          $(".js-data-tipo-aplicacion").select2({
            language: "es",
            data: dosis
          });
          $(".js-data-vacuna").select2({
            language: "es",
            data: vacunas
          });
          vacunacion(); contenedoresEstatus();
        });
        // Fin de valores iniciales
          
        $.get('catalogo/jurisdiccion', {}, function(response, status){ // Consulta
          if(response.data==null){
            notificar('Error','Sin datos','error',2000);
          } else { 
            while (jurisdicciones.length) { jurisdicciones.pop(); }                
            jurisdicciones.push({ 'id': 0, 'text': 'Todas las Jurisdicciones' });           
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

        $.get('catalogo/vacuna', {}, function(response, status){ // Consulta
          if(response.data==null){
            notificar('Error','Sin datos','error',2000);
          } else { 
            while (vacunas.length) { vacunas.pop(); }                
            vacunas.push({ 'id': 0, 'text': 'Todas las vacunas' });           
            if(response.data.length<=0){
                notificar('Información','No existen vacunas','warning',2000);
            } else {

              //notificar('Información','Cargando vacunas','info',2000);
              $('.js-data-vacuna').empty(); 
                                   
              $.each(response.data, function( i, cont ) {
                var dv = [];
                var temp = [];
                while (dv.length) { dv.pop(); }
                while (temp.length) { temp.pop(); }
                $.each(cont.vacunas_esquemas, function( ive, contve ) {
                  if(ive==0)
                    dv.push(dosis[0]);
                  if(temp.indexOf(contve.tipo_aplicacion) != -1){ 
                  } else {
                    dv.push(dosis[contve.tipo_aplicacion]);  temp.push(contve.tipo_aplicacion);
                  }
                });
                dosis_vacunas[cont.id]= dv;
                vacunas.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
              });  
              //console.log(dosis_vacunas)
            }  
            $(".js-data-vacuna").select2({
                language: "es",
                data: vacunas
            }); 
          }
        }).fail(function(){ 
            notificar('Error','Error interno','error',2000);
        });
        
          
        $(".js-data-vacuna").change(function(){
          var id = $(this).val();
          var dss = dosis_vacunas[id];
          $('.js-data-tipo-aplicacion').empty();
          $(".js-data-tipo-aplicacion").select2({
            language: "es",
            data: dss
          }); 

          vacunacion();
        });

        $(".js-data-tipo-aplicacion,.js-data-jurisdiccion").change(function(){
          vacunacion();
        });

        var vacunacion = function() {
          var datos = $("#vacunacion-form").serialize();
          $.get('dashboard/vacunacion', datos, function(response, status){ // Consulta
            if(response.data==null){
              notificar('Error','Sin datos','error',2000);
            } else { 
              construirVacunacion(response.data);
            }
          }).fail(function(){ 
              notificar('Error','Error interno','error',2000);
          });
        }

        var contenedoresEstatus = function(){
          $.get('dashboard/contenedores-biologico', {}, function(response, status){ // Consulta
            if(response.data==null){
              notificar('Error','Sin datos','error',2000);
            } else { 
              construirContenedores(response.data);
            }
          }).fail(function(){ 
              notificar('Error','Error interno','error',2000);
          });
        }

        function construirVacunacion(datos){
            $(".total-capturas").empty().html(numberWithCommas(parseInt(datos.biologico.todos)));
            $(".total-ninos").empty().html(numberWithCommas(parseInt(datos.biologico.ninios)));
            $(".total-ninas").empty().html(numberWithCommas(parseInt(datos.biologico.ninias)));
        }

        var construirContenedores = function(datos){
          var statusContenedores = '';
          var accordion = ''; var aria = 'true'; var collapsedhead = 'collapse';
          var total = 0;
          datos.forEach(element => {

            accordion+= `<div class="panel">
                            <a class="panel-heading `+collapsedhead+`" role="tab" id="heading`+element.id+`" data-toggle="collapse" data-parent="#accordion" href="#panel`+element.id+`" aria-expanded="`+aria+`" aria-controls="panel`+element.id+`">
                              <button type="button" class="btn btn-round" style="background-color:`+element.color+`; padding:8px 10px;">
                                <i class="fa `+element.icono+`" style="color:white; font-size:large;"></i> <span style="color:white; font-size:large;">`+element.total+`</span>
                              </button> <span class="panel-title">`+element.descripcion+`</span>
                            </a>
                            <div id="panel`+element.id+`" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading`+element.id+`">
                              <div class="panel-body">`; 
                              element.tipos.forEach(tipo => {
                                accordion+= `<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                <div class="tile-stats">
                                                  <div class="icon"> <img width="35px" src="images/tipos-contenedores/`+tipo.imagen+`.png">
                                                  </div>
                                                  <div class="count">`+tipo.total+`</div>

                                                  <h3>`+tipo.clave+`</h3>
                                                  <p>`+tipo.nombre+`</p>
                                                </div>
                                              </div>`;
                              });
            accordion+= `</div>
                            </div>
                          </div>`;
            statusContenedores+= `<li>
                                    <button type="button" class="btn btn-round" style="background-color:`+element.color+`; padding:8px 10px;">
                                      <i class="fa `+element.icono+`" style="color:white; font-size:large;"></i> `+element.total+`
                                    </button>`+element.descripcion+`
                                  </li>`;

            total = total + parseInt(element.total);
            
            if(aria=='true'){
              aria = 'false'; collapsedhead = '';
            }
          });

          $("#total-contenedores").empty().html(total);
          $("#accordion").empty().html(accordion);
          //'<li> <button type="button" class="btn btn-round" style="background-color:'+element.color+'; padding:8px 10px;"><i class="fa '+element.icono+'" style="color:white; font-size:large;"></i></button>'+element.descripcion+'</li>'
          //$(".total-contenedores").empty().html(numberWithCommas(parseInt(datos.todos)));          
          // var contenedores = echarts.init(document.getElementById('contenedores'));
          // option = {
          //     tooltip: {
          //         trigger: 'item',
          //         formatter: "{a} <br/>{b}: {c} ({d}%)"
          //     },
          //     color:[te_contenedores[0].color,te_contenedores[1].color,te_contenedores[2].color,te_contenedores[3].color,te_contenedores[4].color],
          //     legend: {
          //         orient: 'vertical',
          //         x: 'left',
          //         data:[te_contenedores[0].descripcion,te_contenedores[1].descripcion,te_contenedores[2].descripcion,te_contenedores[3].descripcion,te_contenedores[4].descripcion]
          //     },
          //     series: [
          //         {
          //             name:'Estatus',
          //             type:'pie',
          //             radius: ['50%', '70%'],
          //             avoidLabelOverlap: false,
          //             label: {
          //                 normal: {
          //                     show: false,
          //                     position: 'center'
          //                 },
          //                 emphasis: {
          //                     show: true,
          //                     textStyle: {
          //                         fontSize: '30',
          //                         fontWeight: 'bold'
          //                     }
          //                 }
          //             },
          //             labelLine: {
          //                 normal: {
          //                     show: false
          //                 }
          //             },
          //             data:[
          //                 {value:datos.danado, name:te_contenedores[0].descripcion},
          //                 {value:datos.funcionando, name:te_contenedores[1].descripcion},
          //                 {value:datos.desconectado, name:te_contenedores[2].descripcion},
          //                 {value:datos.espera, name:te_contenedores[3].descripcion},
          //                 {value:datos.sindatos, name:te_contenedores[4].descripcion}
          //             ]
          //         }
          //     ]
          // };

          // contenedores.setOption(option);
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