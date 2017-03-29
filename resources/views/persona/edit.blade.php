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
        #paterno,#materno,#nombre,#curp,#tutor {text-transform:uppercase};
    </style>
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-group"></i> Censo Nominal: {{ $persona->nombre  }} {{ $persona->apellido_paterno  }} {{ $persona->apellido_materno  }} <i class="fa fa-angle-right text-danger"></i><small> Editar</small></h2>
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
            <?php
                $fecha_nac = explode("-", trim($persona->fecha_nacimiento)); 
                $persona->fecha_nacimiento = $fecha_nac[2].'-'.$fecha_nac[1].'-'.$fecha_nac[0];
            ?>
            {!! Form::model($persona, ['route' => ['persona.update', $persona], 'method' => 'PUT', 'files' => 'true', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => '']) !!}

                <div class="bt-form__wrapper">
                    <div class="bt-flabels__wrapper">
                        {!! Form::label('clues_id', '* Unidad de salud', ['for' => 'clues_id'] ) !!}
                        {!! Form::select('clues_id', $clues,  $persona->clues_id, ['class' => 'form-control js-data-clue select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'clues_id', 'data-placeholder' => '* Unidad de salud', 'style' => 'width:100%'] ) !!}
                        <span class="bt-flabels__error-desc">Requerido</span>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('paterno', '* Apellido Paterno', ['for' => 'paterno'] ) !!}
                                {!! Form::text('paterno', $persona->apellido_paterno, ['class' => 'form-control', 'data-parsley-required' => 'true', 'readonly' => 'on', 'data-parsley-length' => '[3, 30]', 'id' => 'paterno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Paterno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 30 caracteres</span>                                
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('materno', '* Apellido Materno', ['for' => 'materno'] ) !!}
                                {!! Form::text('materno', $persona->apellido_materno, ['class' => 'form-control', 'data-parsley-required' => 'true', 'readonly' => 'on', 'data-parsley-length' => '[3, 30]', 'id' => 'materno', 'autocomplete' => 'off', 'placeholder' => '* Apellido Materno' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 30 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('nombre', '* Nombre(s)', ['for' => 'nombre'] ) !!}
                                {!! Form::text('nombre', $persona->nombre, ['class' => 'form-control', 'data-parsley-required' => 'true', 'readonly' => 'on', 'data-parsley-length' => '[3, 50]', 'id' => 'nombre', 'autocomplete' => 'off', 'placeholder' => '* Nombre(s)' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 50 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha_nacimiento', '* Fecha de nacimiento', ['for' => 'fecha_nacimiento'] ) !!}
                                {!! Form::text('fecha_nacimiento', $persona->fecha_nacimiento, ['class' => 'form-control has-feedback-left',  'data-parsley-required' => 'true', 'readonly' => 'on', 'id' => 'fecha_nacimientos', 'autocomplete' => 'off', 'placeholder' => '* Fecha de nacimiento' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('genero', '* Género', ['for' => 'genero'] ) !!}
                                {!! Form::select('genero', ['F' => 'F - Femenino', 'M' => 'M - Masculino'],  $persona->genero, ['class' => 'form-control js-data-genero select2', 'data-parsley-required' => 'true', 'id' => 'genero',  'data-placeholder' => '* Género', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">                                
                                {!! Form::label('entidad_federativa_nacimiento_id', '* Entidad federativa de nacimiento', ['for' => 'entidad_federativa_nacimiento_id'] ) !!}
                                {!! Form::select('entidad_federativa_nacimiento_id', $estados,  $persona->entidad_federativa_nacimiento_id, ['class' => 'form-control js-data-estado select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'entidad_federativa_nacimiento_id',  'data-placeholder' => '* Entidad federativa de nacimiento', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('curp', 'CURP', ['for' => 'curp'] ) !!}
                                {!! Form::text('curp', $persona->curp, ['class' => 'form-control', 'style' => 'font-size:x-large; color:tomato;', 'data-parsley-required' => 'true', 'readonly' => 'on', 'id' => 'curp', 'autocomplete' => 'off', 'placeholder' => 'CURP' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 17 - Máx: 18 Caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('tipos_parto_id', 'Tipo de parto', ['for' => 'tipos_parto_id'] ) !!}
                                {!! Form::select('tipos_parto_id', $partos,  $persona->tipos_parto_id, ['class' => 'form-control js-data-parto select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'tipos_parto_id',  'data-placeholder' => 'Tipo de parto', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="bt-flabels__wrapper">
                        {!! Form::label('tutor', '* Nombre  completo del tutor', ['for' => 'tutor'] ) !!}
                        {!! Form::text('tutor', null , ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[10, 100]', 'id' => 'tutor', 'autocomplete' => 'off', 'placeholder' => '* Nombre  completo del tutor' ]  ) !!}
                        <span class="bt-flabels__error-desc">Requerido / Mín: 10 - Máx: 100 caracteres</span>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('municipios_id', '* Municipio', ['for' => 'municipios_id'] ) !!}
                                {!! Form::select('municipios_id', $municipios,  $persona->idMunicipio, ['class' => 'form-control js-data-municipio select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'municipios_id',  'data-placeholder' => '* Municipio', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">   
                                {!! Form::label('localidades_id', '* Localidad', ['for' => 'localidades_id'] ) !!}
                                {!! Form::select('localidades_id', $localidades, $persona->idLocalidad, ['class' => 'form-control js-data-localidad select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'localidades_id',  'data-placeholder' => '* Localidad', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('agebs_id', 'AGEB', ['for' => 'agebs_id'] ) !!}
                                {!! Form::select('agebs_id', $agebs, $persona->agebs_id, ['class' => 'form-control js-data-ageb select2', 'data-parsley-type' => 'number', 'id' => 'agebs_id', 'data-placeholder' => 'Ageb', 'style' => 'width:100%'] ) !!}
                                
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
                                {!! Form::text('descripcion_domicilio', null , ['class' => 'form-control', 'data-parsley-length' => '[3, 100]',  'data-parsley-required' => 'true', 'id' => 'descripcion_domicilio', 'autocomplete' => 'off', 'placeholder' => 'Descripción domicilio' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('calle', 'Calle', ['for' => 'calle'] ) !!}
                                {!! Form::text('calle', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 100]', 'data-parsley-required' => 'true', 'id' => 'calle', 'autocomplete' => 'off', 'placeholder' => 'Calle' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 1 - Máx: 100 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('numero', 'No.', ['for' => 'numero'] ) !!}
                                {!! Form::text('numero', null , ['class' => 'form-control', 'data-parsley-length' => '[1, 5]', 'data-parsley-required' => 'true', 'id' => 'numero', 'autocomplete' => 'off', 'placeholder' => 'No.' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido /    Mín: 1 - Máx: 5 caracteres</span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('colonia', 'Colonia', ['for' => 'colonia'] ) !!}
                                {!! Form::text('colonia', null , ['class' => 'form-control', 'data-parsley-length' => '[3, 100]', 'data-parsley-required' => 'true', 'id' => 'colonia', 'autocomplete' => 'off', 'placeholder' => 'Colonia' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 3 - Máx: 100 caracteres</span>
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
                                {!! Form::label('instituciones_id', 'Afiliación', ['for' => 'instituciones_id'] ) !!}
                                {!! Form::select('instituciones_id', $instituciones,  $persona->instituciones_id, ['class' => 'form-control js-data-institucion select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'instituciones_id',  'data-placeholder' => '* Afilación', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">                                                           
                                {!! Form::label('codigos_id', 'Código', ['for' => 'codigos_id'] ) !!}
                                {!! Form::select('codigos_id', $codigos, $persona->codigos_id, ['class' => 'form-control js-data-codigo select2', 'data-parsley-type' => 'number', 'data-parsley-min' => '0', 'id' => 'codigos_id',  'data-placeholder' => '* Código', 'style' => 'width:100%'] ) !!}
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                </div>                
                
                <div class="uk-text-center uk-margin-top pull-right">
                    <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-eraser"></i> Limpiar</button>
                    @permission('update.personas')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar Cambios</button>@endpermission
                </div>

                @if(count($vacunas_esquemas)>0)
                  <div class="x_panel">
                    <div class="x_title">
                        <h2 id="title-esquema"><i class="fa fa-calendar text-success"></i> {{ $esquema->descripcion }} </h2>
                        <ul class="nav navbar-right panel_toolbox">
                        <!--<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
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
                        </li>-->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" id="content-esquema">
                    <?php
                        $is_primer_md = false;
                        $is_last_md = false;
                        $total_md = 1;
                        $increment_md = 0;
                    ?>
                    @foreach($vacunas_esquemas as $key=>$ve)   
                        <?php 
                            $key_plus = $key; 
                            $key_plus = $key_plus + 1; 
                            $i_actual = $ve->intervalo; 
                            if((count($vacunas_esquemas) - 1) == $key) {
                                $i_siguiente = 'none';
                            } else {
                                $i_siguiente = $vacunas_esquemas[$key_plus]->intervalo;
                            }
                            $col_md = 1; $plu_col_md = 0;
                            foreach ($vacunas_esquemas as $k => $v) {
                                if($ve->intervalo==$v->intervalo)
                                    $plu_col_md++;
                            }
                            $col_md = 12 / $plu_col_md;

                            if($increment_md==0) {
                                $is_primer_md = true;
                            } else {
                                $is_primer_md = false;
                            }

                            if($col_md==6)
                                if($is_primer_md)
                                    $col_md = 3; 
                                else
                                    $col_md = 9;
                            
                            if($col_md==4)
                                if($is_primer_md)
                                    $col_md = 6; 
                                else
                                    $col_md = 3;
                        

                            $total_md = $plu_col_md;
                            $increment_md++;
                        ?>

                        @if($key==0)
                            <div class="col-md-12">
                        @endif
                        <?php $fecha_ap = ''; ?>
                        @foreach($persona->personasVacunasEsquemas as $index=>$valor)
                            @if($valor->vacunas_esquemas_id==$ve->id)
                                <?php
                                    $fecha_ap = explode("-", trim(substr($valor->fecha_aplicacion, 0, -8))); 
                                    $fecha_ap = $fecha_ap[2].'-'.$fecha_ap[1].'-'.$fecha_ap[0];
                                    break; 
                                ?>
                            @endif
                        @endforeach
                            <div class="animated flipInY col-lg-{{$col_md}} col-md-{{$col_md}} col-sm-{{$col_md}} col-xs-12"><br>
                                <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #{{$ve->vacuna->color_rgb}}; background-color:#{{$ve->vacuna->color_rgb}} !important;">
                                    <div class="row">
                                        <div class="col-md-12"> <span style="font-size:x-large;font-weight:bold;"> {{$ve->vacuna->clave}} <small> @if($ve->tipo_aplicacion==1) Única @endif @if($ve->tipo_aplicacion==2) 1a Dosis @endif @if($ve->tipo_aplicacion==3) 2a Dosis @endif @if($ve->tipo_aplicacion==4) 3a Dosis @endif @if($ve->tipo_aplicacion==5) 4a Dosis @endif @if($ve->tipo_aplicacion==6) Refuerzo @endif  </small> </span> <span style="font-size:large;" class="pull-right"> @if($ve->intervalo<=29) Nacimiento @else  @if(($ve->intervalo/30)<=23){{($ve->intervalo/30)}} Meses @else {{round((($ve->intervalo/30)/12))}} Años @endif @endif  </span></div>
                                    </div>
                                    <div class="row">
                                        <div class="bt-flabels__wrapper">
                                            {!! Form::label('fecha_aplicacion'.$ve->id, 'Fecha de aplicación', ['for' => 'fecha_aplicacion'.$ve->id] ) !!}
                                            {!! Form::text('fecha_aplicacion'.$ve->id, $fecha_ap, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'fecha_aplicacion'.$ve->id, 'autocomplete' => 'off', 'placeholder' => 'Fecha de aplicación' ]  ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        @if((count($vacunas_esquemas)-1) == $key)
                            </div>
                        @else
                            @if($key!=0)
                                @if($i_actual!=$i_siguiente)
                                    <?php 
                                        $is_primer_md = false;
                                        $is_last_md = false;
                                        $total_md = 1;
                                        $increment_md = 0;
                                    ?>
                                    </div> <div class="col-md-12">
                                @endif
                            @endif
                        @endif
                    @endforeach
                    </div>
                    </div>
                </div>
                @else
                    <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin esquema</h3></div>
                @endif

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
    <!-- File Input -->
    {!! Html::script('assets/mine/js/bootstrap.file-input.js') !!}
    <!-- Switchery -->
    {!! Html::script('assets/vendors/switchery/dist/switchery.min.js') !!}
    <!-- Form Mine -->
    {!! Html::script('assets/mine/js/parsleyjs/2.1.2/parsley.min.js') !!}
    {!! Html::script('assets/mine/js/floating-labels.js') !!}
    {!! Html::script('assets/mine/js/myCheckBox.js') !!}
    {!! Html::script('assets/mine/js/myMessage.js') !!}
    {!! Html::script('assets/mine/js/myfileImage.js') !!}
    {!! Html::script('assets/mine/js/myTags.js') !!}
    {!! Html::script('assets/mine/js/myPicker.js') !!}

    <!-- Select2 Personalizado -->
    <script>
        $(".js-data-clue,.js-data-ageb,.js-data-genero,.js-data-parto,.js-data-estado,.js-data-municipio,.js-data-codigo,.js-data-institucion,.js-data-localidad").select2();

        $(".js-data-genero,.js-data-estado").prop("readonly", true);

        $(".js-data-clue").change(function(){
            var clue_id = $(this).val();
            $.get('../../catalogo/clue/'+clue_id, function(response, status){ // Consulta CURP
                $(".js-data-estado").val(response.data.idEntidad).trigger("change");
                $(".js-data-municipio").val(response.data.idMunicipio).trigger("change");
                $(".js-data-localidad").val(response.data.idLocalidad).trigger("change");
            }).fail(function(){  // Calcula CURP                    
                new PNotify({
                    title: 'Info!',
                    text: 'No se consulto detalles de la unidad de salud',
                    type: 'warning',
                    styling: 'bootstrap3'
                });
            });
        });
    </script>
@endsection