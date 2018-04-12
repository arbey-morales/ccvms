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
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel tile fixed_height_340">
              <div class="x_content">
                <div class="row text-muted well well-sm no-shadow">          
                  @role('root|admin|red-frio')
                    <div class="col-md-4 col-sm-12 col-xs-12" style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('jurisdicciones_id', [], 0, ['class' => 'form-control js-data-jurisdiccion select2', 'style' => 'width:100%'])!!}
                    </div>
                  @endrole
                  <div class="col-md-4 col-sm-12 col-xs-12 municipios" style="padding-top:10px; padding-bottom:5px;">
                    {!! Form::select('municipios_id', [],  0, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipios_id', 'data-placeholder' => 'Todos los municipios', 'style' => 'width:100%'] ) !!}
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-12 clues" style="padding-top:10px; padding-bottom:5px;">
                    {!! Form::select('clues_id', [],  0, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clues_id', 'data-placeholder' => 'Todas la unidad de salud', 'style' => 'width:100%'] ) !!}
                  </div>
                </div>
                @role('root|admin|captura')
                <div class="row">
                  <div class="col-md-6 col-sm-12 col-xs-12" style="padding-top:10px; padding-bottom:5px;">
                    <div class="btn-group">
                      <button class="btn btn-info tab-coberturas" type="button"> <i class="fa fa-adjust btn-md" style="font-size:x-large;"></i> PORCENTAJE DE COBERTURAS</button>
                      <button class="btn btn-success tab-esquemas-completos" type="button"> <i class="fa fa-bullseye btn-md" style="font-size:x-large;"></i> ESQUEMAS COMPLETOS</button>
                      <button class="btn btn-warning tab-concordancia" type="button"> <i class="fa fa-star-half-empty btn-md" style="font-size:x-large;"></i> PORCENTAJE DE CONCORDANCIA</button>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12 col-xs-12" style="padding-top:10px; padding-bottom:5px;">
                  <h1 class="price pull-right">VACUNACIÓN</h1>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-sm-3 col-sm-12 col-xs-12 contenido-coberturas"> <!-- COBERTURAS -->                    
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('vacunas_id', [], 1, ['class' => 'form-control js-data-vacuna select2', 'style' => 'width:100%'])!!}
                    </div>
                    <div style="padding-top:10px; padding-bottom:5px;">
                    {!!Form::select('tipo_aplicacion', [], 0, ['class' => 'form-control js-data-tipo-aplicacion select2', 'style' => 'width:100%'])!!}
                    </div>
                    <div style="padding-top:10px; padding-bottom:5px;">
                    {!!Form::select('edad', [], 0, ['class' => 'form-control js-data-edad select2', 'style' => 'width:100%'])!!}
                    </div>
                    <button id="compose-coberturas" class="btn btn-large btn-info btn-block coberturas-button" type="button">BUSCAR</button>
                  </div>

                  <div class="col-sm-3 col-sm-12 col-xs-12 contenido-esquemas-completos hide"> <!-- ESQUEMAS COMPLETOS -->
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('edad_esquemas', [], 0, ['class' => 'form-control js-data-edad-esquema select2', 'style' => 'width:100%'])!!}
                    </div>
                    <button id="compose-esquemas esquemas-completos" class="btn btn-large btn-success btn-block esquemas-completos-button" type="button">BUSCAR</button>
                  </div>

                  <div class="col-sm-3 col-sm-12 col-xs-12 contenido-concordancia hide"> <!-- CONCORDANCIA -->
                    PARA OBTENER EL PORCENTAJE DE CONCORDANCIA DEL AÑO ACTUAL ES NECESARIO QUE LA POBLACIÓN CONAPO SEA AGREGADA.
                    <!-- <div style="padding-top:10px; padding-bottom:5px;">
                      {!! Form::select('anio', [], '', ['class' => 'form-control js-data-anio select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'anio',  'data-placeholder' => 'Población CONAPO', 'style' => 'width:100%'] ) !!}
                    </div> -->
                    <div class="product_price" style="text-align:center !important;">
                      <span class="price-tax">CONCORDANCIA</span>
                      <br>
                      <h1 class="price pc" style="text-decoration:none; cursor:pointer;"onClick="masMenosDetalles()"> </h1>
                   </div> 
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('edad_concordancia', [], 0, ['class' => 'form-control js-data-edad-concordancia select2', 'style' => 'width:100%'])!!}
                    </div>
                    <button id="compose-concordancia concordancia" class="btn btn-large btn-warning btn-block concordancia-button" type="button">BUSCAR</button>
                  </div>


                  <div class="col-sm-9 col-sm-12 col-xs-12 contenido-coberturas"> <!-- COBERTURAS -->   
                    <div class="row">  
                      <div class="product_price col-md-4 col-sm-12 col-xs-12" style="text-align:center !important;">
                        <span class="price-tax">Población Nominal</span>
                        <br>
                        <h1 class="price total-capturas"> </h1>
                        <i class="total-ninas" style="font-size:x-large; color:gray; padding:5px;"></i> 
                        <i class="fa fa-male" style="color:#4d81bf; font-size:xx-large;"></i> 
                        <i class="fa fa-female" style="color:#ed1586; font-size:xx-large;"></i>  
                        <i class="total-ninos" style="font-size:x-large; color:gray; padding:5px;"></i>
                      </div>             
                      <div class="product_price col-md-4 col-sm-12 col-xs-12" style="text-align:center !important;">
                        <span class="price-tax">Coberturas</span>
                        <br>
                        <h1 class="price total-coberturas"> </h1>  
                        <i class="total-coberturas-ninas" style="font-size:x-large; color:gray; padding:5px;"></i> 
                        <i class="fa fa-male" style="color:#4d81bf; font-size:xx-large;"></i> 
                        <i class="fa fa-female" style="color:#ed1586; font-size:xx-large;"></i>  
                        <i class="total-coberturas-ninos" style="font-size:x-large; color:gray; padding:5px;"></i>
                      </div>
                      <div class="product_price col-md-4 col-sm-12 col-xs-12" style="text-align:center !important;">
                        <span class="price-tax">Porcentaje de Coberturas</span>
                        <h1 class="price porcentaje-coberturas-nominal"> </h1>
                        <h1 class="price porcentaje-coberturas-oficial"> </h1>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-9 col-sm-12 col-xs-12 contenido-esquemas-completos hide"> <!-- ESQUEMAS COMPLETOS -->
                    <div class="row">
                      <div class="product_price col-md-4 col-sm-12 col-xs-12" style="text-align:center !important;">
                        <span class="price-tax">Población Nominal</span>
                        <br>
                        <h1 class="price total-nominal-esquemas-completos"> </h1>
                        <i class="total-nominal-esquemas-completos-ninas" style="font-size:x-large; color:gray; padding:5px;"></i> 
                        <i class="fa fa-male" style="color:#4d81bf; font-size:xx-large;"></i> 
                        <i class="fa fa-female" style="color:#ed1586; font-size:xx-large;"></i>  
                        <i class="total-nominal-esquemas-completos-ninos" style="font-size:x-large; color:gray; padding:5px;"></i>
                      </div> 
                      <div class="product_price col-md-4 col-sm-12 col-xs-12" style="text-align:center !important;">
                        <span class="price-tax">Total de esquemas</span>
                        <br>
                        <h1 class="price total-esquemas-completos"> </h1>
                        <i class="total-esquemas-completos-ninas" style="font-size:x-large; color:gray; padding:5px;"></i> 
                        <i class="fa fa-male" style="color:#4d81bf; font-size:xx-large;"></i> 
                        <i class="fa fa-female" style="color:#ed1586; font-size:xx-large;"></i>  
                        <i class="total-esquemas-completos-ninos" style="font-size:x-large; color:gray; padding:5px;"></i>
                      </div> 
                    </div>
                  </div>

                  <div class="col-sm-9 col-sm-12 col-xs-12 contenido-concordancia hide"> <!-- CONCORDANCIA -->
                    <!-- <div class="concordancia-detalles"> 
                      <a href="#tabla-concordancia" style="font-size:large;" class="mas-menos-detalles" onClick="masMenosDetalles()"> <i class="fa fa-chevron-circle-down"></i> Menos Detalles </a> 
                    </div> -->
                    <div class="tabla-concordancia"></div>
                  </div>


                </div>
                @endrole
                @role('root')
                  <hr>
                @endrole
                @role('root|red-frio')
                <div class="row">
                  <div class="col-md-12">                  
                    <h1 class="price pull-right">RED DE FRÍO</h1>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-9">
                    <div id="map" class="padre" style="width:100%; height:550px;"> 
                    <div> <span class="hijo"> <i class="fa fa-spin fa-spinner"></i> Cargando </span></div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <!-- <button id="compose-es" class="btn btn-sm btn-primary btn-block" type="button">FILTROS RED DE FRÍO</button> -->
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('estatus_contenedor_id', [], 0, ['class' => 'form-control js-data-estatus-contenedor select2', 'style' => 'width:100%'])!!}
                    </div>
                    <div style="padding-top:10px; padding-bottom:5px;">
                      {!!Form::select('tipo_contenedor_id', [], 0, ['class' => 'form-control js-data-tipo-contenedor select2', 'style' => 'width:100%'])!!}
                    </div>
                    <br>
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true"> </div>
                  </div>
                </div>
                @endrole

              </div>
            </div>
          </div>
          
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
    <!-- AIzaSyAcJqDlKB4Kk_FQExuMuEKQVmmYzy-dUMU -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcJqDlKB4Kk_FQExuMuEKQVmmYzy-dUMU&callback=ubicacionContenedores"
    async defer></script>
@endsection