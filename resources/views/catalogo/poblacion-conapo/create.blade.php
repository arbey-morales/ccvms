@extends('app')
@section('title')
   Población CONAPO
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
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-cloud"></i> Población CONAPO <span id="anio"></span> </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('catalogo.poblacion-conapo.index') }}">
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
            {!! Form::open([ 'route' => 'catalogo.poblacion-conapo.store', 'id' => 'poblacion-conapo-form', 'method' => 'POST', 'class' => 'uk-form bt-flabels js-flabels', 'data-parsley-validate' => 'on', 'data-parsley-errors-messages-disabled' => 'on']) !!}

                @if(count($data)>0)
                <div class="x_panel">
                    <div class="x_content" id="content-cdj">
                        <div class="uk-text-center uk-margin-top pull-right">
                            <button type="reset" class="btn btn-primary btn-lg"> <i class="fa fa-eraser"></i> Limpiar</button>
                            @permission('create.catalogos')<button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-save"></i> Guardar</button>@endpermission
                        </div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle; text-align:center;" rowspan="2">Municipio / Edades</th>
                                    <th style="text-align:center; color:#4d81bf;" colspan="11">Hombres</th>
                                    <th style="text-align:center; color:#ed1586;" colspan="11">Mujeres</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align:middle; text-align:center;">*0</th>
                                    <th style="vertical-align:middle; text-align:center;">1</th>
                                    <th style="vertical-align:middle; text-align:center;">2</th>
                                    <th style="vertical-align:middle; text-align:center;">3</th>
                                    <th style="vertical-align:middle; text-align:center;">4</th>
                                    <th style="vertical-align:middle; text-align:center;">5</th>
                                    <th style="vertical-align:middle; text-align:center;">6</th>
                                    <th style="vertical-align:middle; text-align:center;">7</th>
                                    <th style="vertical-align:middle; text-align:center;">8</th>
                                    <th style="vertical-align:middle; text-align:center;">9</th>
                                    <th style="vertical-align:middle; text-align:center;">10</th>
                                    <th style="vertical-align:middle; text-align:center;">*0</th>
                                    <th style="vertical-align:middle; text-align:center;">1</th>
                                    <th style="vertical-align:middle; text-align:center;">2</th>
                                    <th style="vertical-align:middle; text-align:center;">3</th>
                                    <th style="vertical-align:middle; text-align:center;">4</th>
                                    <th style="vertical-align:middle; text-align:center;">5</th>
                                    <th style="vertical-align:middle; text-align:center;">6</th>
                                    <th style="vertical-align:middle; text-align:center;">7</th>
                                    <th style="vertical-align:middle; text-align:center;">8</th>
                                    <th style="vertical-align:middle; text-align:center;">9</th>
                                    <th style="vertical-align:middle; text-align:center;">10</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data as $key_mun=>$item)
                                    <tr>
                                        <td style="vertical-align:middle; width:7%;"> 
                                        {{$item->clave}} - {{$item->nombre}}
                                        </td>
                                        <td>
                                            {!! Form::text('hombrescero'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombrescero'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombresuno'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombresuno'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombresdos'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombresdos'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombrestres'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombrestres'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombrescuatro'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombrescuatro'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombrescinco'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombrescinco'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombresseis'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombresseis'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombressiete'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombressiete'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombresocho'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombresocho'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombresnueve'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombresnueve'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('hombresdiez'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'hombresdiez'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujerescero'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujerescero'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujeresuno'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujeresuno'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujeresdos'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujeresdos'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujerestres'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujerestres'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujerescuatro'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujerescuatro'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujerescinco'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujerescinco'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujeresseis'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujeresseis'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujeressiete'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujeressiete'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujeresocho'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujeresocho'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujeresnueve'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujeresnueve'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('mujeresdiez'.$item->id, 0, ['class' => 'form-control has-feedback-left', 'aria-describedby' => 'inputSuccess2Status', 'id' => 'mujeresdiez'.$item->id, 'autocomplete' => 'off' ]  ) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                    <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin municipios</h3></div>
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

        var anio = moment().format('YYYY');
        $("#anio").html('para el año '+anio);

        $(document).ready(function(){
            $("input[name*='hombres'],input[name*='mujeres']").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });
    </script>
@endsection