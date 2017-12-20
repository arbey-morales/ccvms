var registro_borrar = null;
var data = [];
var data_seguimiento = [];
var data_actividad = [];
var data_biologico = [];
var usuario = { jurisdiccion:{ clave:'', nombre:'' } };
var texto = '';
var catalogos_cargados = false;

var municipios = [{ 'id': 0, 'text': 'Seleccionar un municipio' }];
var jurisdicciones = [{ 'id': 0, 'text': 'Seleccionar una jurisdicción' }];
var localidades = [{ 'id': 0, 'text': 'Seleccionar una localidad' }];
var colonias = [{ 'id': 0, 'text': 'Seleccionar una colonia' }];
var biologicos = [{ 'id': 0, 'text': 'Todos los biológicos' }];
var clues = [{ 'id': 0, 'text': 'Seleccionar una unidad de salud' }];
var agebs = [{ 'id': 0, 'text': 'Seleccionar una ageb' }];
var ta_abreviatura = ["X","Ú","1a","2a","3a","4a","R"];

$("#tipos-biologicos").hide();

$(document).ready(function() {
    // Borra elelmento vía Ajax 
    $( "#contenido" ).on( "click", ".btn-delete", function(e) {
        e.preventDefault();
        var row = $(this).parents('div');
        registro_borrar = row.data('id');
        $("#modal-text").html(row.data('nombre'));
    });
    
    $(".js-data-jurisdiccion").select2({
        language: "es",
        data: jurisdicciones
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
    $(".js-data-colonia").select2({
        language: "es",
        data: colonias
    });
    $(".js-data-ageb").select2({
        language: "es",
        data: agebs
    });
    $(".js-data-biologico").select2({
        language: "es",
        data: biologicos
    });
});

$('#home-tab').click(function() {
    $("#contenido,.no-resultados").empty();
    if (!catalogos_cargados) {
        iniciar_catalogos();
    }
});
$('#profile-tab').click(function() {
    $("#contenido,.no-resultados").empty();
    if (!catalogos_cargados) {
        iniciar_catalogos();
    }
});
$('#profile-tab2').click(function() {
    $("#contenido,.no-resultados").empty();
    if (!catalogos_cargados) {
        iniciar_catalogos();
    }
});
$('#profile-tab3').click(function() {
    $("#contenido,.no-resultados").empty();
    if (!catalogos_cargados) {
        iniciar_catalogos();
    }
});

// Envia Formulario de búsqueda
$('#todo').change(function() {
    if ($(this).is(':checked')){
        $(".js-ajax-buscar").click();
    }
});

/**
 * Evita tecla "Enter"
 */
$(document).ready(function() {
    $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });
});
/**
 * TAB BUSCAR
 */
$(".js-ajax-buscar").click(function(e){
    e.preventDefault();
    $("#contenido").empty();        
    var dato = $("#form").serialize();
    $(".no-resultados").empty().html('<i class="fa fa-circle-o-notch fa-spin"></i> Buscando');
    $.get('persona/reporte/buscar', dato, function(response, status){       
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
            $(".no-resultados").empty();
        } else { 
            $(".no-resultados").empty();                  
            if(response.data.length<=0){
                notificar('Información','No existen resultados','warning',2000);
                $(".no-resultados").empty();
            } else {
                data = response.data;
                texto = response.text;
                usuario = response.user;
                notificar('Información','Cargando '+response.data.length+' resultados','info',2000);
                $("#contenido").empty();
                $.each(response.data, function( i, cont ) {
                    var icono = '';
                    if (cont.loc_nombre==null) {
                        cont.loc_nombre='';
                    }
                    if (cont.mun_nombre==null) {
                        cont.mun_nombre='';
                    }
                    if (cont.col_nombre==null) {
                        cont.col_nombre='';
                    }
                    if(cont.genero=='M'){
                        icono = '<i class="fa fa-male" style="color:#4d81bf; font-size:x-large;"></i>';
                    }
                    if(cont.genero=='F'){
                        icono = '<i class="fa fa-female" style="color:#ed1586; font-size:x-large;"></i>';
                    }
                    $("#contenido").append('<div class="row '+cont.id+'" data-toggle="tooltip" data-placement="top" data-original-title="dfsfsdfsdf'+cont.usuario_id+' / '+cont.created_at+'"><div class="col-md-1" id="'+cont.id+'" data-id="'+cont.id+'" data-nombre="'+cont.nombre+' '+cont.apellido_paterno+' '+cont.apellido_materno+'"><button type="button" class="btn btn-danger btn-delete" style="font-size:large;" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button></div> <div class="col-md-11"><a href="persona/'+cont.id+'"> <div class="mail_list"> <div class="right">  <h3> '+icono+' - '+cont.apellido_paterno+' '+cont.apellido_materno+' '+cont.nombre+' | <span style="color:tomato;">  '+cont.curp+'</span> | <span style="color:gray; font-weight:normal;"> TUTOR: '+cont.tutor+'</span> <small>'+cont.fecha_nacimiento+'</small></h3> <p> <span style="color:#428bca; font-weight:bold;">  '+cont.clu_clues+' - </span> <span style="color:#317d79; padding-right:20px;">  '+cont.clu_nombre+' - </span>  '+cont.calle+' '+cont.numero+', '+cont.col_nombre+', '+cont.loc_nombre+', '+cont.mun_nombre+' </p>  </div> </div> </a></div></div>');
                });
            }  
        }
    }).fail(function(){ 
        notificar('Información','Falló la búsqueda','danger',2000);
        $(".no-resultados").empty();
    });
});
/**
 * TAB BUSCAR
 */

function iniciar_catalogos(){
    catalogos_cargados = true;
    $.get('../catalogo/jurisdiccion', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {         
            while (jurisdicciones.length) { jurisdicciones.pop(); }                
            jurisdicciones.push({ 'id': 0, 'text': 'Seleccionar una jurisdicción' });           
            if(response.data.length<=0){
                notificar('Información','No existen jurisdicciones','warning',2000);
            } else {
                //notificar('Información','Cargando jurisdicciones','info',2000);
                $('.js-data-jurisdiccion').empty();                      
                $.each(response.data, function( i, cont ) {
                    jurisdicciones.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
                });  
            }  
            $(".js-data-jurisdiccion").select2({
                language: "es",
                data: jurisdicciones
            });
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de jurisdicciones','danger',2000);
    });

    $.get('../catalogo/municipio', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {         
            while (municipios.length) { municipios.pop(); }                
            municipios.push({ 'id': 0, 'text': 'Seleccionar un municipio' });           
            if(response.data.length<=0){
                notificar('Información','No existen municipios','warning',2000);
            } else {
               // notificar('Información','Cargando municipios','info',2000);
                $('.js-data-municipio').empty();                      
                $.each(response.data, function( i, cont ) {
                    municipios.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
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

    $.get('../catalogo/vacuna', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {         
            while (biologicos.length) { biologicos.pop(); }                
            biologicos.push({ 'id': 0, 'text': 'Todos los biológicos' });           
            if(response.data.length<=0){
                notificar('Información','No existen biológicos','warning',2000);
            } else {
                //notificar('Información','Cargando biológicos','info',2000);
                $('.js-data-biologico').empty();                      
                $.each(response.data, function( i, cont ) {
                    biologicos.push({ 'id': cont.id, 'text': cont.nombre });
                });  
            }  
            $(".js-data-biologico").select2({
                language: "es",
                data: biologicos
            });
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de biológicos','danger',2000);
    });
}

$(".js-data-jurisdiccion").change(function(e){
    if($(this).val()!=0){
        $.get('../catalogo/municipio?jurisdicciones_id='+$(this).val(), {}, function(response, status){
            if(response.data==null){
                notificar('Sin resultados','warning',2000);
            } else {         
                while (municipios.length) { municipios.pop(); }                
                municipios.push({ 'id': 0, 'text': 'Seleccionar un municipio' });           
                if(response.data.length<=0){
                    notificar('Información','No existen municipios','warning',2000);
                } else {
                    //notificar('Información','Cargando municipios','info',2000);
                    $('.js-data-municipio').empty();                      
                    $.each(response.data, function( i, cont ) {
                        municipios.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
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
});

$(".js-data-municipio").change(function(e){
    $('.js-data-localidad,.js-data-clue,.js-data-colonia').empty();
    localidades = [{ 'id': 0, 'text': 'Seleccionar una localidad' }];
    clues = [{ 'id': 0, 'text': 'Seleccionar una unidad de salud' }];
    colonias = [{ 'id': 0, 'text': 'Seleccionar una colonia' }];
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
                    //notificar('Información','Cargando unidad de salud','info',2000);
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
                   // notificar('Información','Cargando localidades','info',2000);
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

        /** COLONIAS */
        $.get('../catalogo/colonia?municipios_id='+$(this).val(), {}, function(response, status){
            while (colonias.length) { colonias.pop(); }                
            colonias.push({ 'id': 0, 'text': 'Seleccionar una colonia' });
            if(response.data==null){
                notificar('Sin resultados','warning',2000);
            } else {                                        
                if(response.data.length<=0){
                    notificar('Información','No existen colonias','warning',2000);
                } else {
                    //notificar('Información','Cargando colonias','info',2000);
                    $.each(response.data, function( i, cont ) {
                        colonias.push({ 'id': cont.id, 'text':  cont.nombre+' - '+cont.mun_nombre });
                    }); 
                } 
            } 
            $(".js-data-colonia").select2({
                language: "es",
                data:colonias
            });
        }).fail(function(){ 
            notificar('Información','Falló carga de colonias','danger',2000);
            $(".js-data-colonia").select2({
                language: "es",
                data:colonias
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
        $(".js-data-colonia").select2({
            language: "es",
            data: colonias
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
                    //notificar('Información','Cargando agebs','info',2000); 
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

/**
 * CONFIRMAR ELIMINACIÓN
 */
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

/**
 * TAB SEGUIMIENTO
 */
$(".js-ajax-seguimiento").click(function(e){
    e.preventDefault();
    $("#contenido").empty();        
    var dato = $("#form-seguimiento").serialize();
    $(".no-resultados").empty().html('<i class="fa fa-circle-o-notch fa-spin"></i> Buscando');
    $.get('persona/reporte/seguimiento', dato, function(response, status){       
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
            $(".no-resultados").empty();
        } else { 
            $(".no-resultados").empty();                  
            if(response.data.length<=0){
                notificar('Información','No existen resultados','warning',2000);
                $(".no-resultados").empty();
            } else {
                data_seguimiento = response.data;
                texto = response.text;
                usuario = response.user;
                $(".no-resultados").empty().html('<button type="button" onClick="descargarSeguimientos()" class="btn btn-info btn-lg"> <i class="fa fa-file-pdf-o"></i> Seguimientos</button> <span style="padding-left:40px; font-size:large;">'+response.data.length+' Resultados</span>'); 
                $("#contenido").empty();
                $.each(response.data, function( i, cont ) {
                    var icono = '';
                    if (cont.loc_nombre==null) {
                        cont.loc_nombre='';
                    }
                    if (cont.mun_nombre==null) {
                        cont.mun_nombre='';
                    }
                    if (cont.col_nombre==null) {
                        cont.col_nombre='';
                    }
                    if(cont.genero=='M'){
                        icono = '<i class="fa fa-male" style="color:#4d81bf; font-size:x-large;"></i>';
                    }
                    if(cont.genero=='F'){
                        icono = '<i class="fa fa-female" style="color:#ed1586; font-size:x-large;"></i>';
                    }
                    $("#contenido").append('<div class="row '+cont.id+'" data-toggle="tooltip" data-placement="top" data-original-title="dfsfsdfsdf'+cont.usuario_id+' / '+cont.created_at+'"><div class="col-md-1" id="'+cont.id+'" data-id="'+cont.id+'" data-nombre="'+cont.nombre+' '+cont.apellido_paterno+' '+cont.apellido_materno+'"><button type="button" class="btn btn-danger btn-delete" style="font-size:large;" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button></div> <div class="col-md-11"><a href="persona/'+cont.id+'"> <div class="mail_list"> <div class="right">  <h3> '+icono+' - '+cont.apellido_paterno+' '+cont.apellido_materno+' '+cont.nombre+' | <span style="color:tomato;">  '+cont.curp+'</span> | <span style="color:gray; font-weight:normal;"> TUTOR: '+cont.tutor+'</span> <small>'+cont.fecha_nacimiento+'</small></h3> <p> <span style="color:#428bca; font-weight:bold;">  '+cont.clu_clues+' - </span> <span style="color:#317d79; padding-right:20px;">  '+cont.clu_nombre+' - </span>  '+cont.calle+' '+cont.numero+', '+cont.col_nombre+', '+cont.loc_nombre+', '+cont.mun_nombre+' </p>  </div> </div> </a></div></div>');
                });
            }  
        }
    }).fail(function(){ 
        notificar('Información','Falló la búsqueda','danger',2000);
        $(".no-resultados").empty();
    });
});
function descargarSeguimientos() {
    var dosiss   = [];
    var vac = [];
    var awd = [];
    var wd = 0;
    var body = [];       
    if(data_seguimiento[0]){
        wd = Math.round(100/data_seguimiento[0].seguimientos.length);
        if(data_seguimiento[0].seguimientos.length>0){                 
            $.each(data_seguimiento[0].seguimientos, function( index, seg ) {                     
                awd.push(wd+'%');
                dosiss.push({'text': ''+seg.tipo_aplicacion, 'color':'black', 'bold': true, 'alignment':'center', 'fontSize':'8'});                      
                if(index==0){         
                    var tdv = 0; 
                    $.each(data_seguimiento[0].seguimientos, function( ind, s ) {  
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
                    if(data_seguimiento[0].seguimientos[(index-1)].vacunas_id!=seg.vacunas_id){     
                        var tdv = 0; 
                        $.each(data_seguimiento[0].seguimientos, function( ind, s ) {  
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
    $.each(data_seguimiento, function( indice, row ) { 
        var data_row = [];           
        if(indice==0){
            body.push([{'text': row.mun_nombre, 'color':'black','fillColor':'#E0E0E0', 'bold':true, 'colSpan':7}]);
            body.push([{'text': row.clu_clues+' - '+row.clu_nombre, 'color':'black', 'fillColor': '#F0F0F0', 'marginLeft':10, 'colSpan':7}]);
        } else {            
            if(row.municipios_id!=data_seguimiento[indice - 1].municipios_id){ //  municipio diferente
                body.push([{'text': row.mun_nombre, 'color':'black','fillColor':'#E0E0E0', 'bold':true, 'colSpan':7}]);
            }
            if(row.clues_id!=data_seguimiento[indice - 1].clues_id){
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
                { image: logo_ccvms, width: 85 },
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
                                { image: logo_ccvms, width: 40 }, 
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
                { text: texto, alignment: 'left' },
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
                        'text': ''+data_seguimiento.length, alignment: 'left', width: '80%', bold:true, fontSize:12, color: 'black', marginTop:20
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

/**
 * TAB SEGUIMIENTO
 */
$(".js-ajax-actividad").click(function(e){
    e.preventDefault();
    $("#contenido").empty();        
    var dato = $("#form-actividad").serialize(); 
    
        $(".no-resultados").empty().html('<i class="fa fa-circle-o-notch fa-spin"></i> Buscando');
        $.get('persona/reporte/actividad', dato, function(response, status){       
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
            $(".no-resultados").empty();
        } else { 
            $(".no-resultados").empty();                  
            if(response.data.length<=0){
                notificar('Información','No existen resultados','warning',2000);
                $(".no-resultados").empty();
            } else {
                data_actividad = response.data;
                texto = response.text;
                usuario = response.user;
                //$(".no-resultados").empty().html('<button type="button" onClick="descargarActividades()" class="btn btn-info btn-lg"> <i class="fa fa-file-pdf-o"></i> Actividades</button> <span style="padding-left:40px; font-size:large;">'+response.data.length+' Resultados</span>'); 
                $("#contenido").empty();  
                var tabla = '';    
                tabla+= '<table id="data-table" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">';  // Inicio de tabla 
                
                /*tabla+= '<tr><th rowspan="3">EDAD</th><th rowspan="2" colspan="3">POBLACIÓN</th><th colspan="'+col_general+'"> DOSIS APLICADAS</th></tr>';
                tabla+= '<tr>'+encabezado+'</tr>';
                tabla+= '<tr><th>Oficial</th><th>Nominal</th><th>% Conc.</th>'+encabezado2+'</tr>';
                */
                var total_aplicaciones = 0;
                var fila2 = '', fila3 = '';
                $.each(data_actividad[0].dosis, function( d, dosis ) {
                    fila2+='<th colspan="'+dosis.aplicacion.length+'">'+dosis.clave+'</th>';
                    total_aplicaciones+=dosis.aplicacion.length;
                    $.each(dosis.aplicacion, function( ap, aplicacion ) {
                        fila3+= '<th>'+ta_abreviatura[aplicacion]+'</th>';   
                    });
                });
                tabla+= '<tr><th rowspan="3">EDAD</th><th rowspan="2" colspan="3">POBLACIÓN</th><th colspan="'+total_aplicaciones+'"> DOSIS APLICADAS</th><th rowspan="2" colspan="3">ESQUEMAS COMPLETOS</th></tr>';
                tabla+= '<tr>'+fila2+'</tr>';
                tabla+= '<tr><th>Oficial</th><th>Nominal</th><th>% Conc.</th>'+fila3+'<th>Total</th><th>% Of.</th><th>% Nom.</th></tr>';
                $.each(data_actividad, function( i, cont ) { 
                    tabla+= '<tr>';
                    var concidencia = Math.round((100/cont.poblacion.oficial) * cont.poblacion.nominal);
                    var nominal_ec = Math.round((100/cont.poblacion.nominal) * cont.esquema_completo);
                    if(isNaN(nominal_ec)) {
                        nominal_ec = 0;
                    }
                    var oficial_ec = Math.round((100/ cont.poblacion.oficial) * cont.esquema_completo);
                    if(isNaN(oficial_ec)) {
                        oficial_ec = 0;
                    }
                    concidencia=parseFloat(concidencia).toFixed(2);
                    nominal_ec=parseFloat(nominal_ec).toFixed(2);
                    oficial_ec=parseFloat(oficial_ec).toFixed(2);
                    tabla+= '<td>'+cont.parametros.edad+'</td><td>'+Math.round(cont.poblacion.oficial)+'</td><td>'+cont.poblacion.nominal+'</td><td>'+concidencia+'</td>';
                    //$.each(cont.dosis, function( d, dosis ) {
                        $.each(cont.da, function( ap, apli ) {
                            tabla+='<td>'+apli+'</td>'; 
                        }); 
                    //}); 
                    tabla+= '<td>'+cont.esquema_completo+'</td><td>'+oficial_ec+'</td><td>'+nominal_ec+'</td>';
                    tabla+= '</tr>';
                });
                tabla+='</table>';
                $("#contenido").html(tabla);
            }  
        }
        }).fail(function(){ 
            notificar('Información','Falló la búsqueda','danger',2000);
            $(".no-resultados").empty();
        });

        

});

