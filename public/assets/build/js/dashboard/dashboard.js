// Colapsa el menu de la izquierda
$('body').removeClass('nav-md');
$('body').addClass('nav-sm');

// Inicio de valores iniciales
var jurisdicciones = [{ 'id': 0, 'text': 'Todas las Jurisdicciones' }];
var municipios = [{ 'id':0, 'text':'Todos los Municipios'}];
var municipiosData = [];
var localidades = [{ 'id':0, 'text':'Todas las Localidades'}];
var localidadesData = [];
var tiposContenedores = [{ 'id':0, 'text':'Todos los tipos de contenedores'}];
var estatusContenedores = [{ 'id':0, 'text':'Todos los estatus'}];
var clues = [{ 'id':0, 'text':'Todas las Unidad de Salud'}];
var cluesData = [];
var dosis = [
              { 'id': 0, 'text': 'Todas las aplicaciones/dosis' },
              { 'id': 1, 'text': 'Dosis única' },
              { 'id': 2, 'text': '1a dosis' },
              { 'id': 3, 'text': '2a dosis' },
              { 'id': 4, 'text': '3a dosis' },
              { 'id': 5, 'text': '4a dosis' },
              { 'id': 6, 'text': 'Refuerzo' }
            ];
var dosis_vacunas = [];
var vacunas = [{ 'id': 0, 'text': 'Todas las Vacunas' }];

$(".js-data-jurisdiccion").change(function () {
  vaciaClue();
  limitaMunicipios($(this).val()); 
  ubicacionContenedores(); 
  contenedoresEstatus(); 
  vacunacion(); 
});

$(".js-data-municipio").change(function () {
  vaciaClue();
  ubicacionContenedores(); 
  contenedoresEstatus(); 
  vacunacion();
});

$(".js-data-clue").change(function () {
  ubicacionContenedores(); 
  contenedoresEstatus(); 
  vacunacion();
});

$(".js-data-estatus-contenedor,.js-data-tipo-contenedor").change(function(){
  ubicacionContenedores(); 
  contenedoresEstatus();
});

$(".js-data-tipo-aplicacion").change(function(){
  vacunacion();
});

$(".js-data-edad").change(function(){
  vacunacion();
});

$(".js-data-vacuna").change(function(){
  limitaDosis($(this).val());
  vacunacion();
});

var vaciaClue = function(){
  while (clues.length) { clues.pop(); }                
  clues.push({ 'id': 0, 'text': 'Todas las unidades de salud' }); 
  $('.js-data-clue').empty();
}

var limitaDosis = function(id){
  var dss = dosis_vacunas[id];
  $('.js-data-tipo-aplicacion').empty();
  $(".js-data-tipo-aplicacion").select2({
    language: "es",
    data: dss
  });
}

var limitaMunicipios = function(id){
  console.log(id)
  while (municipios.length) { municipios.pop(); }                
  municipios.push({ 'id': 0, 'text': 'Todos las Municipios' });
  $.each(municipiosData, function( i, cont ) {
    //console.log(cont.id,cont.jurisdicciones_id, parseInt(id))
    if(parseInt(id)==0) {
      municipios.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
    } else {
      if(cont.jurisdicciones_id==parseInt(id)) {
        municipios.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
      }
    }
  }); 
  $('.js-data-municipio').empty();
  $(".js-data-municipio").select2({
    language: "es",
    data: municipios
  });
}

/*var consultaClues = function(){
  var data = $("#dashboard-form").serialize();
  $.get('/catalogo/clue', data, function(response, status){ // Consulta
    if(response.data==null){
      notificar('Error','Sin datos','error',2000);
    } else { 
      while (clues.length) { clues.pop(); }                
      clues.push({ 'id': 0, 'text': 'Todas las unidades de salud' });           
      if(response.data.length<=0){
          notificar('Información','No existen jurisdicciones','warning',2000);
      } else {
          //notificar('Información','Cargando clues','info',2000);
          $('.js-data-clue').empty();                      
          $.each(response.data, function( i, cont ) {
              clues.push({ 'id': cont.id, 'text': cont.clues+'-'+cont.nombre });
          });  
      }  
      $(".js-data-clue").select2({
          language: "es",
          data: clues
      });
    }
  }).fail(function(response, status){ 
      notificar('Error','Error interno'+response+' ----- '+status,'error',2000);
  });
}*/
 
// CARGA EL CATALOGO DE JURISDICCIONES
$.get('catalogo/jurisdiccion', {}, function(response, status){ // Consulta
  if(response.data==null){
    notificar('Error','Sin datos','error',2000);
  } else { 
    while (jurisdicciones.length) { jurisdicciones.pop(); }                
    jurisdicciones.push({ 'id': 0, 'text': 'Todas las Jurisdicciones' });           
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
    notificar('Error','Error interno','error',2000);
});

// CARGA LA LISTA DE MUNICIPIOS
$.get('catalogo/municipio', {}, function(response, status){ // Consulta
  if(response.data==null){
    notificar('Error','Sin datos','error',2000);
  } else { 
    while (municipios.length) { municipios.pop(); }                
    municipios.push({ 'id': 0, 'text': 'Todos las Municipios' });           
    if(response.data.length<=0){
        notificar('Información','No existen municipios','warning',2000);
    } else {
        //notificar('Información','Cargando municipios','info',2000);
        $('.js-data-municipio').empty(); 
        municipiosData = response.data;                     
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
    notificar('Error','Error interno','error',2000);
});

// CARGAR LA LISTA DE VACUNAS
$.get('catalogo/vacuna', {}, function(response, status){ // Consulta
  if(response.data==null){
    notificar('Error','Sin datos','error',2000);
  } else { 
    while (vacunas.length) { vacunas.pop(); }                
    //vacunas.push({ 'id': 0, 'text': 'Todas las vacunas' });           
    if(response.data.length<=0){
        notificar('Información','No existen vacunas','warning',2000);
    } else {

      //notificar('Información','Cargando vacunas','info',2000);
      $('.js-data-vacuna').empty(); 
                           
      $.each(response.data, function( i, cont ) {
        var dv = [];
        var temp = []; // Por cada vacuna
        while (dv.length) { dv.pop(); }
        while (temp.length) { temp.pop(); }
        $.each(cont.vacunas_esquemas, function( ive, contve ) {
          // if(ive==0)
          //   dv.push(dosis[1]);
            //console.log(contve.tipo_aplicacion)
          if(temp.indexOf(contve.tipo_aplicacion) != -1){ 
            //console.log('VACUNA: '+cont.id+' , existe TA: '+contve.tipo_aplicacion)
          } else {
            dv.push(dosis[contve.tipo_aplicacion]);  temp.push(contve.tipo_aplicacion);
          }
        });
        dosis_vacunas[cont.id]= dv;
        vacunas.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
      });  
      //console.log(dosis_vacunas)
    } 
    
    //console.log(dosis_vacunas)
    $(".js-data-vacuna").select2({
        language: "es",
        data: vacunas
    }); 

    limitaDosis(1);

  }
}).fail(function(){ 
    notificar('Error','Error interno','error',2000);
});

// CARGAR LA LISTA DE STATUS DE CONTENEDORES
$.get('catalogo/red-frio/estatus-contenedor', {}, function(response, status){ // Consulta
  if(response.data==null){
    notificar('Error','Sin datos','error',2000);
  } else { 
    while (estatusContenedores.length) { estatusContenedores.pop(); }                
    estatusContenedores.push({ 'id': 0, 'text': 'Todos los estatus' });           
    if(response.data.length<=0){
        notificar('Información','No existen estatus de contenedores','warning',2000);
    } else {

      //notificar('Información','Cargando vacunas','info',2000);
      $('.js-data-estatus-contenedor').empty(); 
                           
      $.each(response.data, function( i, cont ) {
        estatusContenedores.push({ 'id': cont.id, 'text': cont.descripcion });
      });  
      //console.log(dosis_vacunas)
    }  
    $(".js-data-estatus-contenedor").select2({
        language: "es",
        data: estatusContenedores
    }); 
  }
}).fail(function(){ 
    notificar('Error','Error interno','error',2000);
});

// CARGA LA LISTA DE TIPOS DE CONTENEDORES
$.get('catalogo/red-frio/tipo-contenedor', {}, function(response, status){ // Consulta
  if(response.data==null){
    notificar('Error','Sin datos','error',2000);
  } else { 
    while (tiposContenedores.length) { tiposContenedores.pop(); }                
    tiposContenedores.push({ 'id': 0, 'text': 'Todos los tipos de contenedores' });           
    if(response.data.length<=0){
        notificar('Información','No existen tipos de contenedores','warning',2000);
    } else {

      //notificar('Información','Cargando vacunas','info',2000);
      $('.js-data-tipo-contenedor').empty(); 
                           
      $.each(response.data, function( i, cont ) {
        tiposContenedores.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
      });  
      //console.log(dosis_vacunas)
    }  
    $(".js-data-tipo-contenedor").select2({
        language: "es",
        data: tiposContenedores
    }); 
  }
}).fail(function(){ 
    notificar('Error','Error interno','error',2000);
});

// UNIDAD DE SALUD
$(".js-data-clue").select2({
  ajax: {
      url: "/catalogo/clue",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        console.log('ONE: '+params.term)
        return {
            q:                  params.term, // search term
            municipios_id:      $('.js-data-municipio').val(), // search term
            jurisdicciones_id:  $('.js-data-jurisdiccion').val(), // search term
            page:               params.page
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
  allowClear: true,
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


var numberWithCommas = function (x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
} 

$(document).ready(function(){
  vaciaClue();
  $(".js-data-edad").select2({
    language: "es",
    data: edades
  }); 
  $(".js-data-tipo-aplicacion").select2({
    language: "es",
    data: dosis
  });
  setTimeout(() => {    
    vacunacion();
  }, 1000); 
  contenedoresEstatus();
});