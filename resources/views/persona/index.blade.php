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

        {!! Form::open(['id' => 'form']) !!}
            <div class="x_panel">
                <div class="x_content">

                    <div class="row tile_count">
                        <div class="col-md-7 col-sm-8 col-xs-12 tile_stats_count">
                            <span class="count_top"><i class="fa fa-filter"></i> Filtros</span><br>
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::hidden('filtro', 1, ['class' => 'form-control', 'id' => 'filtro', 'autocomplete' => 'off' ]) !!}
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
                                    {!!Form::select('agebs_id', [], 0, ['class' => 'form-control js-data-ageb select2', 'style' => 'width:100%'])!!}
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::text('sector', '', ['class' => 'form-control', 'id' => 'sector', 'autocomplete' => 'off', 'placeholder' => '# Sector' ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::text('manzana', '', ['class' => 'form-control', 'id' => 'manzana', 'autocomplete' => 'off', 'placeholder' => '# manzana' ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2 col-xs-12 tile_stats_count">
                            <span class="count_top"><i class="fa fa-male"></i> Nombre del infante/tutor o CURP</span>
                            <div class="row">
                                <div class="col-md-12"> 
                                {!! Form::text('q', '', ['class' => 'form-control', 'id' => 'q', 'autocomplete' => 'off', 'placeholder' => 'MOTC880220...' ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-1 col-xs-12 tile_stats_count">
                            <span class="count_top"><i class="fa fa-magic"></i> Todo, sin filtros</span>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::checkbox('todo', '1', false, ['class' => 'js-switch', 'id' => 'todo'] ) !!} 
                                    {!! Form::label('todo-todo', ' ', ['for' => 'todo', 'style' => 'font-size:large; padding-right:10px;'] ) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::radio('rep', 'seg', true,['id' => 'seg', 'class' => 'flat']) !!}
                            {!! Form::label('seg', 'De seguimiento', ['for' => 'seg', 'style' => 'font-size:large; padding-right:10px;'] ) !!}
<!--
                            {!! Form::radio('rep', 'act', false,['id' => 'act', 'class' => 'flat']) !!}
                            {!! Form::label('act', 'De actividades', ['for' => 'act', 'style' => 'font-size:large; padding-right:10px;'] ) !!}

                            {!! Form::radio('rep', 'bio', false,['id' => 'bio', 'class' => 'flat']) !!}
                            {!! Form::label('bio', 'De biológico', ['for' => 'bio', 'style' => 'font-size:large; padding-right:10px;'] ) !!}
                            -->
                        </div>
                        <div class="col-md-2 no-resultados">
                        </div>
                        <div class="col-md-2 text-right">
                            <button type="button" class="btn btn-success btn-lg js-ajax"> <i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
            </div>

    {!! Form::close() !!}
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

    <!-- Datatables -->
    <script>
    var registro_borrar = null;
    var data = [];
    var usuario = { jurisdiccion:{ clave:'', nombre:'' } };
    var text = '';
    var municipios = [{ 'id': 0, 'text': 'Seleccionar un municipio' }];
    var localidades = [{ 'id': 0, 'text': 'Seleccionar una localidad' }];
    var clues = [{ 'id': 0, 'text': 'Seleccionar una unidad de salud' }];
    var agebs = [{ 'id': 0, 'text': 'Seleccionar una ageb' }];
    
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            language: {
                url: '/assets/mine/js/dataTables/es-MX.json'
            }
        });

        // Delete on Ajax 
        $( "#contenido" ).on( "click", ".btn-delete", function(e) {
            e.preventDefault();
            var row = $(this).parents('div');
            registro_borrar = row.data('id');
            $("#modal-text").html(row.data('nombre'));
        });
        
        $(".js-data-clue").select2({
            language: "es",
            data: clues
        });
        $(".js-data-municipio").select2({
            language: "es",
            data: municipios
        });
        $(".js-data-localidad").select2({
            language: "es",
            data: localidades
        });
        $(".js-data-ageb").select2({
            language: "es",
            data: agebs
        });
        iniciarMunicipio();
    });

    $('#todo').change(function() {
        if ($(this).is(':checked')){
            $(".js-ajax").click();
        }
    });

    /*** BUSCAR */
    $(".js-ajax").click(function(e){
        e.preventDefault();
        $("#contenido").empty();
        var dato = $("#form").serialize();
        $(".no-resultados").empty().html('<i class="fa fa-spinner fa-spin"></i> Buscando');
        $.get('persona/buscar', dato, function(response, status){            
            if(response.data==null){
                notificar('Sin resultados','warning',2000);
                $(".no-resultados").empty();
            } else { 
                $(".no-resultados").empty();                  
                if(response.data.length<=0){
                    notificar('Información','No existen resultados','warning',2000);
                    $(".no-resultados").html('Sin resultados');
                } else {
                    notificar('Información','Cargando '+response.data.length+' resultados','info',2000);
                    $(".no-resultados").html('<a class="btn btn-primary btn-lg" href="#" onClick="descargarSeguimientos()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-comment"></i> Seguimientos</a>');
                    data = response.data;                    
                    usuario = response.usuario;
                    text = response.text;
                    $("#contenido").empty();
                    //$("#contenido").empty().html('<table id="data-table" class="table" cellspacing="0" width="100%"><tbody>');
                    //$("#contenido").append('<ul class="list-unstyled top_profiles scroll-view">');
                    $.each(response.data, function( i, cont ) {
                        var icono = '';
                        if(cont.genero=='M'){
                            icono = '<i class="fa fa-male" style="color:#4d81bf; font-size:x-large;"></i>';
                        }
                        if(cont.genero=='F'){
                            icono = '<i class="fa fa-female" style="color:#ed1586; font-size:x-large;"></i>';
                        }
                        var url_edit = '{{ Route::getCurrentRoute()->getPath() }}';
                        //var con = '<li class="media event"> <a class="pull-left border-aero profile_thumb"> <i class="fa fa-user aero"></i> </a> <div class="media-body"> <a class="title" href="#">Ms. Mary Jane</a> <p><strong>$2300. </strong> Agent Avarage Sales </p> <p> <small>12 Sales Today</small> </p> </div> </li>';
                        //$("#contenido").append(con);
                        $("#contenido").append('<div class="row '+cont.id+'" data-toggle="tooltip" data-placement="top" data-original-title="'+cont.usuario_id+' / '+cont.created_at+'"><div class="col-md-1" id="'+cont.id+'" data-id="'+cont.id+'" data-nombre="'+cont.nombre+' '+cont.apellido_paterno+' '+cont.apellido_materno+'"><button type="button" class="btn btn-danger btn-delete" style="font-size:large;" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button></div> <div class="col-md-11"><a href="'+url_edit+'/'+cont.id+'"> <div class="mail_list"> <div class="right">  <h3> '+icono+' - '+cont.apellido_paterno+' '+cont.apellido_materno+' '+cont.nombre+' | <span style="color:tomato;">  '+cont.curp+'</span> | <span style="color:gray; font-weight:normal;"> TUTOR: '+cont.tutor+'</span> <small>'+cont.fecha_nacimiento+'</small></h3> <p> <span style="color:#428bca; font-weight:bold;">  '+cont.clu_clues+' - </span> <span style="color:#317d79; padding-right:20px;">  '+cont.clu_nombre+' - </span>  '+cont.calle+' '+cont.numero+', '+cont.col_nombre+', '+cont.loc_nombre+', '+cont.mun_nombre+' </p>  </div> </div> </a></div></div>');
                        //$("#contenido").append('<tr><td class="text-left">'+(i + 1)+'</td><td class="text-left"><a class="btn btn-default" href="'+url_edit+'/'+cont.id+'" class="button"> '+icono+' </a> '+cont.apellido_paterno+' '+cont.apellido_materno+' '+cont.nombre+'</td><td class="text-left">'+cont.curp+'</td><td class="text-left">'+cont.fecha_nacimiento+'</td><td class="text-left">'+cont.calle+' '+cont.numero+', '+cont.col_nombre+', '+cont.loc_nombre+', '+cont.mun_nombre+'</td><td class="text-left"><strong>'+cont.clu_clues+'</strong>, '+cont.clu_nombre+'</td><td class="text-left"><a class="btn btn-primary" href="'+url_edit+'/'+cont.id+'/edit" class="button"> <i class="fa fa-edit"></i> </a><button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button></td></tr>');
                    });
                    //$("#contenido").append('</ul>');
                    //$("#contenido").append('</tbody></table>');
                }  
            }
        }).fail(function(){ 
            notificar('Información','Falló la búsqueda','danger',2000);
            $(".no-resultados").empty();
        });
    });
    /*** Si cambia o tiene el foco */
    $(".js-data-clue,.js-data-localidad,.js-data-ageb,#sector,#manzana").change(function(){
        $("#q").val('');
        $("#filtro").val(1);
    });
    $(".js-data-clue,.js-data-localidad,.js-data-municipio,.js-data-ageb,#sector,#manzana").focus(function(){
        $("#q").val('');
        $("#filtro").val(1);
    });
    /*** Si cambia o tiene el foco */
    $("#q").change(function(){
        resetFiltro();
    });
    $("#q").focus(function(){
        resetFiltro();
    });

    function resetFiltro(){
        $("#filtro").val(2);
        $(".js-data-clue,.js-data-localidad,.js-data-ageb").empty();
        $("#sector,#manzana").val('');
        localidades = [{ 'id': 0, 'text': 'Seleccionar una localidad' }];
        clues = [{ 'id': 0, 'text': 'Seleccionar una unidad de salud' }];
        agebs = [{ 'id': 0, 'text': 'Seleccionar una ageb' }];
        $(".js-data-clue").select2({
            language: "es",
            data: clues
        });
        $(".js-data-municipio").val(0).trigger("change");
        $(".js-data-localidad").select2({
            language: "es",
            data: localidades
        });
        $(".js-data-ageb").select2({
            language: "es",
            data: agebs
        });
    }

    function iniciarMunicipio(){
        $.get('../catalogo/municipio', {}, function(response, status){
            if(response.data==null){
                notificar('Sin resultados','warning',2000);
            } else {         
                while (municipios.length) { municipios.pop(); }                
                municipios.push({ 'id': 0, 'text': 'Seleccionar un municipio' });           
                if(response.data.length<=0){
                    notificar('Información','No existen municipios','warning',2000);
                } else {
                    notificar('Información','Cargando municipios','info',2000);
                    $('.js-data-municipio').empty();                      
                    $.each(response.data, function( i, cont ) {
                        municipios.push({ 'id': cont.id, 'text': cont.nombre });
                    });  
                }  
                $(".js-data-municipio").select2({
                    language: "es",
                    data: municipios
                });
            }
        }).fail(function(){ 
            notificar('Información','Falló carga de municipios','danger',2000);
        });
    }

    $(".js-data-municipio").change(function(e){
        $('.js-data-localidad,.js-data-clue').empty();
        localidades = [{ 'id': 0, 'text': 'Seleccionar una localidad' }];
        clues = [{ 'id': 0, 'text': 'Seleccionar una unidad de salud' }];
        if($(this).val()!=0){ 
            /** CLUES */            
            $.get('../catalogo/clue?municipios_id='+$(this).val(), {}, function(response, status){
                while (clues.length) { clues.pop(); }                
                clues.push({ 'id': 0, 'text': 'Seleccionar una unidad de salud' });
                if(response.data==null){
                    notificar('Sin resultados','warning',2000);
                } else {                                        
                    if(response.data.length<=0){
                        notificar('Información','No existen unidad de salud','warning',2000);
                    } else {
                        notificar('Información','Cargando unidad de salud','info',2000);
                        $.each(response.data, function( i, cont ) {
                            clues.push({ 'id': cont.id, 'text': ''+cont.clues+' - '+cont.nombre });
                        }); 
                    }
                }
                $(".js-data-clue").select2({
                    language: "es",
                    data:clues
                }); 
            }).fail(function(){ 
                notificar('Información','Falló carga de unidad de salud','danger',2000);
                $(".js-data-clue").select2({
                    language: "es",
                    data:clues
                }); 
            });

            /** LOCALIDADES */
            $.get('../catalogo/localidad?municipios_id='+$(this).val(), {}, function(response, status){
                while (localidades.length) { localidades.pop(); }                
                localidades.push({ 'id': 0, 'text': 'Seleccionar una localidad' });
                if(response.data==null){
                    notificar('Sin resultados','warning',2000);
                } else {                                        
                    if(response.data.length<=0){
                        notificar('Información','No existen localidades','warning',2000);
                    } else {
                        notificar('Información','Cargando localidades','info',2000);
                        $.each(response.data, function( i, cont ) {
                            localidades.push({ 'id': cont.id, 'text': ''+ cont.clave+' - '+cont.nombre });
                        }); 
                    } 
                } 
                $(".js-data-localidad").select2({
                    language: "es",
                    data:localidades
                });
            }).fail(function(){ 
                notificar('Información','Falló carga de localidades','danger',2000);
                $(".js-data-localidad").select2({
                    language: "es",
                    data:localidades
                });
            });
        } else {
            $(".js-data-clue").select2({
                language: "es",
                data:clues
            }); 
            $(".js-data-localidad").select2({
                language: "es",
                data:localidades
            });
        }       
    });

    $(".js-data-localidad").change(function(e){
        /** AGEBS */
        $('.js-data-ageb').empty();
        agebs = [{ 'id': 0, 'text': 'Seleccionar una ageb' }];
        if($(this).val()!=0){        
            $.get('../catalogo/ageb?localidades_id='+$(this).val(), {}, function(response, status){
                while (agebs.length) { agebs.pop(); }                
                agebs.push({ 'id': 0, 'text': 'Seleccionar una ageb' });
                if(response.data==null){
                    notificar('Sin resultados','warning',2000);
                } else {                    
                    if(response.data.length<=0){
                        notificar('Información','No existen agebs','warning',2000);
                    } else {
                        notificar('Información','Cargando agebs','info',2000); 
                        $.each(response.data, function( i, cont ) {
                            agebs.push({ 'id': cont.id, 'text': ''+cont.id });
                        });
                    }                      
                }
                $(".js-data-ageb").select2({
                    language: "es",
                    data:agebs
                });
            }).fail(function(){ 
                notificar('Información','Falló carga de agebs','danger',2000);
                $(".js-data-ageb").select2({
                    language: "es",
                    data:agebs
                });
            });
        } else {
            $(".js-data-ageb").select2({
                language: "es",
                data:agebs
            });
        }
    });

    // Confirm delete on Ajax
    $('.btn-confirm-delete').click(function(e){
        var row = $("div#"+registro_borrar);
        var form = $("#form-delete");
        var url_delete = form.attr('action').replace(":ITEM_ID", registro_borrar);
        var dato = $("#form-delete").serialize();
        $.post(url_delete, dato, function(response, status){
            if (response.code==1) {
                notificar(response.title,response.text,response.type,3000);
                if(response.type=='success') {
                    row.slideUp('slow'); $("div."+registro_borrar).slideUp('slow');
                }
            }
            if (response.code==0) {
                notificar('Error','Ocurrió un error al intentar borrar el registro, verifique!','error',3000);
            }
        }).fail(function(){
            notificar('Error','No se procesó la eliminación del registro','error',3000);
            row.show(); $("div."+registro_borrar).show();
        });
    });

    /*function descargarSeguimientos()
    {
        var definicionSeguimientos
        pdfMake.createPdf(definicionSeguimientos).download('Reporte de Seguimientos '+moment().format('DD-MM-YYYY')+'.pdf');
    }*/
    function descargarActividades()
    {
        //pdfMake.createPdf(definicionActividades).open('Reporte de Actividades '+moment().format('DD-MM-YYYY')+'.pdf');
    }
    function descargarBiologicos()
    {
        //pdfMake.createPdf(definicionBiologicos).open('Reporte de Biológicos '+moment().format('DD-MM-YYYY')+'.pdf');
    }

    function escaparCharEspeciales(str)
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

    ///// Seguimientos    
    function descargarSeguimientos() {
        var dosiss   = [];
        var vac = [];
        var awd = [];
        var wd = 0;
        var body = [];       
        if(data[0]){
            wd = Math.round(100/data[0].seguimientos.length);
            if(data[0].seguimientos.length>0){                 
                $.each(data[0].seguimientos, function( index, seg ) {                     
                    awd.push(wd+'%');
                    dosiss.push({'text': ''+seg.tipo_aplicacion, 'color':'black', 'bold': true, 'alignment':'center', 'fontSize':'8'});                      
                    if(index==0){         
                        var tdv = 0; 
                        $.each(data[0].seguimientos, function( ind, s ) {  
                            if(s.vacunas_id==seg.vacunas_id){
                                tdv++;
                            }
                        });
                        if(tdv<=1){
                            vac.push({'text':seg.clave, 'color':'black', 'bold': true, 'alignment':'center', 'fontSize':'8'});
                        } else {
                            vac.push({'text':seg.clave, 'colSpan':tdv, 'color':'black', 'bold': true, 'alignment':'center', 'fontSize':'8'});
                        }
                    } else {
                        if(data[0].seguimientos[(index-1)].vacunas_id!=seg.vacunas_id){     
                            var tdv = 0; 
                            $.each(data[0].seguimientos, function( ind, s ) {  
                                if(s.vacunas_id==seg.vacunas_id){
                                    tdv++;
                                }
                            });
                            if(tdv<=1){
                                vac.push({'text':seg.clave, 'color':'black', 'bold': true, 'alignment':'center', 'fontSize':'8'});
                            } else {
                                vac.push({'text':seg.clave, 'colSpan':tdv, 'color':'black', 'bold': true, 'alignment':'center', 'fontSize':'8'});
                            }
                        } else {
                            vac.push({'text':''});
                        }
                    }
                });
            }
        }
        var head_table = [
            {text:'#', style:'celda_header'},
            {text:'NOMBRE COMPLETO', style:'celda_header'},
            {text:'CURP', style:'celda_header'},
            {text:'NAC.', style:'celda_header'},
            {text:'G', style:'celda_header'},
            {paddingLeft: 25, text:'DOMICILIO', style:'celda_header'},
            {paddingLeft: 25, layout: 'lightHorizontalLines',table: { widths: awd, body:
                    [
                        vac, //note the second object with empty string for 'text'
                        dosiss
                    ] 
                } 
            }
        ];
        body.push(head_table);

        var total_mpio = 0;
        var total_clue = 0;
        $.each(data, function( indice, row ) { 
            var data_row = [];           
            if(indice==0){
                body.push([{'text': row.mun_nombre, 'color':'black','fillColor':'#E0E0E0', 'bold':true, 'colSpan':7}]);
                body.push([{'text': row.clu_clues+' - '+row.clu_nombre, 'color':'black', 'fillColor': '#F0F0F0', 'marginLeft':10, 'colSpan':7}]);
            } else {            
                if(row.municipios_id!=data[indice - 1].municipios_id){ //  municipio diferente
                    body.push([{'text': row.mun_nombre, 'color':'black','fillColor':'#E0E0E0', 'bold':true, 'colSpan':7}]);
                }
                if(row.clues_id!=data[indice - 1].clues_id){
                    body.push([{'text': row.clu_clues+' - '+row.clu_nombre, 'color':'black', 'fillColor': '#F0F0F0', 'marginLeft':10, 'colSpan':7}]);
                }
            }
            
            
            data_row.push({'text':''+(parseInt(indice) + 1)+'', 'style':'celda_body'});
            data_row.push({'text':row.apellido_paterno+' '+row.apellido_materno+' '+row.nombre, 'style':'celda_body'});
            data_row.push({'text':row.curp, 'style':'celda_body'});
            data_row.push({'text':row.fecha_nacimiento, 'style':'celda_body'});
            data_row.push({'text':row.genero, 'style':'celda_body'});

            data_row.push({'text':row.calle+' '+row.numero+', '+row.col_nombre+' '+row.loc_nombre+', '+row.mun_nombre, 'style':'celda_body'});
            var seg_marca = [];   
            var ws = [];
                   
            $.each(row.seguimientos, function( ind_dos, dos ) { 
                ws.push(wd+'%');
                seg_marca.push({'text': ''+dos.marca+'', 'bold':true, 'alignment':'center', 'color': 'black', 'fontSize':7, 'margin':0}); 
            });
            data_row.push({layout: 'lightHorizontalLines', table: { widths: ws,  body: [seg_marca] } });
            body.push(data_row);
        });

        var definicionSeguimientos = {
            // a string or { width: number, height: number } OFICIO PIXELS: { width: 1285, height: 816 }
            pageSize: 'A3',
            // by default we use portrait, you can change it to landscape if you wish
            pageOrientation: 'landscape',
            pageMargins: [ 40, 70, 40, 70 ],
            header: {
                margin: [40,30],
                /*columns: [
                    { image: logo_sm, width: 85 },
                    { text: 'Reporte de seguimiento \n Jurisdicción '+usuario.jurisdiccion.clave+' '+usuario.jurisdiccion.nombre, width: 970, alignment: 'center', bold: true },
                    { image: censia, width: 50 }
                ]*/
                columns: [
                    {
                        layout: 'noBorders' ,
                        table: {
                            widths: [ '15%','70%','15%'], 
                            body: [
                                [
                                    { image: logo_sm, width: 72 }, 
                                    { text: 'Reporte de seguimiento \n Jurisdicción '+usuario.jurisdiccion.clave+' '+usuario.jurisdiccion.nombre, alignment: 'center', bold: true },
                                    { image: censia, width: 50, alignment:'right'}
                                ]
                            ]
                        }
                    }
                ]
            },
            footer: {
                margin: [ 40, 30, 40, 30 ],                
                columns: [
                    { text: text, alignment: 'left' },
                    { text: moment().format('LL'), alignment: 'right' }
                ]
            },
            content: [
                {
                    table: {
                        widths: ['1%', '11%', '10%', '5%', '1%', '10%', '62%'],
                        headerRows: 1,
                        body
                    }
                },
                {
                    columns: [
                        {
                            'text': 'Total de resultados: ', alignment: 'right', width: '20%', fontSize:12, marginTop:20, marginRight:10
                        },
                        {
                            'text': ''+data.length, alignment: 'left', width: '80%', bold:true, fontSize:12, color: 'black', marginTop:20
                        }
                    ]
                }
            ],
            styles: {
                celda_header: {
                    fontSize: 10,
                    bold: true,
                    aligment: 'center'
                },
                celda_body: {
                    fontSize: 7,
                    italic: true,
                    alignment: 'left'
                }
            }
        }

        pdfMake.createPdf(definicionSeguimientos).download('Reporte de Seguimientos '+moment().format('DD-MM-YYYY')+'.pdf');
    }
            
    </script>
    <!-- /Datatables -->
@endsection