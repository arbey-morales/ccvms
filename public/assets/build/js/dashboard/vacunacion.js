function construirVacunacion(datos){
  $(".total-capturas").empty().html(numberWithCommas(parseInt(datos.biologico.todos)));
  $(".total-ninos").empty().html(numberWithCommas(parseInt(datos.biologico.ninios)));
  $(".total-ninas").empty().html(numberWithCommas(parseInt(datos.biologico.ninias)));
}

$(".js-data-tipo-aplicacion,.js-data-jurisdiccion,.js-data-municipio").change(function(){
  vacunacion();
});

var vacunacion = function() {
  var datos = $("#vacunacion-form").serialize();
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