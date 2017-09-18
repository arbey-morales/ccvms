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
        <a class="btn btn-primary btn-lg" href="#" onClick="descargarSeguimientos()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-comment"></i> Seguimientos</a>
        <a class="btn btn-warning btn-lg" href="#" onClick="descargarActividades()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-newspaper-o"></i> Actividades</a>
        <a class="btn btn-info btn-lg" href="#" onClick="descargarBiologicos()" class="button" data-toggle="tooltip" data-placement="bottom" title="" data-original-title=".Pdf"> <i class="fa fa-flask"></i> Biológicos</a>
        </div>
        <div class="col-md-6">
            @permission('create.personas')<a class="btn btn-default btn-lg pull-right" href="{{ route('persona.create') }}" role="button">Agregar Persona</a>@endpermission
        </div>
    </div>
    <div class="clearfix"></div>

    @include('errors.msgAll')

    {!! Form::open([ 'route' => 'persona.index', 'method' => 'GET']) !!}

        
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-3">
                            {!!Form::select('municipios_id', $municipios, $m_selected, ['class' => 'form-control js-data-municipio select2', 'style' => 'width:100%'])!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::select('clues_id', $clues, $c_selected, ['class' => 'form-control js-data-clue select2', 'style' => 'width:100%'])!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::select('edad', ['0-0-7' => 'Nacimiento','0-2-0' => '2 meses','0-4-0' => '4 meses','0-6-0' => '6 meses','0-7-0' => '7 meses','1-0-0' => '1 año','1-6-0' => '1 año 6 meses','2-0-0' => '2 años','3-0-0' => '3 años','4-0-0' => '4 años','5-0-0' => '5 años','6-0-0' => '6 años'], $e_selected, ['class' => 'form-control js-data-edad select2', 'style' => 'width:100%'])!!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::text('q', $q, ['class' => 'form-control', 'id' => 'q', 'autocomplete' => 'off', 'placeholder' => 'Buscar por Nombre y CURP ' ]) !!}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-10 text-right">
                            <h3 class="text-info"> {{count($data)}} <small>Resultados</small></h3>
                            </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success btn-lg js-submit"> <i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
            </div>

    {!! Form::close() !!}
    <div class="x_panel">
        <div class="x_content">
            @if(count($data)>0)
                @include('persona.list')
             @endif
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
    var data = $.parseJSON(escaparCharEspeciales('{{$data}}'));
    var usuario = $.parseJSON(escaparCharEspeciales('{{$user}}'));
    var definicionSeguimientos = tablaSeguimientos();
    var definicionActividades = tablaActividades();
    var definicionBilogicos = tablaBiologicos();
    
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            language: {
                url: '/assets/mine/js/dataTables/es-MX.json'
            }
        });

        // Delete on Ajax 
        $('.btn-delete').click(function(e){
            e.preventDefault();
            var row = $(this).parents('tr');
            registro_borrar = row.data('id');
            $("#modal-text").html(row.data('nombre'));
        });     
        
        $(".js-data-clue,.js-data-edad,.js-data-municipio").select2();
    });

    // Confirm delete on Ajax
    $('.btn-confirm-delete').click(function(e){
        var row = $("tr#"+registro_borrar);
        var form = $("#form-delete");
        var url_delete = form.attr('action').replace(":ITEM_ID", registro_borrar);
        var data = $("#form-delete").serialize();
        $.post(url_delete, data, function(response, status){
            if (response.code==1) {
                notificar(response.title,response.text,response.type,3000);
                if(response.type=='success') {
                    row.fadeOut();
                }
            }
            if (response.code==0) {
                notificar('Error','Ocurrió un error al intentar borrar el registro, verifique!','error',3000);
            }
        }).fail(function(){
            notificar('Error','No se procesó la eliminación del registro','error',3000);
            row.fadeIn();
        });
    });

    function descargarSeguimientos()
    {
        pdfMake.createPdf(definicionSeguimientos).open('Reporte de Seguimientos '+moment().format('DD-MM-YYYY')+'.pdf');
    }
    function descargarActividades()
    {
        pdfMake.createPdf(definicionActividades).open('Reporte de Actividades '+moment().format('DD-MM-YYYY')+'.pdf');
    }
    function descargarBiologicos()
    {
        pdfMake.createPdf(definicionBiologicos).open('Reporte de Biológicos '+moment().format('DD-MM-YYYY')+'.pdf');
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
    function tablaSeguimientos() {
        var content = [];
        var body = [];
        body.push([
                    {'text':'#', 'style':'celda_header'},
                    {'text':'Nombre completo', 'style':'celda_header'},
                    {'text':'CURP', 'style':'celda_header'},
                    {'text':'Nac.', 'style':'celda_header'},
                    {'text':'G', 'style':'celda_header'},
                    {'text':'Domicilio', 'style':'celda_header'},
                    {'text':'---', 'style':'celda_header'}
                ]);
        $.each(data, function( indice, row ) { 
            var data_row = [];
            data_row.push({'text':''+(parseInt(indice) + 1)+'', 'style':'celda_body'});
            data_row.push({'text':row.apellido_paterno+' '+row.apellido_materno+' '+row.nombre, 'style':'celda_body'});
            data_row.push({'text':row.curp, 'style':'celda_body'});
            data_row.push({'text':row.fecha_nacimiento, 'style':'celda_body'});
            data_row.push({'text':row.genero, 'style':'celda_body'});
            var colonia = '';
            if(row.colonias_id!=null){
                colonia = row.colonia.nombre+', ';
            }
            data_row.push({'text':row.calle+' '+row.numero+', '+colonia+' '+row.localidad.nombre+', '+row.municipio.nombre, 'style':'celda_body'});
            var seg_head  = [];
            var seg_marca = [];   
            var vacunas   = [];          
            if(row.seguimientos.length>0){  
                $.each(row.seguimientos, function( index, seg ) { 
                    var dosis = [];
                    if(index==0){
                        var total = 0;
                        $.each(row.seguimientos, function( ind2, seg2 ) { if(seg2.vacunas_id==seg.vacunas_id){ total++; dosis.push(seg2.marca); } });
                        vacunas.push({'clave': seg.clave, 'color_rgb': seg.color_rgb, 'dosis': dosis, 'total': total});
                    } else {
                        var total = 0;
                        if(row.seguimientos[(index-1)].vacunas_id!=seg.vacunas_id){
                            $.each(row.seguimientos, function( ind2, seg2 ) { if(seg2.vacunas_id==seg.vacunas_id){ total++; dosis.push(seg2.marca); } });
                            vacunas.push({'clave': seg.clave, 'color_rgb': seg.color_rgb, 'dosis': dosis, 'total': total});
                        }
                    }
                });
            }

            var widths = [];
            var width = Math.round((100 / row.seguimientos.length));
            $.each(vacunas, function( ind, vac ) {                
                var temp_width = (vac.total * width);
                widths.push(''+width+'%');
                if(vac.total>1){
                    seg_head.push({'text': vac.clave, 'width': temp_width+'%', 'color': '#'+vac.color_rgb, 'fontSize':6});
                    seg_marca.push({'text': vac.clave, 'width': temp_width+'%', 'color': '#'+vac.color_rgb, 'fontSize':6});
                   // $.each(vac.dosis, function( ind_dos, dos ) { seg_marca.push({'text': ''+dos+'', 'fontSize':6}); });
                } else {
                    seg_head.push({'text': vac.clave, 'width': temp_width+'%', 'color': '#'+vac.color_rgb, 'fontSize':6});
                    seg_marca.push({'text': vac.clave, 'width': temp_width+'%', 'color': '#'+vac.color_rgb, 'fontSize':6});
                    ///seg_marca.push({'text': vac.dosis[0], 'fontSize':6});
                }
            });  
            console.log(seg_head,seg_marca);
            var body_vac = [];

            data_row.push({'layout': 'lightHorizontalLines','table': { 'body': [seg_head,seg_marca] } });
            body.push(data_row);
        });
        return definicionSeguimientos = {
            // a string or { width: number, height: number } OFICIO PIXELS: { width: 1285, height: 816 }
            pageSize: 'LEGAL',
            // by default we use portrait, you can change it to landscape if you wish
            pageOrientation: 'landscape',
            pageMargins: [ 40, 70, 40, 70 ],
            header: {
                margin: [ 40, 30, 40, 30 ],
                columns: [
                    { image: logo_sm, width: 85 },
                    { text: 'Rep. de seguimiento \n Jurisdicción '+usuario.jurisdiccion.clave+' '+usuario.jurisdiccion.nombre, width: 790, alignment: 'center', bold: true },
                    { image: censia, width: 50 }
                ]
            },
            footer: {
                margin: [ 40, 30, 40, 30 ],                
                columns: [
                    { text: 'Generó: '+usuario.nombre+' '+usuario.paterno+' '+usuario.materno+' / '+usuario.email, alignment: 'left' },
                    { text: moment().format('LL'), alignment: 'right' }
                ]
            },
            content: [
                {
                    layout: 'lightHorizontalLines',
                    table: {
                        widths: ['3%', '14%', '10%', '5%', '3%', '20%', '45%'],
                        body
                    }
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
    }

    ///// Actividades
    function tablaActividades() {
        var body = [];
        body.push([
                    {'text':'Nombre', 'style':'celda_header'},
                    {'text':'Nacimiento', 'style':'celda_header'},
                    {'text':'Género', 'style':'celda_header'},
                    {'text':'CURP', 'style':'celda_header'},
                    {'text':'Tutor', 'style':'celda_header'},
                    {'text':'Parto', 'style':'celda_header'},
                    {'text':'Dirección', 'style':'celda_header'},
                    {'text':'CLUE', 'style':'celda_header'},
                    {'text':'AGEB', 'style':'celda_header'},
                    {'text':'Sector', 'style':'celda_header'},
                    {'text':'Mz', 'style':'celda_header'},
                    {'text':'Código', 'style':'celda_header'},
                    {'text':'Afiliación', 'style':'celda_header'}
                ]);
        $.each(data, function( indice, row ) { 
            var data_row = [];
            data_row.push({'text':row.nombre+' '+row.apellido_paterno+' '+row.apellido_materno, 'style':'celda_body'});
            data_row.push({'text':row.fecha_nacimiento, 'style':'celda_body'});
            data_row.push({'text':row.genero, 'style':'celda_body'});
            data_row.push({'text':row.curp, 'style':'celda_body'});
            data_row.push({'text':row.tutor, 'style':'celda_body'});
            data_row.push({'text':row.tipo_parto.descripcion, 'style':'celda_body'});
            var colonia = '';
            if(row.colonias_id!=null){
                colonia = row.colonia.nombre+', ';
            }
            data_row.push({'text':row.calle+' '+row.numero+', '+colonia+' '+row.localidad.nombre+', '+row.municipio.nombre, 'style':'celda_body'});
            data_row.push({'text':row.clue.clues+' '+row.clue.nombre, 'style':'celda_body'});
            var ageb = '';
            if(row.agebs_id!=null){
                ageb = row.ageb.id;
                ageb = ageb.substr(-4);
            }
            data_row.push({'text':ageb, 'style':'celda_body'});
            data_row.push({'text':row.sector, 'style':'celda_body'});
            data_row.push({'text':row.manzana, 'style':'celda_body'});
            var codigo = '';
            if(row.codigos_id!=null){
                codigo = row.codigo.nombre;
            }
            data_row.push({'text':codigo, 'style':'celda_body'});
            var afiliacion = '';
            if(row.codigos_id!=null){
                afiliacion = row.afiliacion.nombre_corto;
            }
            data_row.push({'text':afiliacion, 'style':'celda_body'});
            body.push(data_row);
        });
        return definicionActividades = {
            // a string or { width: number, height: number } OFICIO PIXELS: { width: 1285, height: 816 }
            pageSize: 'LEGAL',
            // by default we use portrait, you can change it to landscape if you wish
            pageOrientation: 'landscape',
            pageMargins: [ 40, 70, 40, 70 ],
            header: {
                margin: [ 40, 30, 40, 30 ],
                columns: [
                    { image: logo_sm, width: 85 },
                    { text: 'Censo Nominal \n Jurisdicción '+usuario.jurisdiccion.clave+' '+usuario.jurisdiccion.nombre, width: 790, alignment: 'center', bold: true },
                    { image: censia, width: 50 }
                ]
            },
            footer: {
                margin: [ 40, 30, 40, 30 ],                
                columns: [
                    { text: 'Generó: '+usuario.nombre+' '+usuario.paterno+' '+usuario.materno+' / '+usuario.email, alignment: 'left' },
                    { text: moment().format('LL'), alignment: 'right' }
                ]
            },
            content: [
                {
                    layout: 'lightHorizontalLines', // optional
                    table: {
                        body
                    }
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
    }

    ///// Biológicos
    function tablaBiologicos() {
        var body = [];
        body.push([
                    {'text':'Nombre', 'style':'celda_header'},
                    {'text':'Nacimiento', 'style':'celda_header'},
                    {'text':'Género', 'style':'celda_header'},
                    {'text':'CURP', 'style':'celda_header'},
                    {'text':'Tutor', 'style':'celda_header'},
                    {'text':'Parto', 'style':'celda_header'},
                    {'text':'Dirección', 'style':'celda_header'},
                    {'text':'CLUE', 'style':'celda_header'},
                    {'text':'AGEB', 'style':'celda_header'},
                    {'text':'Sector', 'style':'celda_header'},
                    {'text':'Mz', 'style':'celda_header'},
                    {'text':'Código', 'style':'celda_header'},
                    {'text':'Afiliación', 'style':'celda_header'}
                ]);
        $.each(data, function( indice, row ) { 
            var data_row = [];
            data_row.push({'text':row.nombre+' '+row.apellido_paterno+' '+row.apellido_materno, 'style':'celda_body'});
            data_row.push({'text':row.fecha_nacimiento, 'style':'celda_body'});
            data_row.push({'text':row.genero, 'style':'celda_body'});
            data_row.push({'text':row.curp, 'style':'celda_body'});
            data_row.push({'text':row.tutor, 'style':'celda_body'});
            data_row.push({'text':row.tipo_parto.descripcion, 'style':'celda_body'});
            var colonia = '';
            if(row.colonias_id!=null){
                colonia = row.colonia.nombre+', ';
            }
            data_row.push({'text':row.calle+' '+row.numero+', '+colonia+' '+row.localidad.nombre+', '+row.municipio.nombre, 'style':'celda_body'});
            data_row.push({'text':row.clue.clues+' '+row.clue.nombre, 'style':'celda_body'});
            var ageb = '';
            if(row.agebs_id!=null){
                ageb = row.ageb.id;
                ageb = ageb.substr(-4);
            }
            data_row.push({'text':ageb, 'style':'celda_body'});
            data_row.push({'text':row.sector, 'style':'celda_body'});
            data_row.push({'text':row.manzana, 'style':'celda_body'});
            var codigo = '';
            if(row.codigos_id!=null){
                codigo = row.codigo.nombre;
            }
            data_row.push({'text':codigo, 'style':'celda_body'});
            var afiliacion = '';
            if(row.codigos_id!=null){
                afiliacion = row.afiliacion.nombre_corto;
            }
            data_row.push({'text':afiliacion, 'style':'celda_body'});
            body.push(data_row);
        });
        return definicionBiologicos = {
            // a string or { width: number, height: number } OFICIO PIXELS: { width: 1285, height: 816 }
            pageSize: 'LEGAL',
            // by default we use portrait, you can change it to landscape if you wish
            pageOrientation: 'landscape',
            pageMargins: [ 40, 70, 40, 70 ],
            header: {
                margin: [ 40, 30, 40, 30 ],
                columns: [
                    { image: logo_sm, width: 85 },
                    { text: 'Censo Nominal \n Jurisdicción '+usuario.jurisdiccion.clave+' '+usuario.jurisdiccion.nombre, width: 790, alignment: 'center', bold: true },
                    { image: censia, width: 50 }
                ]
            },
            footer: {
                margin: [ 40, 30, 40, 30 ],                
                columns: [
                    { text: 'Generó: '+usuario.nombre+' '+usuario.paterno+' '+usuario.materno+' / '+usuario.email, alignment: 'left' },
                    { text: moment().format('LL'), alignment: 'right' }
                ]
            },
            content: [
                {
                    layout: 'lightHorizontalLines', // optional
                    table: {
                        body
                    }
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
    }
            
    </script>
    <!-- /Datatables -->
@endsection