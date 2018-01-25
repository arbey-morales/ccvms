// MASCARA TIPO DD-MM-AAAA
$("#fecha_nacimiento_tutor,#fecha_nacimiento").mask("99-99-9999");
// GUARADARÁ EL ESQUEMA SELECCIONADO
var ultimo_esquema = ''; 
var ultima_fecha_nacimiento = '';
// EQUIVALENCIA DE CLAVES ESTADOS
var estados_equivalencia = ["X","AS","BC","BS","CC","CL","CM","CS","CH","DF","DG","GT","GR","HG","JC","MC","MN","MS","NT","NL","OC","PL","QT","QR","SP","SL","SR","TC","TS","TL","VZ","YN","ZS"];
var clues = [{ 'id': 0, 'clues':'', 'text': '* Unidad de salud' }];
var clue_actual = {municipios_id:0, localidades_id:0};
var municipios = [{ 'id': 0, 'text': '* Municipio/domicilio' }];
var estados = [{ 'id': 0, 'text': '* Entidad federativa de nacimiento' }];
var localidades = [{ 'id': 0, 'text': '* Localidad/domicilio' }];
var colonias = [{ 'id': 0, 'text': 'Sin colonia' }];
var agebs = [{ 'id': 0, 'text': 'AGEB' }];
var afiliaciones = [{ 'id': 0, 'text': 'Ninguna afiliación' }];
var codigos = [{ 'id': 0, 'text': 'Ningún código' }];
var tipos_partos = [{ 'id': 0, 'text': '* Tipo de parto' }];

// CARGA EL ESQUEMA CON BASE A LA FECHA 01-01-AÑO ACTUAL
setTimeout(function() {          
    conseguirEsquema(moment().format('YYYY'),"01-01-"+moment().format('YYYY'));
}, 500);

$("#personas-form").submit(function(e){
    e.preventDefault();
    $.post($(this).attr('action'),$(this).serialize(), function(response, status){ // Envía formulario
        if(status=='success'){
            notificar(response.titulo,response.texto,response.estatus,5000);
            if(response.estatus=='success'){
                var clue_id = $(".js-data-clue").val();
                console.log(clue_id)
                verClue(clues[clue_id]);
                $("#nombre,#paterno,#materno,#fecha_nacimiento,#curp,#sector,#manzana,#descripcion_domicilio,#calle,#numero,#codigo_postal,#fecha_nacimiento_tutor,#tutor").val('');
                conseguirEsquema(moment().format('YYYY'),"01-01-"+moment().format('YYYY'));
                $("#paterno").focus();
            }
        } else {
            notificar('Error','Error en el servidor','No se guardó el registro','error',3000);
        }
    }).fail(function(jqXhr){ 
        notificar('Información','No se guardó el registro verifique los datos o recargue la página','error',4000);
        if( jqXhr.status === 422 ) {
            var errors = jqXhr.responseJSON;
            var msg = '';
            $.each( errors , function( key, value ) {
                msg = msg +'- '+value[0]+'\n';
            });
            notificar('Información',msg,'error',2000);
        }
    });
});

// INICIA SELECT2 PARA ESTOS SELECTORES
$(".js-data-genero").select2();

// SI CAMBIAN ESTOS SELECTS VALIDAR LOS CAMPOS DE ENTRADA PARA VALIDAR CURP
$(".js-data-estado,.js-data-genero").change(function(){
    setTimeout(function(){ validarCamposCURP(); }, 1000);
});
$("input#curp").change(function(){
    curpRepetida(curp = $(this).val());
});

function curpRepetida(curp){
    //console.log(curp.length)
    $.get('../persona/curp-repetida?curp='+curp, {}, function(response, status){
        if(response.data.length>0){
            $("input#curp").focus();
            var p = response.data;
            notificar('Información','* Verifique la CURP <strong>'+curp+'</strong> \n \n  - Datos: '+p[0].nombre+' '+p[0].apellido_paterno+' '+p[0].apellido_materno+' \n - Agregad@ el '+p[0].created_at,'error',8000);
        } else {
            //console.log(55)
        }
    }).fail(function(){ 
        notificar('Información','Falló comprobación de CURP','danger',2000);
    });
}

// OBTIENE VALUE DE FECHA DE APLICACIÓN Y SE ENVÍA A VALIDACIÓN
function validaAplicacion(id_vacuna_esquema, index){ //id_esquema y key del arreglo en js
    if($("#fecha_aplicacion"+id_vacuna_esquema).val()!="" && $("#fecha_aplicacion"+id_vacuna_esquema).val()!="__-__-____" && $("#fecha_aplicacion"+id_vacuna_esquema).val()!=null){
        if (moment($("#fecha_aplicacion"+id_vacuna_esquema).val(),'DD-MM-YYYY',true).isValid()) {
            comprobarFecha($("#fecha_aplicacion"+id_vacuna_esquema).val(), $("#fecha_aplicacion"+id_vacuna_esquema).attr('data-placeholder'), 3, index);
            /* @params: (fecha de aplicaión, texto que describe la aplicación, 3 = pertenece a aplicaciones, index del arreglo de vacunas esquemas )*/
        } else {
            notificar('Información','Verifique la fecha de aplicación de '+$("#fecha_aplicacion"+id_vacuna_esquema).attr('data-placeholder'),'info',3000);
            $("#fecha_aplicacion"+id_vacuna_esquema).focus();
        } 
    }           
}
/*
function iniciarClue(){
    $('.js-data-clue').empty();
    clues = [{ 'id': 0, 'text': '* Unidad de salud' }];
    $.get('../catalogo/clue', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {      
            while (clues.length) { clues.pop(); }                
            clues.push({ 'id': 0, 'text': '* Unidad de salud' });           
            if(response.data.length<=0){
                notificar('Información','No existen unidades de salud','warning',2000);
            } else {
                notificar('Información','Cargando unidades de salud','info',2000);
                $('.js-data-clue').empty();                      
                $.each(response.data, function( i, cont ) {
                    clues.push({ 'id': cont.id, 'text': cont.clues+' - '+cont.nombre });
                });  
            }  
            $(".js-data-clue").select2({
                language: "es",
                data: clues
            });
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de unidades de salud','danger',2000);
        $(".js-data-clue").select2({
            language: "es",
            data:clues
        }); 
    });
}*/

function iniciarMunicipio(){
    $('.js-data-municipio').empty();
    municipios = [{ 'id': 0, 'text': '* Municipio/domicilio' }];
    $.get('../catalogo/municipio', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {      
            while (municipios.length) { municipios.pop(); }                
            municipios.push({ 'id': 0, 'text': '* Municipio/domicilio' });           
            if(response.data.length<=0){
               // notificar('Información','No existen municipios','warning',2000);
            } else {
                $('.js-data-municipio').empty();                     
                $.each(response.data, function( i, cont ) {
                    municipios.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
                });  
            }  
            $(".js-data-municipio").select2({
                language: "es",
                data: municipios
            }); 
            
            $(".js-data-municipio").val(clue_actual.municipios_id).trigger("change");
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de municipios','danger',2000);
        $(".js-data-municipio").select2({
            language: "es",
            data:municipios
        }); 
    });
}

function iniciarEstado(){
    $('.js-data-estado').empty();
    estados = [{ 'id': 0, 'text': '* Entidad federativa de nacimiento' }];
    $.get('../catalogo/entidad', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {      
            while (estados.length) { estados.pop(); }                
            estados.push({ 'id': 0, 'text': '* Entidad federativa de nacimiento' });           
            if(response.data.length<=0){
               // notificar('Información','No existen municipios','warning',2000);
            } else {
                $('.js-data-estado').empty();                     
                $.each(response.data, function( i, cont ) {
                    estados.push({ 'id': cont.id, 'text': cont.nombre });
                });  
            }  
            $(".js-data-estado").select2({
                language: "es",
                data: estados
            }); 
            
            $(".js-data-estado").val(7).trigger("change");
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de estados','danger',2000);
        $(".js-data-estado").select2({
            language: "es",
            data:estados
        }); 
    });
}
function iniciarCodigo(){
    $('.js-data-codigo').empty();
    codigos = [{ 'id': 0, 'text': '* Ningún código' }];
    $.get('../catalogo/codigo', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {      
            while (codigos.length) { codigos.pop(); }                
            codigos.push({ 'id': 0, 'text': '* Ningún código' });           
            if(response.data.length<=0){
               // notificar('Información','No existen municipios','warning',2000);
            } else {
                $('.js-data-codigo').empty();                     
                $.each(response.data, function( i, cont ) {
                    codigos.push({ 'id': cont.id, 'text': cont.clave+' - '+cont.nombre });
                });  
            }  
            $(".js-data-codigo").select2({
                language: "es",
                data: codigos
            }); 
            
            $(".js-data-codigo").val(0).trigger("change");
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de codigos','danger',2000);
        $(".js-data-codigo").select2({
            language: "es",
            data:codigos
        }); 
    });
}
function iniciarTipoParto(){
    $('.js-data-parto').empty();
    tipos_partos = [{ 'id': 0, 'text': '* Tipos parto' }];
    $.get('../catalogo/tipo-parto', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {      
            while (tipos_partos.length) { tipos_partos.pop(); }                
            tipos_partos.push({ 'id': 0, 'text': ' * Tipos parto' });           
            if(response.data.length<=0){
               // notificar('Información','No existen municipios','warning',2000);
            } else {
                $('.js-data-parto').empty();                     
                $.each(response.data, function( i, cont ) {
                    tipos_partos.push({ 'id': cont.id, 'text': cont.clave+' - '+cont.descripcion });
                });  
            }  
            $(".js-data-parto").select2({
                language: "es",
                data: tipos_partos
            }); 
            
            $(".js-data-parto").val(1).trigger("change");
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de tipos de parto','danger',2000);
        $(".js-data-parto").select2({
            language: "es",
            data:tipos_partos
        }); 
    });
}
function iniciarAfiliacion(){
    $('.js-data-institucion').empty();
    afiliaciones = [{ 'id': 0, 'text': 'Ninguna afiliación' }];
    $.get('../catalogo/institucion', {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {      
            while (afiliaciones.length) { afiliaciones.pop(); }                
            afiliaciones.push({ 'id': 0, 'text': ' Ninguna afiliación' });           
            if(response.data.length<=0){
               // notificar('Información','No existen municipios','warning',2000);
            } else {
                $('.js-data-institucion').empty();                     
                $.each(response.data, function( i, cont ) {
                    afiliaciones.push({ 'id': cont.id, 'text': cont.clave+' - '+cont.nombre });
                });  
            }  
            $(".js-data-institucion").select2({
                language: "es",
                data: afiliaciones
            }); 
            
            $(".js-data-institucion").val(0).trigger("change");
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de afiliaciones','danger',2000);
        $(".js-data-institucion").select2({
            language: "es",
            data:afiliaciones
        }); 
    });
}

// CADA QUE SE COLOCA UNA FECHA DE  NACIMIENTO DEL INFANTE SE ENVÍA A VALIDACIÓN
$("#fecha_nacimiento").blur(function(){
    if (moment($(this).val(),'DD-MM-YYYY',true).isValid()) {
            comprobarFecha($(this).val(), $(this).attr('placeholder'), 1, null);
    } else {
        $(this).val(ultima_fecha_nacimiento);
        notificar('Información','Verifique la fecha de nacimiento del infante.\n \n Se cargará la última fecha valida '+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').format('LL'),'info',5000);
        $(this).focus();
    }            
});

// CADA QUE SE COLOCA UNA FECHA DE  DEL TUTOR SE ENVÍA A VALIDACIÓN
$("#fecha_nacimiento_tutor").blur(function(){
    if (moment($(this).val(),'DD-MM-YYYY',true).isValid()) {
            comprobarFecha($(this).val(), $(this).attr('placeholder'), 2, null);
    } else {
        $(this).val('');
    }            
});

// SI LA CLUE CAMBIA; SE SELECCIONAN SU LOCALIDAD Y MUNICIPIO
$(".js-data-clue").change(function(){
    $('.js-data-localidad').empty();
    $(".js-data-municipio").val(clue_actual.municipios_id).trigger("change");
    localidades = [{ 'id': 0, 'text': '* Localidad' }];
    var clue_id = $(this).val();
    if(clue_id==0){
        $(".js-data-localidad").select2({
            language: "es",
            data: localidades
        });
    } else {
        $.get('../catalogo/clue/'+clue_id, function(response, status){ // Consulta    
            verClue(response.data.clues+' - '+response.data.nombre);
            clue_actual = response.data;
            cargarLocalidad(clue_actual.municipios_id);
            $(".js-data-municipio").val(clue_actual.municipios_id).trigger("change");
        }).fail(function(){  // Calcula CURP
            notificar('Información','No se consultaron los detalles de la unidad de salud','warning',2000);
            $(".js-data-localidad").select2({
                language: "es",
                data: localidades
            });
        });
    }
});

$(".js-data-clue").select2({
    ajax: {
        url: "../catalogo/clue",
        dataType: 'json',
        delay: 250,
        data: function (params) {
        return {
            q: params.term, // search term
            page: params.page
        };
        },
        processResults: function (data, params) {            
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be used
        params.page = params.page || 1;

        return {
            results: $.map(data.data, function (item) {  // hace un  mapeo de la respuesta JSON para presentarlo en el select
                return {
                    id:        item.id,
                    clues:     item.clues,
                    text:      item.nombre
                }
            }),
            pagination: {
            more: (params.page * 30) < data.total_count
            }
        };
        },
        cache: true
    },
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 5,
    language: "es",
    placeholder: {
        id: clues[0].id, 
        clues: clues[0].clues,
        text: clues[0].text
    },
    cache: true,
    templateResult: formatRepo, // omitted for brevity, see the source of this page
    templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
});

function formatRepo (clues) {
    if (!clues.id) { return clues.text; }
    var $clues = $(
        '<span class="">' + clues.clues + ' - '+ clues.text +'</span>'
    );
    return $clues;
};
function formatRepoSelection (clues) {
    if (!clues.id) { return clues.text; }
    var $clues = $(
        '<span class="results-select2"> ' + clues.clues+ ' - '+ clues.text +'</span>'
    );
    return $clues;
};

$(".js-data-municipio").change(function(){
    if($('.js-data-municipio').val()==0){ } else {
        cargarLocalidad($('.js-data-municipio').val());
        cargarColonia($('.js-data-municipio').val());
    }
});

$(".js-data-localidad").change(function(){
    if($('.js-data-localidad').val()==0){ } else {
        cargarAgeb($('.js-data-localidad').val());
    }
});

function cargarAgeb(localidad){
    $('.js-data-ageb').empty();
    agebs = [{ 'id': 0, 'text': 'AGEB' }];
    $.get('../catalogo/ageb?localidades_id='+localidad, {}, function(response, status){
        if(response.data==null){
            //notificar('Sin resultados','warning',2000);
        } else {      
            while (agebs.length) { agebs.pop(); }                
            agebs.push({ 'id': 0, 'text': 'AGEB' });           
            if(response.data.length<=0){
                //notificar('Información','No existen AGEBS','warning',2000);
            } else {
                //notificar('Información','Cargando AGBES','info',2000);
                $('.js-data-ageb').empty();                      
                $.each(response.data, function( i, cont ) {
                    agebs.push({ 'id': cont.id, 'text':cont.id.substr(-4)+' : '+cont.localidad+' '+cont.municipio });
                });  
            }  
            $(".js-data-ageb").select2({
                language: "es",
                data: agebs
            });
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de AGEBS','danger',2000);
        $(".js-data-ageb").select2({
            language: "es",
            data:agebs
        }); 
    });
}

function cargarColonia(municipio){
    $('.js-data-colonia').empty();
    colonias = [{ 'id': 0, 'text': 'Sin colonia' }];
    $.get('../catalogo/colonia?municipios_id='+municipio, {}, function(response, status){
        if(response.data==null){
            //notificar('Sin resultados','warning',2000);
        } else {      
            while (colonias.length) { colonias.pop(); }                
            colonias.push({ 'id': 0, 'text': 'Sin colonia' });           
            if(response.data.length<=0){
                //notificar('Información','No existen colonias','warning',2000);
            } else {
                $('.js-data-colonia').empty();                      
                $.each(response.data, function( i, cont ) {
                    colonias.push({ 'id': cont.id, 'text': cont.nombre });
                });  
            }  
            $(".js-data-colonia").select2({
                language: "es",
                data: colonias
            });
            $(".js-data-colonia").val(clue_actual.localidades_id).trigger("change");
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de colonias','danger',2000);
        $(".js-data-colonia").select2({
            language: "es",
            data:colonias
        }); 
    });
}

function cargarLocalidad(municipio){
    $('.js-data-localidad').empty();
    localidades = [{ 'id': 0, 'text': '* Localidad' }];
    $.get('../catalogo/localidad?municipios_id='+municipio, {}, function(response, status){
        if(response.data==null){
            notificar('Sin resultados','warning',2000);
        } else {      
            while (localidades.length) { localidades.pop(); }                
            localidades.push({ 'id': 0, 'text': '* Localidad' });           
            if(response.data.length<=0){
                //notificar('Información','No existen localidades','warning',2000);
            } else {
                $('.js-data-localidad').empty();                      
                $.each(response.data, function( i, cont ) {
                    localidades.push({ 'id': cont.id, 'text': cont.clave+' - '+cont.nombre });
                });  
            }  
            $(".js-data-localidad").select2({
                language: "es",
                data: localidades
            });
            $(".js-data-localidad").val(clue_actual.localidades_id).trigger("change");
        }
    }).fail(function(){ 
        notificar('Información','Falló carga de localidades','danger',2000);
        $(".js-data-localidad").select2({
            language: "es",
            data:localidades
        }); 
    });
}

$(document).ready(function(){
    /*$(".js-data-clue").select2({
        language: "es",
        data: clues
    });*/
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
    $(".js-data-colonia").select2({
        language: "es",
        data: colonias
    });
    $(".js-data-institucion").select2({
        language: "es",
        data: afiliaciones
    });
    $(".js-data-codigo").select2({
        language: "es",
        data: codigos
    });
    $(".js-data-parto").select2({
        language: "es",
        data: tipos_partos
    });

    var clue_id = $(".js-data-clue").val();
    //iniciarClue();  
    iniciarMunicipio(); 
    iniciarEstado();  
    iniciarCodigo();
    iniciarTipoParto();  
    iniciarAfiliacion(); 
});

function verClue(clue){
    $("#clue-text").empty().html(clue);
    notificar('<strong>UNIDAD DE SALUD:</strong> \n ',clue,'info',6000);
}

// CADA QUE ESTOS ELEMENTOS PIERDEN EL FOCO, SE VALIDAN PARA CONSULTAR LA CURP
$("#fecha_nacimiento,#paterno,#materno,#nombre").blur(function(){            
    setTimeout(function(){ validarCamposCURP(); }, 1000);
});

// SE ENCARGA DE VALIDAR EL FORMATO Y LA EXISTENCIA DE LA FECHA PROPORCIONADA COMO PARAMETRO
function comprobarFecha(fecha,texto,tipo_fecha,index){               
    var errors = 0;
    var mensaje = '';
    var titulo = '';            
    if(tipo_fecha==1){ // FECHA DE NACIMENTO 
        var temp = fecha.split("-"); // fecha recibida partida 
        if (moment(fecha,'DD-MM-YYYY') > moment())  {                          
            errors++; mensaje='No puedes agregar niños nacidos el <strong style="text-transform: uppercase;">'+moment(fecha,'DD-MM-YYYY').format('LL')+'</strong>, Ellos aún no nacen! \n \n Se cargará la última fecha valida <strong style="text-transform: uppercase;">'+moment(ultima_fecha_nacimiento,'DD-MM-YYYY').format('LL')+'</strong>';
            fecha = ultima_fecha_nacimiento;
            temp = fecha.split("-");
        }
        if(fecha!=ultima_fecha_nacimiento){
            conseguirEsquema(temp[2],fecha);
        }
    }
    if(tipo_fecha==2){ // FECHA DE NACIMENTO DEL TUTOR 
        if (moment(fecha,'DD-MM-YYYY') >= moment() || moment(fecha,'DD-MM-YYYY') >= moment(ultima_fecha_nacimiento,'DD-MM-YYYY'))  {                          
            errors++; mensaje='El tutor <strong style="text-transform: uppercase;">debe ser mayor al infante y menor al día de hoy</strong> '+$("#nombre").val();
        }
    }
    if(tipo_fecha==3){ // APLICACIONES-DOSIS
        if (moment(ultima_fecha_nacimiento,'DD-MM-YYYY',true).isValid()) {
            if(moment(fecha,'DD-MM-YYYY')>= moment(ultima_fecha_nacimiento,'DD-MM-YYYY') && moment(fecha,'DD-MM-YYYY') <= moment()){ // SI ES MAYOR QUE EL MACIMIENTO Y MENOR A MAÑANA
                if (ultimo_esquema[index]) { // sabemos que tiene un esquema y tiene datos a validar
                    var aplicacion_actual = ultimo_esquema[index];
                    /*** EDAD IDEAL ***/                      
                    var fecha_ideal_con_dias  = moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add(aplicacion_actual.edad_ideal_dia, 'days');
                    var fecha_ideal_con_meses = fecha_ideal_con_dias.add(aplicacion_actual.edad_ideal_mes, 'months');
                    var fecha_ideal_real      = fecha_ideal_con_meses.add(aplicacion_actual.edad_ideal_anio, 'years');
                    /*** LIMITE SUPERIOR ***/
                    var fecha_sup_con_dias    = moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add(aplicacion_actual.intervalo_fin_dia, 'days');
                    var fecha_sup_con_meses   = fecha_sup_con_dias.add(aplicacion_actual.intervalo_fin_mes, 'months');
                    var fecha_sup_real        = fecha_sup_con_meses.add(aplicacion_actual.intervalo_fin_anio, 'years');
                    /*** LIMITE INFERIOR ***/
                    var fecha_inf_con_dias    = moment(ultima_fecha_nacimiento,'DD-MM-YYYY').add(aplicacion_actual.intervalo_inicio_dia, 'days');
                    var fecha_inf_con_meses   = fecha_inf_con_dias.add(aplicacion_actual.intervalo_inicio_mes, 'months');
                    var fecha_inf_con_anios   = fecha_inf_con_meses.add(aplicacion_actual.intervalo_inicio_anio, 'years');
                    var fecha_inf_real        = fecha_inf_con_anios.subtract(aplicacion_actual.margen_anticipacion, 'days');

                    var mayor = []; var menor = [];                               
                    if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES   
                        mayor = aplicacion_actual.mayores[0];
                    }                    

                    if(aplicacion_actual.menores.length){ // SI TIENE DOSIS MENORES                                     
                        menor = aplicacion_actual.menores[0];
                    }

                    if(aplicacion_actual.menores.length){ // Tiene dosis menores y hay que ver si son ideales
                        var menores_bien = true;
                        var id_primera = null;
                        var menor_siguiente = menor.id;
                        var menor_mensaje = ' ';
                        if(aplicacion_actual.menores.length){ // SI TIENE DOSIS MAYORES VERIFICAR QUE ESTEN BIEN                                     
                            id_primera = aplicacion_actual.menores[(aplicacion_actual.menores.length - 1)].id;
                            $.each(aplicacion_actual.menores, function( ins, apl ) {                                          
                                menor_siguiente = apl.id;
                                menor_mensaje = '<strong style="text-transform: uppercase;">'+tipoAplicacion(apl.tipo_aplicacion)+'</strong>, ';
                                if($("#fecha_aplicacion"+apl.id).val()!="" && $("#fecha_aplicacion"+apl.id).val()!="__-__-____" && $("#fecha_aplicacion"+apl.id).val()!=null){
                                    if (moment($("#fecha_aplicacion"+apl.id).val(),'DD-MM-YYYY',true).isValid()) {
                                    } else { 
                                        menores_bien = false;                                               
                                        return false;
                                    }
                                } else {
                                    menores_bien = false;
                                    return false;
                                }                                 
                            });                            
                        }                                

                        if(menores_bien) {
                            var primera = []; // de aquí podemos sacar si la primera es ideal o no
                            $.each(ultimo_esquema, function( k, apl ) {
                                if(id_primera==apl.id){
                                    primera = apl;
                                    return false;
                                }                                        
                            });
                                                                
                            /*** LIMITE INFERIOR INTERVALOS ***/
                            var fecha_intervalo_inf_con_dias    = moment($("#fecha_aplicacion"+menor.id).val(),'DD-MM-YYYY').add(menor.entre_siguiente_dosis_dia, 'days');
                            var fecha_intervalo_inf_con_meses   = fecha_intervalo_inf_con_dias.add(menor.entre_siguiente_dosis_mes, 'months');
                            var fecha_intervalo_inf_con_anios   = fecha_intervalo_inf_con_meses.add(menor.entre_siguiente_dosis_anio, 'years');
                            var fecha_intervalo_inf_real        = fecha_intervalo_inf_con_anios.subtract(aplicacion_actual.margen_anticipacion, 'days');
                            // menor.es_ideal PARA VER SI LA APLICACIÓN MENOR ES IDEAL
                            // primera.es_ideal PARA VER SI LA PRIMERA APLICACIÓN DE LA VACUNA ES IDEAL
                            if(primera.es_ideal) { // la dosis anterior es ideal, validar conforme a rango de dosis actual                                        
                                if(moment(fecha,'DD-MM-YYYY') < fecha_ideal_real) { // Si la fecha que pusimos es ideal
                                    ultimo_esquema[index].es_ideal = true; 
                                    cambiaEtiqueta(aplicacion_actual.id,aplicacion_actual.etiqueta_ideal_anio,aplicacion_actual.etiqueta_ideal_mes,aplicacion_actual.etiqueta_ideal_dia);
                                    if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES
                                        cambiaEtiqueta(mayor.id,mayor.etiqueta_ideal_anio,mayor.etiqueta_ideal_mes,mayor.etiqueta_ideal_dia);
                                    }                           
                                    if(moment(fecha,'DD-MM-YYYY') >= fecha_inf_real && moment(fecha,'DD-MM-YYYY') <=  fecha_sup_real) {                            
                                        // Si la fecha es valida                               
                                    } else { 
                                        errors++; mensaje='Se puede aplicar desde: <strong style="text-transform: uppercase;">'+fecha_inf_real.format('LL')+'</strong> hasta el <strong style="text-transform: uppercase;">'+fecha_sup_real.format('LL')+'</strong>';
                                    }
                                } else { // LA DOSIS ACTUAL Y SIGUIENTE HAY QUE MODIFICARLA
                                    ultimo_esquema[index].es_ideal = false;
                                    cambiaEtiqueta(aplicacion_actual.id,aplicacion_actual.etiqueta_no_ideal_anio,aplicacion_actual.etiqueta_no_ideal_mes,aplicacion_actual.etiqueta_no_ideal_dia);
                                    if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES
                                        cambiaEtiqueta(mayor.id,mayor.etiqueta_no_ideal_anio,mayor.etiqueta_no_ideal_mes,mayor.etiqueta_no_ideal_dia);
                                    }
                                    if(moment(fecha,'DD-MM-YYYY') >= fecha_intervalo_inf_real && moment(fecha,'DD-MM-YYYY') <=  fecha_sup_real) {
                                    } else {
                                        errors++; mensaje='Se puede aplicar desde: <strong style="text-transform: uppercase;">'+fecha_intervalo_inf_real.format('LL')+'</strong> hasta el <strong style="text-transform: uppercase;">'+fecha_sup_real.format('LL')+'</strong>';
                                    }
                                }
                            } else { // la dosis anterior NO es ideal, validar conforme intervalo establecido
                                ultimo_esquema[index].es_ideal = false;
                                cambiaEtiqueta(aplicacion_actual.id,aplicacion_actual.etiqueta_no_ideal_anio,aplicacion_actual.etiqueta_no_ideal_mes,aplicacion_actual.etiqueta_no_ideal_dia);
                                if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES
                                    cambiaEtiqueta(mayor.id,mayor.etiqueta_no_ideal_anio,mayor.etiqueta_no_ideal_mes,mayor.etiqueta_no_ideal_dia);
                                }
                                if(moment(fecha,'DD-MM-YYYY') >= fecha_intervalo_inf_real && moment(fecha,'DD-MM-YYYY') <=  fecha_sup_real) {
                                } else {
                                    errors++; mensaje='Se puede aplicar desde: <strong style="text-transform: uppercase;">'+fecha_intervalo_inf_real.format('LL')+'</strong> hasta el <strong style="text-transform: uppercase;">'+fecha_sup_real.format('LL')+'</strong>';
                                }
                            }  
                        } else { // No tiene fecha de dosis anterior
                            errors++; mensaje='Debe agregar la fecha de aplicación para '+menor_mensaje+' de '+aplicacion_actual.clave;
                            $("#fecha_aplicacion"+menor_siguiente).focus();
                            
                        }
                    } else { // Significa que es la primera aplicación de la vacuna    
                        if(moment(fecha,'DD-MM-YYYY') < fecha_ideal_real) { // Es ideal
                            ultimo_esquema[index].es_ideal = true;
                            // ES IDEAL: es decir la aplicación es antes de la edad máxima ideal, por lo tanto no se modifican la dosis siguientes 
                            cambiaEtiqueta(aplicacion_actual.id,aplicacion_actual.etiqueta_ideal_anio,aplicacion_actual.etiqueta_ideal_mes,aplicacion_actual.etiqueta_ideal_dia);
                            if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES
                                cambiaEtiqueta(mayor.id,mayor.etiqueta_ideal_anio,mayor.etiqueta_ideal_mes,mayor.etiqueta_ideal_dia);
                            }
                        } else { 
                            // No tiene menores y NO ES IDEAL
                            ultimo_esquema[index].es_ideal = false;
                            cambiaEtiqueta(aplicacion_actual.id,aplicacion_actual.etiqueta_no_ideal_anio,aplicacion_actual.etiqueta_no_ideal_mes,aplicacion_actual.etiqueta_no_ideal_dia);
                            if(aplicacion_actual.mayores.length){ // SI TIENE DOSIS MAYORES
                                cambiaEtiqueta(mayor.id,mayor.etiqueta_no_ideal_anio,mayor.etiqueta_no_ideal_mes,mayor.etiqueta_no_ideal_dia);
                            }
                        }

                        // Como no tiene menores se evalua con el mismo rango                             
                        if(moment(fecha,'DD-MM-YYYY') >= fecha_inf_real  && moment(fecha,'DD-MM-YYYY') <=  fecha_sup_real) {                            
                            // Si la fecha es valida                               
                        } else { 
                            errors++; mensaje='Se puede aplicar desde: <strong style="text-transform: uppercase;">'+fecha_inf_real.format('LL')+'</strong> hasta el <strong style="text-transform: uppercase;">'+fecha_sup_real.format('LL')+'</strong>';
                        }  
                    }                                                                                 
                } else {
                    errors++; mensaje='No se encontraron los detalles de la dosis a aplicar';
                }
            } else {
                errors++; mensaje='La fecha <strong style="text-transform: uppercase;">'+moment(fecha,'DD-MM-YYYY').format('LL')+'</strong>, que acaba de agregar debe ser mayor o igual a la fecha de nacimiento y menor al día de mañana';
                setTimeout(function() {          
                    $("#fecha_aplicacion"+ultimo_esquema[index].id).focus();
                }, 50);        
            }
        } else {
            $("input[name*='fecha_aplicacion']").val('');
            $("#fecha_nacimiento").focus();
            errors++; mensaje='Seleccione una fecha de nacimiento primero';
        }
        
    }
    if(errors>0){
        notificar(texto , mensaje, 'danger', 5000);
    }
}

// CAMBIA ETIQUETA
function cambiaEtiqueta(id,anio,mes,dia){
    $("#intervalo_text"+id).html(obtieneIntervalo(anio,mes,dia));
}

// CONSULTA POR :GET: EL ESQUEMA
function conseguirEsquema(esquema,fecha_nacimiento) {
    $('#title-esquema').empty().html('Buscando esquema '+esquema);
    $('#content-esquema').empty().html('<div class="col-md-12 text-center"> <i class="fa fa-circle-o-notch fa-spin" style="font-size:x-large;"></i> </div> ');
    $.get('../catalogo/esquema/'+esquema, {fecha_nacimiento:fecha_nacimiento}, function(response, status){ // Consulta esquema
        if(response.data==null){
            $('#fecha_nacimiento').val('');
            notificar('Información','No se encontró el esquema que buscas','warning',4000);
            $('#title-esquema').empty().html('No se encuentra el esquema: '+esquema+'. ');
            $('#content-esquema').empty().html('<div class="col-md-12 text-center text-danger"> <h2 class="text-danger"> <i class="fa fa-info-circle text-info"></i> Imposible encontrar el esquema '+esquema+'. Seleccione otra fecha de nacimiento o asegurese que exista el esquema que busca. </h2></div> ');
        } else {                    
            if(response.data.length<=0){
                notificar('Información','Al esquema no se le han programado aplicaciones, verifique!','warning',4000);
                $('#content-esquema').empty().html('Sin aplicaciones programadas, verifique!');
            } else {
                notificar('Información','Cargando esquema','info',2000);
                $('#content-esquema').empty();
            }  
            $('#title-esquema').empty().html('<a class="btn btn-danger btn-lg"><i class="fa fa-calendar"></i> '+response.esquema.descripcion+'</a>  <a class="btn btn-lg btn-default">'+response.letra_edad+'</a>');
            $('#fecha_nacimiento').val(fecha_nacimiento);
            ultima_fecha_nacimiento = fecha_nacimiento;
            generarEsquema(response.data);
        }
    }).fail(function(){ 
        $('#fecha_nacimiento').val('');                  
        $('#title-esquema').empty().html('No se encuentra el esquema: '+esquema+'. ');
        $('#content-esquema').empty().html('<div class="col-md-12 text-center text-danger"> <h2 class="text-danger"> <i class="fa fa-info-circle text-info"></i> Imposible encontrar el esquema '+esquema+'. Seleccione otra fecha de nacimiento o asegurese que exista el esquema que busca. </h2></div> ');
    });
}

// GENERA EL ESQUEMA DENTRO DEL DIV-CONTENT
function generarEsquema(aplicaciones){
    ultimo_esquema = aplicaciones; // LAS VALIDACIONES DEL ESQUEMA ESTÁN AQUÍ
    var key_plus = 0;
    $.each(aplicaciones, function( key, ve ) {
        key_plus++;
        var placeholder = '';
        if(ve.etiqueta_ideal<30){
            placeholder = ultima_fecha_nacimiento;
        }
        //console.log(ve.clave+' '+ve.draw)
        ultimo_esquema[key].es_ideal = false;         
        if(aplicaciones.length - 1 > key){ // último registro de esquemasvacunas
            if(ve.draw){
                $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #'+ve.color_rgb+'; background-color:#'+ve.color_rgb+' !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipoAplicacion(ve.tipo_aplicacion)+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#'+ve.color_rgb+'" id="intervalo_text'+ve.id+'">'+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+'</span> </span> </div> </div> <div class="row"> <div class="bt-flabels__wrapper"> <input id="fecha_aplicacion'+ve.id+'" name="fecha_aplicacion'+ve.id+'" type="text" onBlur="validaAplicacion('+ve.id+','+key+')" placeholder="'+placeholder+'" data-placeholder="'+ve.clave+' '+tipoAplicacion(ve.tipo_aplicacion)+'('+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+')" value="" class="form-control has-feedback-left" aria-describedby="inputSuccess2Status" autocomplete="off" style="font-size:x-large; text-align:center;"> </div> </div> </div> </div>');
            } else {
                $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:#D8D8D8; margin:0px; padding:3px; border:solid 2px #F0F0F0; background-color:#F0F0F0 !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipoAplicacion(ve.tipo_aplicacion)+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#D8D8D8" id="intervalo_text'+ve.id+'">'+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+'</span> </span> </div> </div> <div class="row" style="text-align:center;"> <i class="fa fa-clock-o" style="color:white; font-size:50px ;"></i>  </div> </div> </div>');
            }
            if(aplicaciones[key_plus].fila != ve.fila){
                $('#content-esquema').append('<div class="clearfix"></div>');
            }
        } else {
            if(ve.draw){
                $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #'+ve.color_rgb+'; background-color:#'+ve.color_rgb+' !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipoAplicacion(ve.tipo_aplicacion)+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#'+ve.color_rgb+'" id="intervalo_text'+ve.id+'">'+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+'</span> </span> </div> </div> <div class="row"> <div class="bt-flabels__wrapper"> <input id="fecha_aplicacion'+ve.id+'" name="fecha_aplicacion'+ve.id+'" type="text" onBlur="validaAplicacion('+ve.id+','+key+')" placeholder="'+placeholder+'" data-placeholder="'+ve.clave+' '+tipoAplicacion(ve.tipo_aplicacion)+'('+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+')" value="" class="form-control has-feedback-left" aria-describedby="inputSuccess2Status" autocomplete="off" style="font-size:x-large; text-align:center;"> </div> </div> </div> </div>');
            } else {
                $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:#D8D8D8; margin:0px; padding:3px; border:solid 2px #F0F0F0; background-color:#F0F0F0 !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipoAplicacion(ve.tipo_aplicacion)+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#D8D8D8" id="intervalo_text'+ve.id+'">'+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+'</span> </span> </div> </div> <div class="row" style="text-align:center;"> <i class="fa fa-clock-o" style="color:white; font-size:50px ;"></i>  </div> </div> </div>');
            }
        }
    });

    // APLICA MASCARA DD-MM-AAAA PARA LAS FECHAS DE APLICACIÓN
    setTimeout(function() {
        $("input[name*='fecha_aplicacion']").mask("99-99-9999");
    }, 100);
}

// DEVUELVE TEXTO DE TIPO DE APLICACIÓN
function tipoAplicacion(tipo){
    if(tipo==1) {
        return 'Dosis única';
    } 
    if(tipo==2) {
        return '1a Dosis';
    } 
    if(tipo==3) {
        return '2a Dosis';
    }
    if(tipo==4){ 
        return '3a Dosis'; 
    }
    if(tipo==5){ 
        return '4a Dosis'; 
    }
    if(tipo==6) {
        return 'Refuerzo';
    }
}

// VERIFICA QUE LOS CAMPOS PARA LA CURP SEAN CORRECTOS
function validarCamposCURP(){            
    var estado = $(".js-data-estado").val();
    var born_state =  estados_equivalencia[estado];
    if($(".js-data-genero").val()=="M") {
        var gender = 1;
    }
    if($(".js-data-genero").val()=="F") {
        var gender = 2;
    }
    var fn_validar = reemplazarTodo($('#fecha_nacimiento').val(),"-", "/");
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
        $.get('curp', data, function(response, status){ // Consulta CURP
            if(response.find==true){                        
                $("#curp").val(response.curp);
                curpRepetida(response.curp);
                notificar('Información','Se encontró la CURP, asegurese que sea correcta','info',4000);
            }
            if(response.find==false || response.curp==""){ 
                calcularCURP(name, father_surname, mother_surname, born_date[0], born_date[1], born_date[2], born_state, gender);
            }
        }).fail(function(){  // Calcula CURP                    
            calcularCURP(name, father_surname, mother_surname, born_date[0], born_date[1], born_date[2], born_state, gender);
        });

    } else {
        if(errors<2){
            notificar('Información',warning,'info',3000);
        }
    }
}

function verDetalles(id){
    var apl = ultimo_esquema[id];                        
    $("span#dosis").html('<strong>'+tipoAplicacion(apl.tipo_aplicacion)+' '+apl.clave+'</strong>');
    $("h3#intervalos").html('Aplicar desde <button class="btn btn-round btn-danger"> '+obtieneIntervaloCompleto(apl.intervalo_inicio_anio,apl.intervalo_inicio_mes,apl.intervalo_inicio_dia)+'</button> hasta los <button class="btn btn-round btn-danger"> '+obtieneIntervaloCompleto(apl.intervalo_fin_anio,apl.intervalo_fin_mes,apl.intervalo_fin_dia)+'</button>');
    $("h3#dias-anticipacion").html('Aplicar con <button class="btn btn-round btn-primary"> '+apl.margen_anticipacion+'</button> de anticipación por oportunidad perdida');
    $("h3#fecha-ideal").html(' Edad ideal de aplicación <button class="btn btn-round btn-success"> '+obtieneIntervaloCompleto(apl.edad_ideal_anio,apl.edad_ideal_mes,apl.edad_ideal_dia)+'</button>');
    $("h3#intervalos-ni").html(' Aplicar <button class="btn btn-round btn-info"> '+obtieneIntervaloCompleto(apl.entre_siguiente_dosis_anio,apl.entre_siguiente_dosis_mes,apl.entre_siguiente_dosis_dia)+'</button> después de la dosis anterior');
    $("button.btn-detalle").attr('style',  'background-color:#'+apl.color_rgb);
    $("div.modal-body").attr('style',  'color:#'+apl.color_rgb);
    $("div.modal-header").attr('style',  'background-color:#'+apl.color_rgb);
}

// DEVUELVE 'UN 1M, 3A, NAC, ...' TEXTO CON BASE A LOS DIAS QUE RECIBE
function obtieneIntervaloCompleto(anio,mes,dia){
    if(anio==0){ 
        if(mes==0){ 
            return Math.round(parseInt(dia))+' días ';
        } else {
            if(dia==0){
                return Math.round(parseInt(mes))+' meses ';
            } else {
                return Math.round(parseInt(mes))+' meses y '+Math.round(parseInt(dia))+' días ';
            }
        }
    } else {
        if(mes==0){ 
            if(dia==0){
                return Math.round(parseInt(anio))+' Años ';
            } else {
                return Math.round(parseInt(anio))+' años y '+Math.round(parseInt(dia))+' días';
            }
        } else {
            if(dia==0){
                return Math.round(parseInt(anio))+' años y '+Math.round(parseInt(mes))+' meses';
            } else {
                return Math.round(parseInt(anio))+' años y '+Math.round(parseInt(mes))+' meses y '+Math.round(parseInt(dia))+' días';
            }
        }
    }
}

function obtieneIntervalo(anio,mes,dia){
    if(anio==0){ 
        if(mes==0){ 
            if(dia<=29){ 
                return 'Nac';
            } else {
                return Math.round(parseInt(dia))+'D';
            }
        } else {
            return Math.round(parseInt(mes))+'M';
        }
    } else {
        if(mes==0){ 
            return Math.round(parseInt(anio))+'A';
        } else {
            return Math.round(parseInt(anio) * 12 + parseInt(mes))+'M';
        }
    }
}


// USA UN SCRIPT PARA CALCULAR CURP
function calcularCURP(name, father_surname, mother_surname, born_date_d, born_date_m, born_date_a, born_state, gender){
    var  curp = mxk.getCURP(name, father_surname, mother_surname, born_date_d, born_date_m, born_date_a, born_state, gender);
    $("#curp").val(curp);
    curpRepetida(curp);
    notificar('Información','Se CALCULÓ la CURP, verifique los datos','warning',3000);
}

// TRUE, SI LA FECHA TIENE FORMATO VALIDO
function validarFormatoFecha(campo) {
    var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
    if ((campo.match(RegExPattern)) && (campo!='')) {
            return true;
    } else {
            return false;
    }
}

// VALIDA SI EXISTE LA FECHA
function existeFecha(fecha){
    var fechaf = fecha.split("/");
    var d = fechaf[0];
    var m = fechaf[1];
    var y = fechaf[2];
    return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
}

// REEMPLAZA LO QUE SE LE PIDA EN UNA CADENA
function reemplazarTodo(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
}    