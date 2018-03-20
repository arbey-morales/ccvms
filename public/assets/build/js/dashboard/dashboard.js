// Colapsa el menu de la izquierda
$('body').removeClass('nav-md');
$('body').addClass('nav-sm');

// Inicio de valores iniciales
var jurisdicciones = [{ 'id': 0, 'text': 'Todas las Jurisdicciones' }];
var municipios = [{ 'id':0, 'text':'Todos los Municipios'}];
var localidades = [{ 'id':0, 'text':'Todas las Localidades'}];
var clues = [{ 'id':0, 'text':'Todas las Unidad de Salud'}];
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

$(document).ready(function(){
  $(".js-data-jurisdiccion").select2({
    language: "es",
    data: jurisdicciones
  });
  $(".js-data-municipio").select2({
    language: "es",
    data: municipios
  });
  $(".js-data-clue").select2({
    language: "es",
    data: clues
  });
  $(".js-data-tipo-aplicacion").select2({
    language: "es",
    data: dosis
  });
  $(".js-data-vacuna").select2({
    language: "es",
    data: vacunas
  });
  vacunacion(); contenedoresEstatus();
});
// Fin de valores iniciales
  
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

$.get('catalogo/vacuna', {}, function(response, status){ // Consulta
  if(response.data==null){
    notificar('Error','Sin datos','error',2000);
  } else { 
    while (vacunas.length) { vacunas.pop(); }                
    vacunas.push({ 'id': 0, 'text': 'Todas las vacunas' });           
    if(response.data.length<=0){
        notificar('Información','No existen vacunas','warning',2000);
    } else {

      //notificar('Información','Cargando vacunas','info',2000);
      $('.js-data-vacuna').empty(); 
                           
      $.each(response.data, function( i, cont ) {
        var dv = [];
        var temp = [];
        while (dv.length) { dv.pop(); }
        while (temp.length) { temp.pop(); }
        $.each(cont.vacunas_esquemas, function( ive, contve ) {
          if(ive==0)
            dv.push(dosis[0]);
          if(temp.indexOf(contve.tipo_aplicacion) != -1){ 
          } else {
            dv.push(dosis[contve.tipo_aplicacion]);  temp.push(contve.tipo_aplicacion);
          }
        });
        dosis_vacunas[cont.id]= dv;
        vacunas.push({ 'id': cont.id, 'text': cont.clave+'-'+cont.nombre });
      });  
      //console.log(dosis_vacunas)
    }  
    $(".js-data-vacuna").select2({
        language: "es",
        data: vacunas
    }); 
  }
}).fail(function(){ 
    notificar('Error','Error interno','error',2000);
});

  
$(".js-data-vacuna").change(function(){
  var id = $(this).val();
  var dss = dosis_vacunas[id];
  $('.js-data-tipo-aplicacion').empty();
  $(".js-data-tipo-aplicacion").select2({
    language: "es",
    data: dss
  }); 

  vacunacion();
});

var numberWithCommas = function (x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
} 