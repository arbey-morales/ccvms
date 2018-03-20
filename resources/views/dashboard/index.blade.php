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
      .padre {
        background: #023E58;
        height: 150px;
        /*IMPORTANTE*/
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .hijo {
        color:white;
        font-weight: thinner;      
        height: 100%;
        width: 100%;
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
          <!-- UBICACIÓN -->
          <div class="row">
            <div class="col-md-12">
              Filtros de ubicación
            </div>
          </div>
          <div class="row">          
            @role('root|admin|red-frio')
              <div class="col-md-4 col-sm-6 col-xs-12">
                {!!Form::select('jurisdicciones_id', [], 0, ['class' => 'form-control js-data-jurisdiccion select2', 'style' => 'width:100%'])!!}
              </div>
            @endrole
            <div class="col-md-4 col-sm-6 col-xs-12 municipios">
              {!! Form::select('municipios_id', [],  0, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipios_id', 'data-placeholder' => 'Municipio', 'style' => 'width:100%'] ) !!}
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 clues">
              {!! Form::select('clues_id', [],  0, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clues_id', 'data-placeholder' => 'Unidad de salud', 'style' => 'width:100%'] ) !!}
            </div>
          </div>
          <!-- BIOlÓGICO Y TIPO DE DOSIS -->
          <div class="row">
            <div class="col-md-12">
              Biológico y tipo de dosis
            </div>
          </div>
          <div class="row">
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
      <div class="col-md-12 col-sm-12 col-xs-12 other-status">
      </div>
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
              <div id="map" class="padre" style="width:100%; height:550px;"> 
               <div> <span class="hijo"> <i class="fa fa-spin fa-spinner"></i> Cargando </span></div>
              </div>
              <!--<div class="jumbotron" style="text-align:center;">
                <span type="button" id="total-contenedores" class="btn" style="background-color:white; border-radius: 200px; font-size:80px; font-weight:thin; padding:15px 20px;">
                </span>
                <p>Contenedores</p>
              </div>-->
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
    <!-- Build -->
    {!! Html::script('assets/build/js/dashboard/dashboard.js') !!}
    {!! Html::script('assets/build/js/dashboard/style.map.js') !!}
    {!! Html::script('assets/build/js/dashboard/red.frio.mapa.js') !!}
    {!! Html::script('assets/build/js/dashboard/vacunacion.js') !!}
    <script>
      
      
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAG20do9iIKewhzw2MPwKGQmcdqYg9F6U&callback=initMap"
    async defer></script>
@endsection