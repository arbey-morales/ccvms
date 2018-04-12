var edades = [
  {id:0, text:'Menores de 1 año'},
  {id:1, text:'1 Año'},
  {id:2, text:'2 Años'},
  {id:3, text:'3 Años'},
  {id:4, text:'4 Años'},
  {id:6, text:'6 Años'}
];

var edadesEsquemasCompletos = [
  {id:0, text:'Menores de 1 año'},
  {id:1, text:'Hasta 1 Año'},
  {id:2, text:'Hasta 2 Años'},
  {id:3, text:'Hasta 3 Años'},
  {id:4, text:'Hasta 4 Años'},
  {id:4, text:'Hasta 5 Años'},
  {id:6, text:'Hasta 6 Años'},
  {id:7, text:'Hasta 7 Años'},
  {id:8, text:'Hasta 8 Años'}
];
var edadesConcordancia = [
  //{id:-1, text:'De 0 a 10 años'},
  {id:0, text:'Menores de 1 año'},
  {id:1, text:'1 Año'},
  {id:2, text:'2 Años'},
  {id:3, text:'3 Años'},
  {id:4, text:'4 Años'},
  {id:4, text:'5 Años'},
  {id:6, text:'6 Años'},
  {id:7, text:'7 Años'},
  {id:8, text:'8 Años'},
  {id:9, text:'7 Años'},
  {id:10, text:'8 Años'}
];

function construirCoberturas(datos){
  // CAPTURAS
  $(".total-capturas").empty().html(numberWithCommas(parseInt(datos.poblacion_nominal.todos)));
  $(".total-ninos").empty().html(numberWithCommas(parseInt(datos.poblacion_nominal.ninios)));
  $(".total-ninas").empty().html(numberWithCommas(parseInt(datos.poblacion_nominal.ninias)));
  // COBERTURAS
  $(".total-coberturas").empty().html(numberWithCommas(parseInt(datos.cobertura.todos)));
  $(".total-coberturas-ninos").empty().html(numberWithCommas(parseInt(datos.cobertura.ninios)));
  $(".total-coberturas-ninas").empty().html(numberWithCommas(parseInt(datos.cobertura.ninias)));
  // COBERTURAS %
  var pcPoNo = (100 / parseInt(datos.poblacion_nominal.todos)) * parseInt(datos.cobertura.todos);
  if (isNaN(pcPoNo)) {
    pcPoNo = 0;
  }
  $(".porcentaje-coberturas-nominal").empty().html(numberWithCommas(pcPoNo.toFixed(2))+' % <small style="font-size:small;">Nominal</small>');
  // $(".porcentaje-coberturas-oficial").empty();
  // if ($("#clues_id").val()==null || $("#clues_id").val()==0) { // Si no hay clue se puede traer la población oficial
  //   var pcPoOf = (100 / parseInt(datos.poblacion_nominal.todos)) * parseInt(datos.cobertura.todos);
  //   if (isNaN(pcPoOf)) {
  //     pcPoOf = 0;
  //   }
  //   $(".porcentaje-coberturas-oficial").empty().html(numberWithCommas(pcPoOf.toFixed(2))+' % <small style="font-size:small;">Oficial</small>');
  // }
  
  $(".porcentaje-coberturas-ninos").empty().html(numberWithCommas(parseInt(datos.cobertura.ninios)));
  $(".porcentaje-coberturas-ninas").empty().html(numberWithCommas(parseInt(datos.cobertura.ninias)));
  // ESQUEMAS COMPLETOS
  // CONCORDANCIA
}

function construirEsquemasCompletos(datos){
  // POBLACIÓN NOMINAL
  $(".total-nominal-esquemas-completos").empty().html(numberWithCommas(parseInt(datos.poblacion_nominal.todos)));
  $(".total-nominal-esquemas-completos-ninos").empty().html(numberWithCommas(parseInt(datos.poblacion_nominal.ninios)));
  $(".total-nominal-esquemas-completos-ninas").empty().html(numberWithCommas(parseInt(datos.poblacion_nominal.ninias))); 
  // COBERTURAS
  $(".total-esquemas-completos").empty().html(numberWithCommas(parseInt(datos.esquema_completo.todos)));
  $(".total-esquemas-completos-ninos").empty().html(numberWithCommas(parseInt(datos.esquema_completo.ninios)));
  $(".total-esquemas-completos-ninas").empty().html(numberWithCommas(parseInt(datos.esquema_completo.ninias)));
}

function construirConcordancia(datos){
  // POBLACIÓN NOMINAL
  var limiteEdadConcordancia = 10;
  var inicioEdadConcordancia = 0;
  if(parseInt($(".js-data-edad-concordancia").val())===-1){ // todas las edades 0-10 años
    limiteEdadConcordancia = 10;
    inicioEdadConcordancia = 0;
  } else { // Una sola edad ej: 1 o 2 o 5 años
    limiteEdadConcordancia = parseInt($(".js-data-edad-concordancia").val());
    inicioEdadConcordancia = parseInt($(".js-data-edad-concordancia").val());
  }

  var po = 0;  var pn = 0;  var pc = 0;

  var table = `<table class="table table-striped" style="width:100%;">
                <thead>
                  <tr>
                    <th style="text-align:center;" rowspan="2">MUNICIPIO</th>
                    <th style="text-align:center;" colspan="2">POBLACIÓN OFICIAL</th>
                    <th style="text-align:center;" colspan="2">POBLACIÓN NOMINAL</th>
                    <th style="text-align:center;" colspan="3">% CONCORDANCIA</th>
                  </tr>
                  <tr> 
                    <th style="text-align:center;" ><i class="fa fa-male" style="color:#4d81bf; font-size:large;"></i></th>
                    <th style="text-align:center;" ><i class="fa fa-female" style="color:#ed1586; font-size:large;"></i></th>
                    <th style="text-align:center;" ><i class="fa fa-male" style="color:#4d81bf; font-size:large;"></i></th>
                    <th style="text-align:center;" ><i class="fa fa-female" style="color:#ed1586; font-size:large;"></i></th>
                    <th style="text-align:center;" ><i class="fa fa-male" style="color:#4d81bf; font-size:large;"></i></th>
                    <th style="text-align:center;" ><i class="fa fa-female" style="color:#ed1586; font-size:large;"></i></th>
                    <th style="text-align:center;" >General</th>
                  </tr>
                </thead>
                <tbody>`; 
  var inc = 0; 
  var encabezado = '';
  datos.forEach(element => {
    var tr1 = '';
    if(limiteEdadConcordancia===inicioEdadConcordancia) { // Solo mostrar una edad
      h = 'hombres_'+inicioEdadConcordancia;
      m = 'mujeres_'+inicioEdadConcordancia;
      hn = 'hombres_nominal_'+inicioEdadConcordancia;
      mn = 'mujeres_nominal_'+inicioEdadConcordancia;
      
      var pch = 0; var pcm  = 0; var pcg  = 0;
      pn = element[hn] + element[mn];
      po = element[h] + element[m];
      pch = (100 / element[h]) * element[hn];
      pcm = (100 / element[m]) * element[mn];
      pcg = (pcm + pch)/2;
      if (!isFinite(pch))
        pch = 0.00;
      if (!isFinite(pcm))
        pcm = 0.00;
      if (!isFinite(pcg))
        pcg = 0.00;
      pc+=pcg;

      table+= `<tr> 
                <td>`+element.clave+` - `+element.nombre+`</td>
                <td style="text-align:center;">`+element[h]+`</td>
                <td style="text-align:center;">`+element[m]+`</td>
                <td style="text-align:center;">`+element[hn]+`</td>
                <td style="text-align:center;">`+element[mn]+`</td>
                <td style="text-align:center;">`+pch.toFixed(2)+`</td>
                <td style="text-align:center;">`+pcm.toFixed(2)+`</td>
                <td style="text-align:center;">`+pcg.toFixed(2)+`</td>
              </tr>`;
    } else {
      // table+= '<tr><td>'+element.clave+' - '+element.nombre+'</td>';
      // for (let index = inicioEdadConcordancia; index < limiteEdadConcordancia; index++) {
      //   //console.log(' ------------------------- ' +index+ ' ------------------------- ')
      //   h = 'hombres_'+index;
      //   m = 'mujeres_'+index;
      //   hn = 'hombres_nominal_'+index;
      //   mn = 'mujeres_nominal_'+index;
      //   table+= '<td>'+element[h]+'</td><td>'+element[m]+'</td><td>'+element[hn]+'</td><td>'+element[mn]+'</td>';
      // }
      // table+='</tr>';
      //console.log(element.clave+' - '+element.nombre)
    }  
    inc++;  
  });

  pc =  pc/datos.length;

  table+= `</tbody></table>`;
  $(".tabla-concordancia").empty().html(table);
  $(".pc").empty().html(pc.toFixed(2)+' %');
  // NO DEVUELVE SOLO JURIS
}

var coberturas = function() {
  $(".coberturas-button").attr('disabled','disabled');
  if (!buscandoCoberturas) {
    buscandoCoberturas = true;
    var datos = $("#dashboard-form").serialize();
    $(".total-capturas,.total-ninos,.total-ninas,.total-coberturas,.total-coberturas-ninas,.total-coberturas-ninos,.porcentaje-coberturas-oficial,.porcentaje-coberturas-nominal").empty(); 
    $(".total-capturas,.total-coberturas,.porcentaje-coberturas-nominal").html('<i class="fa fa-spinner fa-spin"></i>');
    $.get('dashboard/cobertura', datos, function(response, status){ // Consulta
      if(response.data==null){
        notificar('Error','Sin datos','error',2000);
      } else { 
        construirCoberturas(response.data);
      }

      buscandoCoberturas = false;
      $(".coberturas-button").removeAttr('disabled');
    }).fail(function(){ 
        notificar('Error','Error interno','error',2000);
        buscandoCoberturas = false;
        $(".coberturas-button").removeAttr('disabled');
    });    
  } else {
    $(".coberturas-button").removeAttr('disabled');
  }
}

var esquemasCompletos = function() {
  $(".esquemas-completos-button").attr('disabled','disabled');
  if (!buscandoEsquemasCompletos) {
    buscandoEsquemasCompletos = true;
    var datos = $("#dashboard-form").serialize(); console.log('jhsdjhsdjhsdjhsdjhsdjhsdjhsd')
    $(".total-esquemas-completos,.total-esquemas-completos-ninos,.total-esquemas-completos-ninas,.total-nominal-esquemas-completos,.total-nominal-esquemas-completos-ninos,.total-nominal-esquemas-completos-ninas").empty(); 
    $(".total-nominal-esquemas-completos,.total-esquemas-completos").html('<i class="fa fa-spinner fa-spin"></i>');
    $.get('dashboard/esquema-completo', datos, function(response, status){ // Consulta
      if(response.data==null){
        notificar('Error','Sin datos','error',2000);
      } else { 
        construirEsquemasCompletos(response.data);
      }

      buscandoEsquemasCompletos = false;
      $(".esquemas-completos-button").removeAttr('disabled');
    }).fail(function(){ 
        notificar('Error','Error interno','error',2000);
        buscandoEsquemasCompletos = false;
        $(".esquemas-completos-button").removeAttr('disabled');
    });    
  } else {
    $(".esquemas-completos-button").removeAttr('disabled');
  }
}


var concordancia = function() {
  
  if($(".concordancia-detalles").is(":visible")){
    $(".concordancia-detalles").show("fast");
  }

  if($(".tabla-concordancia").is(":visible")){

  } else {
    $(".tabla-concordancia").show("fast");
  }
  $(".concordancia-button").attr('disabled','disabled');
  if (!buscandoConcordancia) {
    buscandoConcordancia = true;
    var datos = $("#dashboard-form").serialize();
    $(".pc").html('<i class="fa fa-spinner fa-spin"></i>');
    $(".tabla-concordancia").empty().html('<div style="text-align:center; color:#989898; font-size:large; opacity: 0.7; background: #fff;"><br><br><br><i class="fa fa-spinner fa-spin"></i> Buscando</div>');
    // $(".total-capturas,.total-ninos,.total-ninas,.total-coberturas,.total-coberturas-ninas,.total-coberturas-ninos,.porcentaje-coberturas-oficial,.porcentaje-coberturas-nominal").empty(); 
    // $(".total-capturas,.total-coberturas,.porcentaje-coberturas-nominal").html('<i class="fa fa-spinner fa-spin"></i>');
    $.get('dashboard/concordancia', datos, function(response, status){ // Consulta
      if(response.data==null){
        notificar('Error','Sin datos','error',2000);
      } else { 
        construirConcordancia(response.data);
      }

      buscandoConcordancia = false;
      $(".concordancia-button").removeAttr('disabled');
    }).fail(function(){ 
        notificar('Error','Error interno','error',2000);
        buscandoConcordancia = false;
        $(".concordancia-button").removeAttr('disabled');
    });    
  } else {
    $(".concordancia-button").removeAttr('disabled');
  }
}

var visible = false;
var masMenosDetalles = function() {
  $(".tabla-concordancia").toggle("slow");  
  if(!visible){
    visible = true;
    $(".mas-menos-detalles").empty().html('<i class="fa fa fa-chevron-circle-down"></i> Menos Detalles');
  } else {
    visible = false;
    $(".mas-menos-detalles").empty().html('<i class="fa fa fa-chevron-circle-up"></i> Menos Detalles');
  }
}