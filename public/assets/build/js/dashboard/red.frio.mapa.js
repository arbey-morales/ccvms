var map;
var marcadores = [];
var centerLat = 16.3074731;
var centerLgt = -92.8639654;

$(".js-data-clue,.js-data-jurisdiccion,.js-data-municipio").change(function(){
  initMap(); contenedoresEstatus();
});

function initialize() {
  console.log(centerLat,centerLgt)
  if(marcadores.length>0){
    centerLat = marcadores[0][1];
    centerLgt = marcadores[0][1];
  }
  console.log(' - - ',centerLat,centerLgt)
  var latlng = new google.maps.LatLng(centerLat,centerLgt);
  var mapOptions = {
    center: {lat: 16.3074731, lng: -92.8639654},
    zoom: 8,
    styles: styleMap
  }
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
  setMarkers(map, marcadores);
  //map.refresh();
  console.log(marcadores)
}

var iconBase = 'images/markers/';
var imageCamaraFria = {
  url: 'images/markers/camara-fria.png',      //ruta de la imagen
};
    
var infowindow;
function setMarkers(map, marcadores) {
  for (var i = 0; i < marcadores.length; i++) {
    var myLatLng = new google.maps.LatLng(marcadores[i][1], marcadores[i][2]);
    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      //icon: imageCamaraFria,
      title: marcadores[i][0],
    });
    (function(i, marker) {
      google.maps.event.addListener(marker,'click',function() {
        if (!infowindow) {
          infowindow = new google.maps.InfoWindow();
        }
        infowindow.setContent(marcadores[i][3]);
        infowindow.open(map, marker);
      });
    })(i, marker);
  }
};

function initMap() {  
  var data = $("#vacunacion-form").serialize();
  $.get('dashboard/ubicacion-contenedores', data, function(response, status){ // Consulta
    if(response.data==null){
      notificar('Error','Sin datos','error',2000);
    } else { 
      while (marcadores.length) { marcadores.pop(); } 
      response.data.forEach(element => {
        marcadores.push([
                          element.clues,
                          element.numero_latitud,
                          element.numero_longitud,
                          element.clues+' '+element.nombre
                        ]);
      });

      initialize();

    }
  }).fail(function(){ 
      notificar('Error','Error interno','error',2000);
  });
}

var contenedoresEstatus = function(){
  var data = $("#vacunacion-form").serialize();
  $.get('dashboard/contenedores-biologico', data, function(response, status){ // Consulta
    if(response.data==null){
      notificar('Error','Sin datos','error',2000);
    } else { 
      construirContenedores(response.data);
    }
  }).fail(function(){ 
      notificar('Error','Error interno','error',2000);
  });
}



var construirContenedores = function(datos){
  var statusContenedores = '';
  var accordion = ''; var aria = 'true'; var collapsedhead = 'collapse'; var otherStatus = `
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Username</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Mark</td>
                  <td>Otto</td>
                  <td>@mdo</td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Jacob</td>
                  <td>Thornton</td>
                  <td>@fat</td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Larry</td>
                  <td>the Bird</td>
                  <td>@twitter</td>
                </tr>
              </tbody>
            </table>`;
            //$(".other-status").empty().html(otherStatus);
  var total = 0;
  datos.forEach(element => {

    accordion+= `<div class="panel">
                    <a class="panel-heading `+collapsedhead+`" role="tab" id="heading`+element.id+`" data-toggle="collapse" data-parent="#accordion" href="#panel`+element.id+`" aria-expanded="`+aria+`" aria-controls="panel`+element.id+`">
                      <button type="button" class="btn btn-round" style="background-color:`+element.color+`; padding:8px 10px;">
                        <i class="fa `+element.icono+`" style="color:white; font-size:large;"></i> <span style="color:white; font-size:large;">`+element.total+`</span>
                      </button> <span class="panel-title">`+element.descripcion+`</span>
                    </a>
                    <div id="panel`+element.id+`" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading`+element.id+`">
                      <div class="panel-body">`; 
                      element.tipos.forEach(tipo => {
                        accordion+= `<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="tile-stats">
                                          <div class="icon"> <img width="35px" src="images/tipos-contenedores/`+tipo.imagen+`.png">
                                          </div>
                                          <div class="count">`+tipo.total+`</div>

                                          <h3>`+tipo.clave+`</h3>
                                          <p>`+tipo.nombre+`</p>
                                        </div>
                                      </div>`;
                      });
    accordion+= `</div>
                    </div>
                  </div>`;
    statusContenedores+= `<li>
                            <button type="button" class="btn btn-round" style="background-color:`+element.color+`; padding:8px 10px;">
                              <i class="fa `+element.icono+`" style="color:white; font-size:large;"></i> `+element.total+`
                            </button>`+element.descripcion+`
                          </li>`;

    total = total + parseInt(element.total);
    
    if(aria=='true'){
      aria = 'false'; collapsedhead = '';
    }
  });

  $("#total-contenedores").empty().html(total);
  $("#accordion").empty().html(accordion);
}