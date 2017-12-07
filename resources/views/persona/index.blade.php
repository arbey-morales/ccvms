@extends('app')
@section('title')
    Censo nominal
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
    <div class="row">
        <div class="col-md-6">
            <!--
            @if($rep['act']==true)
                <a class="btn btn-warning btn-lg" href="#" onClick="descargarActividades()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-newspaper-o"></i> Actividades</a>
            @endif
            @if($rep['bio']==true)
                <a class="btn btn-info btn-lg" href="#" onClick="descargarBiologicos()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-flask"></i> Biológicos</a>
            @endif
            -->
        </div>
        <div class="col-md-6">
            @permission('create.personas')<a class="btn btn-default btn-lg pull-right" href="{{ route('persona.create') }}" role="button">Agregar Persona</a>@endpermission
        </div>
    </div>
    <div class="clearfix"></div>

    @include('errors.msgAll')
        
            <div class="x_panel">
                <div class="x_content">


                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" click="buscar()" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Buscar</a>
                        </li>
                        <li role="presentation" click="seguimiento()" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Seguimiento</a>
                        </li>
                        <li role="presentation" click="actividad()" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Actividad</a>
                        </li>
                        <!--<li role="presentation" click="biologico()" class=""><a href="#tab_content4" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">Biológico</a>
                        </li>-->
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                            {!! Form::open(['id' => 'form']) !!}
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12 tile_stats_count filtro-texto">
                                        <span class="count_top"><i class="fa fa-male"></i> Nombre del infante/tutor o CURP</span>
                                        <div class="row">
                                            <div class="col-md-12"> 
                                            {!! Form::text('q', '', ['class' => 'form-control', 'id' => 'q', 'autocomplete' => 'off', 'placeholder' => 'MOTC880220...' ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 tile_stats_count">
                                        <span class="count_top"><i class="fa fa-magic"></i> Todo, sin filtros</span>
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::checkbox('todo', '1', false, ['class' => 'js-switch', 'id' => 'todo'] ) !!} 
                                                {!! Form::label('todo-todo', ' ', ['for' => 'todo', 'style' => 'font-size:large; padding-right:10px;'] ) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6 no-resultados"> </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-success btn-lg js-ajax-buscar"> <i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                            {!! Form::open(['id' => 'form-seguimiento']) !!}
                                <div class="row tile_count">
                                    <div class="col-md-12 col-sm-12 col-xs-12 tile_stats_count filtro">
                                        <span class="count_top"><i class="fa fa-filter"></i> Filtros</span><br>
                                        @role('root|admin')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!!Form::select('jurisdicciones_id', [], 0, ['class' => 'form-control js-data-jurisdiccion select2', 'style' => 'width:100%'])!!}
                                                </div>
                                            </div>
                                            <br>
                                        @endrole
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!!Form::hidden('filtro', 1, ['class' => 'form-control', 'id' => 'filtro', 'autocomplete' => 'off' ]) !!}
                                                {!!Form::select('municipios_id', [], 0, ['class' => 'form-control js-data-municipio select2', 'style' => 'width:100%'])!!}
                                            </div>
                                            <div class="col-md-6">
                                                {!!Form::select('clues_id', [], 0, ['class' => 'form-control js-data-clue select2', 'style' => 'width:100%'])!!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!!Form::select('localidades_id', [], 0, ['class' => 'form-control js-data-localidad select2', 'style' => 'width:100%'])!!}
                                            </div>
                                            <div class="col-md-6">
                                                {!!Form::select('colonias_id', [], 0, ['class' => 'form-control js-data-colonia select2', 'style' => 'width:100%'])!!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!!Form::select('agebs_id', [], 0, ['class' => 'form-control js-data-ageb select2', 'style' => 'width:100%'])!!}
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::text('manzana', '', ['class' => 'form-control', 'id' => 'manzana', 'autocomplete' => 'off', 'placeholder' => '# manzana' ]) !!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::text('sector', '', ['class' => 'form-control', 'id' => 'sector', 'autocomplete' => 'off', 'placeholder' => '# Sector' ]) !!}
                                            </div>
                                            <div class="col-md-6" id="tipos-biologicos">
                                                {!!Form::select('vacunas_id', [], 0, ['class' => 'form-control js-data-biologico select2', 'style' => 'width:100%'])!!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6 no-resultados"> </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" class="btn btn-success btn-lg js-ajax-seguimiento"> <i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                            {!! Form::open(['id' => 'form-actividad']) !!}
                                <div class="row tile_count">
                                    <div class="col-md-12 col-sm-12 col-xs-12 tile_stats_count filtro">
                                        <span class="count_top"><i class="fa fa-filter"></i> Filtros</span><br>
                                        @role('root|admin')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!!Form::select('jurisdicciones_id', [], 0, ['class' => 'form-control js-data-jurisdiccion select2', 'style' => 'width:100%'])!!}
                                                </div>
                                            </div>
                                            <br>
                                        @endrole
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!!Form::hidden('filtro', 1, ['class' => 'form-control', 'id' => 'filtro', 'autocomplete' => 'off' ]) !!}
                                                {!!Form::select('municipios_id', [], 0, ['class' => 'form-control js-data-municipio select2', 'style' => 'width:100%'])!!}
                                            </div>
                                            <div class="col-md-6">
                                                {!!Form::select('clues_id', [], 0, ['class' => 'form-control js-data-clue select2', 'style' => 'width:100%'])!!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!!Form::select('localidades_id', [], 0, ['class' => 'form-control js-data-localidad select2', 'style' => 'width:100%'])!!}
                                            </div>
                                            <div class="col-md-6">
                                                {!!Form::select('colonias_id', [], 0, ['class' => 'form-control js-data-colonia select2', 'style' => 'width:100%'])!!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!!Form::select('agebs_id', [], 0, ['class' => 'form-control js-data-ageb select2', 'style' => 'width:100%'])!!}
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::text('manzana', '', ['class' => 'form-control', 'id' => 'manzana', 'autocomplete' => 'off', 'placeholder' => '# manzana' ]) !!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::text('sector', '', ['class' => 'form-control', 'id' => 'sector', 'autocomplete' => 'off', 'placeholder' => '# Sector' ]) !!}
                                            </div>
                                            <!--<div class="col-md-6" id="tipos-biologicos">
                                                {!!Form::select('vacunas_id', [], 0, ['class' => 'form-control js-data-biologico select2', 'style' => 'width:100%'])!!}
                                            </div>-->
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6 no-resultados"> </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" class="btn btn-success btn-lg js-ajax-actividad"> <i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                        4
                        </div>
                      </div>
                    </div>
                </div>
            </div>
    
    <div class="x_panel">
        <div class="x_content" id="contenido">
             
        </div>
        <br>
    </div>
    <!-- Modal delete -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header alert-danger">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title" id="myModalLabel"> <i class="fa fa-question" style="padding-right:15px;"></i>  Confirmación </h3>
            </div>
            <div class="modal-body">
                <h3>Seguro que quiere eliminar lo datos de <span id="modal-text" class="text text-danger"></span>?</h3>
                <h4>Además borrará todo registro de aplicaciones realizadas. Si esta de acuerdo presione "Sí, eliminar".</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger btn-lg btn-confirm-delete" data-dismiss="modal">Sí, eliminar</button>
            </div>

            </div>
        </div>
    </div>
    {!! Form::open(['route' => ['persona.destroy', ':ITEM_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) !!}
    {!! Form::close() !!}
@endsection
@section('my_scripts')
    <!-- Datatables -->
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.js') !!}
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/dataTables.responsive.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/responsive.bootstrap.js') !!}
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- Pdfmake -->
    {!! Html::script('assets/vendors/pdfmake/build/pdfmake.min.js') !!}
    {!! Html::script('assets/vendors/pdfmake/build/vfs_fonts.js') !!}
    <!-- Mine -->
    {!! Html::script('assets/mine/js/images.js') !!}
    {!! Html::script('assets/mine/js/personaIndex.js') !!}

@endsection