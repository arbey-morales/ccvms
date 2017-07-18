@extends('app')
@section('title')
   Censo nominal
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

    <style>
        #paterno,#materno,#nombre,#curp,#tutor {
            text-transform:uppercase
        }
        .select-label{
            pointer-events: none;
            position: absolute;
            opacity: 1;
            top: 0;
            -webkit-transform: translateY(15%);
                    transform: translateY(15%);
            z-index: 2;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding-left: 6px;
            color: #52a6e1;
            -webkit-transition: opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), -webkit-transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
            transition: opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), -webkit-transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
            transition: transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
            transition: transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), opacity 0.3s cubic-bezier(0.215, 0.61, 0.355, 1), -webkit-transform 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
        }
    </style>
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-group"></i> Censo nominal <i class="fa fa-angle-right text-danger"></i><small> Nuevo</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('persona.index') }}">
                        <i class="fa fa-long-arrow-left"></i>
                    </a>
                </li>
                <li>
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('errors.msgAll') <!-- Mensages -->
            {!! Form::open([ 'route' => 'persona.store', 'id' => 'personas-form', 'method' => 'POST', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               
               <div class="bt-form__wrapper">
                    <div class="bt-flabels__wrapper">
                        <span class="select-label">* Unidad de salud</span>
                        {!! Form::label('clue_id', '* Unidad de salud', ['for' => 'clue_id'] ) !!}
                        {!! Form::select('clue_id', $clues,  1, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clue_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                        <span class="bt-flabels__error-desc">Requerido</span>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('paterno', '* Apellido Paterno', ['for' => 'paterno'] ) !!}
                                {!! Form::text('paterno', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 30]', 'id' => 'paterno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Paterno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 30 caracteres</span>                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('materno', '* Apellido Materno', ['for' => 'materno'] ) !!}
                                {!! Form::text('materno', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 30]', 'id' => 'materno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Materno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 30 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('nombre', '* Nombre(s)', ['for' => 'nombre'] ) !!}
                                {!! Form::text('nombre', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[3, 50]', 'id' => 'nombre', 'autocomplete' => 'off', 'placeholder' => '* Nombre(s)' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 50 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_nacimiento', '* Fecha de nacimiento', ['for' => 'fecha_nacimiento'] ) !!}
                                {!! Form::text('fecha_nacimiento', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'fecha_nacimiento', 'autocomplete' => 'off', 'placeholder' => '* Fecha de nacimiento' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Género</span>
                                {!! Form::label('genero', '* Género', ['for' => 'genero'] ) !!}
                                {!! Form::select('genero', ['F' => 'F - Femenino', 'M' => 'M - Masculino'],  'F', ['class' => 'form-control js-data-genero select2', 'data-parsley-required' => 'true', 'id' => 'genero',  'data-placeholder' => '* Género', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">* Entidad federativa de nacimiento</span>                              
                                {!! Form::label('entidad_federativa_nacimiento_id', '* Entidad federativa de nacimiento', ['for' => 'entidad_federativa_nacimiento_id'] ) !!}
                                {!! Form::select('entidad_federativa_nacimiento_id', $estados,  $clue_selected->entidades_id, ['class' => 'form-control js-data-estado select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'entidad_federativa_nacimiento_id',  'data-placeholder' => '* Entidad federativa de nacimiento', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('curp', '* CURP', ['for' => 'curp'] ) !!}
                                {!! Form::text('curp', null , ['class' => 'form-control', 'style' => 'font-size:x-large; color:tomato;', 'data-parsley-required' => 'true', 'id' => 'curp', 'autocomplete' => 'off', 'placeholder' => '* CURP' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 17 - Máx: 18 Caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                <span class="select-label">* Tipo de parto</span>
                                {!! Form::label('tipo_parto_id', '* Tipo de parto', ['for' => 'tipo_parto_id'] ) !!}
                                {!! Form::select('tipo_parto_id', $partos,  1, ['class' => 'form-control js-data-parto select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'tipo_parto_id',  'data-placeholder' => '* Tipo de parto', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('tutor', '* Nombre del tutor', ['for' => 'tutor'] ) !!}
                                {!! Form::text('tutor', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[10, 100]', 'id' => 'tutor', 'autocomplete' => 'off', 'placeholder' => '* Nombre del tutor' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 10 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_nacimiento_tutor', '* Fecha de nacimiento tutor', ['for' => 'fecha_nacimiento_tutor'] ) !!}
                                {!! Form::text('fecha_nacimiento_tutor', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'fecha_nacimiento_tutor', 'autocomplete' => 'off', 'placeholder' => '* Fecha de nacimiento tutor' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">* Municipio</span>
                                {!! Form::label('municipio_id', '* Municipio', ['for' => 'municipio_id'] ) !!}
                                {!! Form::select('municipio_id', $municipios,  $clue_selected->municipios_id, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipio_id',  'data-placeholder' => '* Municipio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">   
                                <span class="select-label">* Localidad</span>
                                {!! Form::label('localidad_id', '* Localidad', ['for' => 'localidad_id'] ) !!}
                                {!! Form::select('localidad_id', $localidades, $clue_selected->localidades_id, ['class' => 'form-control js-data-localidad select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'localidad_id',  'data-placeholder' => '* Localidad', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                <span class="select-label">AGEB</span>
                                {!! Form::label('ageb_id', 'AGEB', ['for' => 'ageb_id'] ) !!}
                                {!! Form::select('ageb_id', $agebs, 0, ['class' => 'form-control js-data-ageb select2', 'data-parsley-type' => 'number', 'id' => 'ageb_id', 'data-placeholder' => 'Ageb', 'style' => 'width:100%'] ) !!}
                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('sector', 'Sector', ['for' => 'sector'] ) !!}
                                {!! Form::text('sector', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 3]', 'id' => 'sector', 'autocomplete' => 'off', 'placeholder' => 'Sector' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 3 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">   
                                {!! Form::label('manzana', 'Manzana', ['for' => 'manzana'] ) !!}
                                {!! Form::text('manzana', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 3]', 'id' => 'manzana', 'autocomplete' => 'off', 'placeholder' => 'Manzana' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 3 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('descripcion_domicilio', 'Descripción domicilio', ['for' => 'descripcion_domicilio'] ) !!}
                                {!! Form::text('descripcion_domicilio', null , ['class' => 'form-control', 'id' => 'descripcion_domicilio', 'autocomplete' => 'off', 'placeholder' => 'Descripción domicilio' ]  ) !!}
                                <!--<span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>-->
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('calle', '* Calle', ['for' => 'calle'] ) !!}
                                {!! Form::text('calle', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 100]', 'data-parsley-required' => 'true', 'id' => 'calle', 'autocomplete' => 'off', 'placeholder' => '* Calle' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 1 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('numero', '* No.', ['for' => 'numero'] ) !!}
                                {!! Form::text('numero', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 5]', 'data-parsley-required' => 'true', 'id' => 'numero', 'autocomplete' => 'off', 'placeholder' => '* No.' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido /    Mín: 1 - Máx: 5 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('colonia', 'Colonia', ['for' => 'colonia'] ) !!}
                                {!! Form::text('colonia', null , ['class' => 'form-control', 'id' => 'colonia', 'autocomplete' => 'off', 'placeholder' => 'Colonia' ]  ) !!}
                                <!--<span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>-->
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right"> 
                                {!! Form::label('codigo_postal', 'Código Postal.', ['for' => 'codigo_postal'] ) !!}
                                {!! Form::text('codigo_postal', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 5]', 'id' => 'codigo_postal', 'autocomplete' => 'off', 'placeholder' => 'Código Postal' ]  ) !!}
                                <span class="bt-flabels__error-desc">Mín: 1 - Máx: 5 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">   
                                <span class="select-label">Afiliación</span>
                                {!! Form::label('institucion_id', 'Afiliación', ['for' => 'institucion_id'] ) !!}
                                {!! Form::select('institucion_id', $instituciones,  0, ['class' => 'form-control js-data-institucion select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'institucion_id',  'data-placeholder' => '* Afilación', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">  
                                <span class="select-label">Código</span>                                                         
                                {!! Form::label('codigo_id', 'Código', ['for' => 'codigo_id'] ) !!}
                                {!! Form::select('codigo_id', $codigos, 0, ['class' => 'form-control js-data-codigo select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'codigo_id',  'data-placeholder' => '* Código', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                </div>                
                
                @if(count($vacunas_esquemas)>0)
                    <div class="x_panel">
                        <div class="x_title">
                            <h2 id="title-esquema"><i class="fa fa-calendar text-success"></i> @if(count($esquema)>0) {{ $esquema->descripcion }} @endif </h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" id="content-esquema">
                            @if(count($vacunas_esquemas)>0)
                                @foreach($vacunas_esquemas as $key=>$ve)
                                    <?php $key_plus = $key; $key_plus = $key_plus + 1; $col_md = 12; $plu_col_md = 0; ?>
                                    @if(count($vacunas_esquemas) - 1 > $key)
                                        @foreach ($vacunas_esquemas as $k => $v)
                                            @if($ve->fila==$v->fila)
                                                <?php $plu_col_md++; ?>
                                            @endif 
                                        @endforeach 
                                        <?php $col_md = round(12 / $plu_col_md); ?>                                 
                                        <div class="animated flipInY col-md-{{$col_md}} col-xs-12"><br>
                                            <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #{{$ve->color_rgb}}; background-color:#{{$ve->color_rgb}} !important;">
                                                <div class="row">
                                                    <div class="col-md-12"> <span style="font-size:large;font-weight:bold;"> {{$ve->clave}} <small> @if($ve->tipo_aplicacion==1) Única @endif @if($ve->tipo_aplicacion==2) 1a Dosis @endif @if($ve->tipo_aplicacion==3) 2a Dosis @endif @if($ve->tipo_aplicacion==4) 3a Dosis @endif @if($ve->tipo_aplicacion==5) 4a Dosis @endif @if($ve->tipo_aplicacion==6) Refuerzo @endif  </small> </span> <span style="font-size:large;" class="pull-right"> @if($ve->intervalo_inicio<=29) Nacimiento @else  @if(($ve->intervalo_inicio/30)<=23){{($ve->intervalo_inicio/30)}} Meses @else {{round((($ve->intervalo_inicio/30)/12))}} Años @endif @endif  </span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="bt-flabels__wrapper">
                                                        {!! Form::label('fecha_aplicacion'.$ve->id, 'Fecha de aplicación', ['for' => 'fecha_aplicacion'.$ve->id] ) !!}
                                                        {!! Form::text('fecha_aplicacion'.$ve->id, null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'fecha_aplicacion'.$ve->id, 'autocomplete' => 'off', 'placeholder' => 'Fecha de aplicación' ]  ) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($vacunas_esquemas[$key_plus]->fila != $ve->fila)
                                            <div class="clearfix"></div>
                                        @endif
                                    @endif
                                @endforeach
                            @else
                                <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin esquema</h3></div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin esquema</h3></div>
                @endif

                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-eraser"></i> Limpiar</button>
                    @permission('create.personas')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar</button>@endpermission
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('my_scripts')
    <!-- Select2 -->
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/select2.min.js') !!}
    {!! Html::script('assets/vendors/select2-4.0.3/dist/js/i18n/es.js') !!}
    <!-- jQuery Tags Input -->
    {!! Html::script('assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js') !!}
    <!-- bootstrap-daterangepicker -->
    {!! Html::script('assets/app/js/moment/moment.min.js') !!}
    {!! Html::script('assets/app/js/datepicker/daterangepicker.js') !!}
    <!-- Bootstrap Colorpicker -->
    {!! Html::script('assets/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- File Input -->
    {!! Html::script('assets/mine/js/bootstrap.file-input.js') !!}
    <!-- Form Mine -->
    {!! Html::script('assets/mine/js/parsleyjs/2.1.2/parsley.min.js') !!}
    {!! Html::script('assets/mine/js/floating-labels.js') !!}
    {!! Html::script('assets/mine/js/myCheckBox.js') !!}
    {!! Html::script('assets/mine/js/myMessage.js') !!}
    {!! Html::script('assets/mine/js/myfileImage.js') !!}
    {!! Html::script('assets/mine/js/myTags.js') !!}
    {!! Html::script('assets/mine/js/myPicker.js') !!}
    {!! Html::script('assets/mine/js/mx_CURP_RFC.js') !!}

    <!-- Select2 personalizado -->
    <script>
        var estados_equivalencia = ["X","AS","BC","BS","CC","CL","CM","CS","CH","DF","DG","GT","GR","HG","JC","MC","MN","MS","NT","NL","OC","PL","QT","QR","SP","SL","SR","TC","TS","TL","VZ","YN","ZS"];
        var localidad = { 'id':null, 'nombre':'Localidad'};
        $(".js-data-clue,.js-data-ageb,.js-data-genero,.js-data-parto,.js-data-estado,.js-data-municipio,.js-data-codigo,.js-data-institucion,.js-data-localidad").select2();
        
        $(".js-data-estado,.js-data-genero").change(function(){
            setTimeout(function(){ validate_inputs_curp(); }, 1000);
        });
        
        $("input[name*='fecha_aplicacion']").on("blur", function(){
            valoraFecha($(this).val(), replaceAll($(this).attr('name'),"_", " "));
        });
        $("#fecha_nacimiento,#fecha_nacimiento_tutor,input[name*='fecha_aplicacion']").blur(function(){
            valoraFecha($(this).val(), replaceAll($(this).attr('name'),"_", " "));             
        });
        

        $(".js-data-clue").change(function(){
            var clue_id = $(this).val();
            $.get('../catalogo/clue/'+clue_id, function(response, status){ // Consulta CURP
                $(".js-data-estado").val(response.data.entidades_id).trigger("change");
                $(".js-data-municipio").val(response.data.municipios_id).trigger("change");
                $(".js-data-localidad").val(response.data.localidades_id).trigger("change");
            }).fail(function(){  // Calcula CURP                    
                new PNotify({
                    title: 'Info!',
                    text: 'No se consultó detalles de la unidad de salud',
                    type: 'warning',
                    styling: 'bootstrap3'
                });
            });
        });

        $("#fecha_nacimiento,#paterno,#materno,#nombre").blur(function(){            
            setTimeout(function(){ validate_inputs_curp(); }, 1000);
        });

        function valoraFecha(date,name){
            if(date!=null && date!="") {
                var fn_validar = replaceAll(date,"-", "/");                
                var errors = 0;
                var warning = '';

                if(validarFormatoFecha(fn_validar)){ 
                    if(!existeFecha(fn_validar)){
                        errors++; warning+= "La fecha que acaba agregar no existe. \n";
                    }
                }else{
                    errors++; warning+= "El formato de la fecha que acaba agregar es incorrecto. \n";
                }
                if(errors>0){
                    new PNotify({
                        title: 'Info!',
                        text: name+': '+warning,
                        type: 'info',
                        styling: 'bootstrap3'
                    });
                } 
            }
        }

        function get_esquema(esquema) {
            $('#title-esquema').empty().html('Buscando esquema '+esquema);
            $('#content-esquema').empty().html('<div class="col-md-12 text-center"> <i class="fa fa-circle-o-notch fa-spin" style="font-size:x-large;"></i> </div> ');
            $.get('../catalogo/esquema/'+esquema, {}, function(response, status){ // Consulta esquema
                if(response.data==null){
                    new PNotify({
                        title: 'Oye!',
                        text: 'No se encontró el esquema que buscas',
                        type: 'warning',
                        styling: 'bootstrap3'
                    });
                    $('#title-esquema').empty().html('No se encuentra el esquema: '+esquema+'. ');
                    $('#content-esquema').empty().html('<div class="col-md-12 text-center text-danger"> <h2 class="text-danger"> <i class="fa fa-info-circle text-info"></i> Imposible encontrar el esquema '+esquema+'. Seleccione otra fecha de nacimiento o asegurese que exista el esquema que busca. </h2></div> ');
                } else {
                    $('#title-esquema').empty().html('<i class="fa fa-calendar text-success"></i> '+response.esquema.descripcion);
                    $('#content-esquema').empty();
                    var vacunas_esquemas = response.data;
                    var key_plus = 0;
                    $.each(vacunas_esquemas, function( key, ve ) {
                        key_plus++;
                        var col_md = 12;
                        var plu_col_md = 0;
                        $.each(vacunas_esquemas,  function( k, v ){
                            if(ve.fila==v.fila){
                                plu_col_md++;
                            } 
                        });
                        col_md = 12 / plu_col_md; // numero de columnas por fila
                        var tipo_aplicacion = '';
                        var intervalo_inicio = '';
                        if(ve.tipo_aplicacion==1) {
                            tipo_aplicacion = 'Dosis única';
                        } 
                        if(ve.tipo_aplicacion==2) {
                            tipo_aplicacion = '1a Dosis';
                        } 
                        if(ve.tipo_aplicacion==3) {
                            tipo_aplicacion = '2a Dosis';
                        }
                        if(ve.tipo_aplicacion==4){ 
                            tipo_aplicacion = '3a Dosis'; 
                        }
                        if(ve.tipo_aplicacion==5){ 
                            tipo_aplicacion = '4a Dosis'; 
                        }
                        if(ve.tipo_aplicacion==6) {
                            tipo_aplicacion = 'Refuerzo';
                        }

                        if(ve.intervalo_inicio<=29) {
                            intervalo_inicio = 'Nacimiento'; 
                        } else {
                            if((ve.intervalo_inicio/30)<=23) { 
                                intervalo_inicio = Math.round((ve.intervalo_inicio/30))+' Meses';
                            } else { 
                                intervalo_inicio = Math.round(((ve.intervalo_inicio/30)/12))+' Años';
                            }
                        }
                        if(vacunas_esquemas.length - 1 > key){
                            $('#content-esquema').append('<div class="animated flipInY col-md-'+Math.round(col_md)+' col-xs-12"><br> <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #'+ve.color_rgb+'; background-color:#'+ve.color_rgb+' !important;"> <div class="row"> <div class="col-md-12"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipo_aplicacion+' </small> </span> <span style="font-size:large;" class="pull-right"> '+intervalo_inicio+' </span> </div> </div> <div class="row"> <div class="bt-flabels__wrapper"> <input id="fecha_aplicacion'+ve.id+'" name="fecha_aplicacion'+ve.id+'" type="text" value="" class="form-control has-feedback-left" aria-describedby="inputSuccess2Status" autocomplete="off" placeholder="Fecha de aplicación"> </div> </div> </div> </div>');
                            if(vacunas_esquemas[key_plus].fila != ve.fila){
                                $('#content-esquema').append('<div class="clearfix"></div>');
                            }
                        }
                    });

                    setTimeout(function() {
                        init_fecha_aplicacion();
                    }, 500);
                }
            }).fail(function(){                   
                $('#title-esquema').empty().html('No se encuentra el esquema: '+esquema+'. ');
                $('#content-esquema').empty().html('<div class="col-md-12 text-center text-danger"> <h2 class="text-danger"> <i class="fa fa-info-circle text-info"></i> Imposible encontrar el esquema '+esquema+'. Seleccione otra fecha de nacimiento o asegurese que exista el esquema que busca. </h2></div> ');
            });
        }

        function validate_inputs_curp(){
            var estado = $(".js-data-estado").val();
            var born_state =  estados_equivalencia[estado];
            if($(".js-data-genero").val()=="M") {
                var gender = 1;
            }
            if($(".js-data-genero").val()=="F") {
                var gender = 2;
            }
            var fn_validar = replaceAll($('#fecha_nacimiento').val(),"-", "/");
            var born_date = fn_validar.split('/');
            var name = $('#nombre').val();
            var father_surname = $('#paterno').val();
            var mother_surname = $('#materno').val();
            var errors = 0;
            var warning = '';
            
            if(validarFormatoFecha(fn_validar)){ 
                if(!existeFecha(fn_validar)){
                        errors++; warning+= "La fecha que introdujo no existe. \n";
                }
            }else{
                errors++; warning+= "El formato de la fecha es incorrecto. \n";
            }

            if(gender=="" || gender==null){
                errors++; warning+= "El género debe ser Masculino o Femenino. \n";
            }

            if(name.length<2){
                errors++; warning+= "Longitud de nombre no válida. \n";
            }

            if(father_surname.length<2){
                errors++; warning+= "Longitud de apellido paterno no válida. \n";
            }

            if(mother_surname.length<2){
                errors++; warning+= "Longitud de apellido materno no válida. \n";
            }

            if(errors==0) {
                // Sending form
                var form = $("#personas-form");
                var data = form.serialize();
                $.post('curp ', data, function(response, status){ // Consulta CURP
                    if(response.find==true){                        
                        $("#curp").val(response.curp);
                        new PNotify({
                            title: 'Info!',
                            text: 'Se encontró la CURP, asegurese que sea correcta',
                            type: 'info',
                            styling: 'bootstrap3'
                        });
                    }
                    if(response.find==false || response.curp==""){ 
                        calcula_curp(name, father_surname, mother_surname, born_date[0], born_date[1], born_date[2], born_state, gender);
                    }
                }).fail(function(){  // Calcula CURP                    
                    calcula_curp(name, father_surname, mother_surname, born_date[0], born_date[1], born_date[2], born_state, gender);
                });

            } else {
                if(errors<2){
                    new PNotify({
                        title: 'Oh No!',
                        text: warning,
                        type: 'info',
                        styling: 'bootstrap3'
                    });
                }
            }
        }

        function calcula_curp(name, father_surname, mother_surname, born_date_d, born_date_m, born_date_a, born_state, gender){
            var  curp = mxk.getCURP(name, father_surname, mother_surname, born_date_d, born_date_m, born_date_a, born_state, gender);
            $("#curp").val(curp);
            new PNotify({
                title: 'Oh No!',
                text: 'Se calculó la CURP, verifique los datos',
                type: 'warning',
                styling: 'bootstrap3'
            });
        }

        function validarFormatoFecha(campo) {
            var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
            if ((campo.match(RegExPattern)) && (campo!='')) {
                    return true;
            } else {
                    return false;
            }
        }

        function existeFecha(fecha){
            var fechaf = fecha.split("/");
            var d = fechaf[0];
            var m = fechaf[1];
            var y = fechaf[2];
            return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
        }

        function replaceAll(str, find, replace) {
            return str.replace(new RegExp(find, 'g'), replace);
        }

       
    </script>
@endsection