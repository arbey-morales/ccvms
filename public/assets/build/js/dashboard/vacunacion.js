var edades = [
  {id:0, text:'Menores de 1 año'},
  {id:1, text:'1 Año'},
  {id:2, text:'2 Años'},
  {id:3, text:'3 Años'},
  {id:4, text:'4 Años'},
  {id:6, text:'6 Años'}
];

function construirVacunacion(datos){
  // CAPTURAS
  $(".total-capturas").empty().html(numberWithCommas(parseInt(datos.captura.todos)));
  $(".total-ninos").empty().html(numberWithCommas(parseInt(datos.captura.ninios)));
  $(".total-ninas").empty().html(numberWithCommas(parseInt(datos.captura.ninias)));
  // COBERTURAS
  $(".total-coberturas").empty().html(numberWithCommas(parseInt(datos.cobertura.todos)));
  $(".total-coberturas-ninos").empty().html(numberWithCommas(parseInt(datos.cobertura.ninios)));
  $(".total-coberturas-ninas").empty().html(numberWithCommas(parseInt(datos.cobertura.ninias)));
  // COBERTURAS %
  var pc = (100 / parseInt(datos.captura.todos)) * parseInt(datos.cobertura.todos);
  console.log(pc)
  $(".porcentaje-coberturas").empty().html(numberWithCommas(pc.toFixed(2)));
  $(".porcentaje-coberturas-ninos").empty().html(numberWithCommas(parseInt(datos.cobertura.ninios)));
  $(".porcentaje-coberturas-ninas").empty().html(numberWithCommas(parseInt(datos.cobertura.ninias)));
  // ESQUEMAS COMPLETOS
  // CONCORDANCIA
}

var vacunacion = function() {
  var datos = $("#dashboard-form").serialize();
  $(".total-capturas,.total-ninos,.total-ninas").empty(); $(".total-capturas").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
  $.get('dashboard/vacunacion', datos, function(response, status){ // Consulta
    if(response.data==null){
      notificar('Error','Sin datos','error',2000);
    } else { 
      construirVacunacion(response.data);
    }
  }).fail(function(){ 
      notificar('Error','Error interno','error',2000);
  });
}