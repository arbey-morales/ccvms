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
      {!! Form::open([ 'url' => 'dashboard/vacunacion', 'id' => 'dashboard-form', 'method' => 'GET']) !!}
        <div class="row">
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
              {!! Form::select('municipios_id', [],  0, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipios_id', 'data-placeholder' => 'Todos los municipios', 'style' => 'width:100%'] ) !!}
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 clues">
              {!! Form::select('clues_id', [],  0, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clues_id', 'data-placeholder' => 'Todas la unidad de salud', 'style' => 'width:100%'] ) !!}
            </div>
          </div>
        </div>

      <br />

        <div class="row">
          @role('root|admin|captura')
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel tile fixed_height_340">
              <div class="x_content">

                <div class="row">
                  <div class="col-sm-3">
                    <button id="compose-cb" class="btn btn-sm btn-info btn-block" type="button">COBERTURAS</button>
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('vacunas_id', [], 1, ['class' => 'form-control js-data-vacuna select2', 'style' => 'width:100%'])!!}
                    </div>
                    <!-- <div style="padding-top:10px; padding-bottom:5px;">
                     {!!Form::select('tipo_aplicacion', [], 0, ['class' => 'form-control js-data-tipo-aplicacion select2', 'style' => 'width:100%'])!!}
                    </div> -->
                    <div style="padding-top:10px; padding-bottom:5px;">
                     {!!Form::select('edad', [], 0, ['class' => 'form-control js-data-edad select2', 'style' => 'width:100%'])!!}
                    </div>
                    <!-- <button id="compose-ec" class="btn btn-sm btn-success btn-block" type="button">ESQUEMAS COMPLETOS</button>
                    <button id="compose-c" class="btn btn-sm btn-warning btn-block" type="button">CONCORDANCIA</button> -->
                  </div>
                  <div class="col-sm-9">
                    <div class="row tile_count">
                      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-keyboard-o"></i> RESULTADOS </span>
                        <div class="count total-capturas"> </div>
                        <span class="count_bottom"> <i class="primary"><i class="fa fa-female"></i><i class="total-ninas"> </i> </i> Niñas <i class="green"><i class="fa fa-male"></i><i class="total-ninos"> </i> </i> Niños </span>
                      </div>
                      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-flash"></i> COBERTURAS CENSO NOM.</span>
                        <div class="count total-coberturas"> </div>
                        <span class="count_bottom"> <i class="primary"><i class="fa fa-female"></i><i class="total-coberturas-ninas"> </i> </i> Niñas <i class="green"><i class="fa fa-male"></i><i class="total-coberturas-ninos"> </i> </i> Niños </span>
                      </div>
                      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-flas"></i> % </span>
                        <div class="count porcentaje-coberturas"> </div>
                        <!-- <span class="count_bottom"> <i class="primary"><i class="fa fa-female"></i><i class="porcentaje-coberturas-ninas"> </i> </i> Niñas <i class="green"><i class="fa fa-male"></i><i class="porcentaje-coberturas-ninos"> </i> </i> Niños </span> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endrole

          @role('root|red-frio')
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel fixed_height_340">
              <div class="x_content">
                <div class="row">
                  <div class="col-sm-3">
                    <button id="compose-es" class="btn btn-sm btn-primary btn-block" type="button">FILTROS RED DE FRÍO</button>
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('estatus_contenedor_id', [], 0, ['class' => 'form-control js-data-estatus-contenedor select2', 'style' => 'width:100%'])!!}
                    </div>
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('tipo_contenedor_id', [], 0, ['class' => 'form-control js-data-tipo-contenedor select2', 'style' => 'width:100%'])!!}
                    </div>
                  </div>
                  <!-- /MAIL LIST -->

                  <!-- CONTENT MAIL -->
                  <div class="col-sm-9">
                    <div id="map" class="padre" style="width:100%; height:550px;"> 
                    <div> <span class="hijo"> <i class="fa fa-spin fa-spinner"></i> Cargando </span></div>
                    </div>
                  </div>
                  <!-- /CONTENT MAIL -->
                </div>
                <!-- ESTATUS Y TIPO DE CONTENEDOR -->
                
                <!-- start accordion -->
                <!-- <div class="accordion col-md-4 col-sm-4 col-xs-12" id="accordion" role="tablist" aria-multiselectable="true">            
                </div> -->
                <!-- end of accordion -->
                <!-- <div class="col-md-8 col-sm-8 col-xs-12">
                  <div id="map" class="padre" style="width:100%; height:550px;"> 
                  <div> <span class="hijo"> <i class="fa fa-spin fa-spinner"></i> Cargando </span></div>
                  </div>
                </div> -->
                
              </div>
            </div>
          </div>
          @endrole
          
        </div>
    {!! Form::close() !!}
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAG20do9iIKewhzw2MPwKGQmcdqYg9F6U&callback=ubicacionContenedores"
    async defer></script>
@endsection