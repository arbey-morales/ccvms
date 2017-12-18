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
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-share-alt-square"></i> Cuadro de Distribución Jurisdiccional <i class="fa fa-angle-right text-danger"></i><small> Nuevo</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('cuadro-dist-juris.index') }}">
                        <i class="fa fa-chevron-circle-left" style="font-size:30px;"></i>
                    </a>
                </li>
                <li>
                    <a class="collapse-link">
                        
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('errors.msgAll') <!-- Mensages -->
            {!! Form::open([ 'route' => 'cuadro-dist-juris.store', 'id' => 'cuadros-distribucion-jurisdiccional-form', 'method' => 'POST', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}
               
               <div class="bt-form__wrapper">
                    <div class="bt-flabels__wrapper">
                        {!! Form::label('pedidos_estatales_id', '* Pedido estatal', ['for' => 'pedidos_estatales_id'] ) !!}
                        {!! Form::select('pedidos_estatales_id', $pedidos_estatales,  0, ['class' => 'form-control js-data-pedidos-estatales select2', 'data-parsley-required' => 'true', 'data-parsley-type' => 'number', 'data-parsley-min' => '1', 'id' => 'pedidos_estatales_id', 'data-placeholder' => '* Pedido estatal', 'style' => 'width:100%'] ) !!}
                        <span class="bt-flabels__error-desc">Requerido</span>
                    </div>
                    <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper">
                                {!! Form::label('descripcion', '* Descripción ', ['for' => 'descripcion'] ) !!}
                                {!! Form::text('descripcion', null, ['class' => 'form-control', 'data-parsley-required' => 'true', 'data-parsley-length' => '[10, 255]', 'id' => 'descripcion', 'autocomplete' => 'off', 'placeholder' => '* Descripción ' ]  ) !!}
                                <span class="bt-flabels__error-desc">Requerido / Mín: 10 - Máx: 255 caracteres</span>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <div class="bt-flabels__wrapper bt-flabels--right">
                                {!! Form::label('fecha', '* Fecha', ['for' => 'fecha'] ) !!}
                                {!! Form::text('fecha', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'data-parsley-required' => 'true', 'id' => 'fecha', 'autocomplete' => 'off', 'placeholder' => '* Fecha' ]  ) !!}
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                <span class="bt-flabels__error-desc">Requerido</span>
                            </div>
                        </div>
                    </div>
                </div> 

                @if(count($vacunas)>0 && count($jurisdicciones)>0)
                <div class="x_panel">
                    <div class="x_content" id="content-cdj">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vacuna/Jurisdicción</th>
                                    @foreach($jurisdicciones as $key_jur=>$value)
                                        <th>{{$value->nombre}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vacunas as $key_vac=>$item)
                                    <tr>
                                        <th style="background-color:#{{$item->color_rgb}}; color:#FFF; text-align:center; vertical-align:middle; font-size: large;">{{$item->nombre}}</th>
                                        @foreach($jurisdicciones as $key_jur=>$value)
                                            <th> 
                                                
                                                {!! Form::label('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-cantidad', 'Cantidad', ['for' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-cantidad'] ) !!}
                                                {!! Form::number('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-cantidad', 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-cantidad', 'autocomplete' => 'off', 'placeholder' => 'Cantidad' ]  ) !!}

                                                {!! Form::label('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-lote', '# Lote', ['for' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-lote'] ) !!}
                                                {!! Form::text('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-lote', null, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-lote', 'autocomplete' => 'off', 'placeholder' => '# Lote' ]  ) !!}

                                                {!! Form::label('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-fecha-caducidad', 'Fecha caducidad', ['for' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-fecha-caducidad'] ) !!}
                                                {!! Form::text('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-fecha-caducidad', null , ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-fecha-caducidad', 'autocomplete' => 'off', 'placeholder' => 'Fecha caducidad' ]  ) !!}

                                                {!! Form::label('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-rt', 'RT', ['for' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-rt'] ) !!}
                                                {!! Form::text('vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-rt', null, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'vacuna-'.$item->id.'-jurisdiccion-'.$value->id.'-rt', 'autocomplete' => 'off', 'placeholder' => 'RT' ]  ) !!}
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                    <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin Vacunas o Jurisdicciones</h3></div>
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
        $(".js-data-pedidos-estatales").select2();
    </script>
@endsection