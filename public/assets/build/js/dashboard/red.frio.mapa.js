var map;
var marcadores = [];
var centerLat = 16.3074731;
var centerLgt = -92.8639654;
var contenedores = [];



// INICIALIZA EL MAPA
function initialize() {
  //console.log(centerLat, centerLgt)
  if (marcadores.length > 0) {
    centerLat = marcadores[0][1];
    centerLgt = marcadores[0][1];
  }
  //console.log(' - - ', centerLat, centerLgt)
  var latlng = new google.maps.LatLng(centerLat, centerLgt);
  var mapOptions = {
    center: { lat: 16.3074731, lng: -92.8639654 },
    zoom: 8,
    styles: styleMap
  }
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
  setMarkers(map, marcadores);
}

// AGREGA LOS MARCADORES
var infowindow;
function setMarkers(map, marcadores) {
  for (var i = 0; i < marcadores.length; i++) {
    var myLatLng = new google.maps.LatLng(marcadores[i][1], marcadores[i][2]);
    //console.log(marcadores[i])
    var image = {
      url: 'images/markers/marker-'+marcadores[i][4]+'.png',
      // This marker is 20 pixels wide by 32 pixels high.
      //size: new google.maps.Size(20, 32),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 32).
      anchor: new google.maps.Point(0, 32)
    };
  
    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      icon: image,
      title: marcadores[i][0],
    });
    (function (i, marker) {
      google.maps.event.addListener(marker, 'click', function () {
        if (!infowindow) {
          infowindow = new google.maps.InfoWindow();
        }
        infowindow.setContent(marcadores[i][3]);
        infowindow.open(map, marker);
      });
    })(i, marker);
  }
};

// DEVUELVE LA LISTA DE CONTENEDORES CON LA UBICACIÓN DE LA CLUES
function ubicacionContenedores() {
  var data = $("#dashboard-form").serialize();
  $.get('dashboard/ubicacion-contenedores', data, function (response, status) { // Consulta
    if (response.data == null) {
      notificar('Error', 'Sin datos', 'error', 2000);
    } else {
      while (marcadores.length) { marcadores.pop(); }
      contenedores = response.data;
      response.data.forEach(element => {
        //console.log(element)
        marcadores.push([
          element.clues,                        // Titulo
          element.numero_latitud,               // Longitud
          element.numero_longitud,              // Latitud
          element.clues + ' ' + element.nombre, // Leyenda del marcador
          element.icono
        ]);
      });

      initialize();

    }
  }).fail(function () {
    notificar('Error', 'Error interno', 'error', 2000);
  });
}

// CARGA LA LISTA DE CONTENEDORES POR ESTATUS Y TIPO
var contenedoresEstatus = function () {
  var data = $("#dashboard-form").serialize();
  $.get('dashboard/contenedores-biologico', data, function (response, status) { // Consulta
    if (response.data == null) {
      notificar('Error', 'Sin datos', 'error', 2000);
    } else {
      construirContenedores(response.data);
    }
  }).fail(function () {
    notificar('Error', 'Error interno', 'error', 2000);
  });
}

var verEstatus = function (estatus) { // El estatus de los contenedores

}

var verEstatusTipo = function (estatus, tipo) { // El estatus y tipo de los contenedores

}

// CONSTRUYE LA LISTA DE LOS ESTSTUS Y LOS TIPOS DE CONTENEDORES
var construirContenedores = function (datos) {
  var statusContenedores = '';
  var accordion = ''; var aria = 'true'; var collapsedhead = 'collapse'; //var otherStatus = ``;
  
  var total = 0;
  datos.forEach(element => {
  //   otherStatus+= `<div class="col-sm-2">
  //   <div class="daily-weather">
  //     <h2 class="day">Sat</h2>
  //     <h3 class="degrees">26</h3>
  //     <canvas height="32" width="32" id="cloudy"></canvas>
  //     <h5>10 <i>km/h</i></h5>
  //   </div>
  // </div>`;

    accordion += `<div class="panel" onClick="verEstatus(` + element.id + `)">
                    <a class="panel-heading `+ collapsedhead + `" role="tab" id="heading` + element.id + `" data-toggle="collapse" data-parent="#accordion" href="#panel` + element.id + `" aria-expanded="` + aria + `" aria-controls="panel` + element.id + `">
                      <button type="button" class="btn btn-round" style="background-color:`+ element.color + `; padding:8px 10px;">
                        <i class="fa `+ element.icono + `" style="color:white; font-size:large;"></i> <span style="color:white; font-size:large;">` + element.total + `</span>
                      </button> <span class="panel-title">`+ element.descripcion + `</span>
                    </a>
                    <div id="panel`+ element.id + `" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading` + element.id + `">
                      <div class="panel-body">`;
    element.tipos.forEach(tipo => {
      accordion += `<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="tile-stats">
                                          <div class="icon"> <img width="35px" src="images/tipos-contenedores/`+ tipo.imagen + `.png">
                                          </div>
                                          <div class="count">`+ tipo.total + `</div>

                                          <h3>`+ tipo.clave + `</h3>
                                          <p>`+ tipo.nombre + `</p>
                                        </div>
                                      </div>`;
    });
    accordion += `</div>
                    </div>
                  </div>`;
    statusContenedores += `<li>
                            <button type="button" class="btn btn-round" style="background-color:`+ element.color + `; padding:8px 10px;">
                              <i class="fa `+ element.icono + `" style="color:white; font-size:large;"></i> ` + element.total + `
                            </button>`+ element.descripcion + `
                          </li>`;

    total = total + parseInt(element.total);

    if (aria == 'true') {
      aria = 'false'; collapsedhead = '';
    }
  });

  $("#total-contenedores").empty().html(total);
  $("#accordion").empty().html(accordion);
  //$(".other-status").empty().html(otherStatus);
}