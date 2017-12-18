@extends('app')
@section('title')
    Esquemas
@endsection
@section('my_styles')
    <!-- Datatables -->
    {!! Html::style('assets/mine/css/datatable-bootstrap.css') !!}
    {!! Html::style('assets/mine/css/responsive.bootstrap.min.css') !!}
@endsection
@section('content') 
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-calendar"></i> Esquemas <i class="fa fa-angle-right text-danger"></i><small> Detalles </small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('catalogo.esquema.index') }}">
                        <i class="fa fa-chevron-circle-left" style="font-size:30px;"></i>
                    </a>
                </li>
                <li>
                    <a class="collapse-link">
                        
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <h2 id="title-esquema"> Esquema</h2>
            <div class="x_content" id="content-esquema">
                <div class="col-md-12 text-center text-info"> <i class="fa fa-info-circle text-danger" style="font-size:x-large;"></i> <h3>Sin esquema</h3></div>
            </div>
        </div>
    </div>

    <!-- Modal detalles dosis -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header alert">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title" id="myModalLabel" style="color:white !important;"> <i class="fa fa-exclamation-circle" style="padding-right:15px;"></i>  Información de <span id="dosis" ></span> </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text text-success" style="font-size:x-large; font-weight:bold;">Esquema ideal</h2>
                        <h3 id="intervalos"></h3>
                        <h3 id="fecha-ideal"></h3>
                        <h3 id="dias-anticipacion"></h3>
                    </div>
                    <div class="col-md-6">
                        <h2 class="text text-danger" style="font-size:x-large; font-weight:bold;">Esquema desfasado</h2>
                        <h3 id="intervalos-ni"></h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-lg btn-detalle" data-dismiss="modal">Entendido!</button>
                <!--<button type="button" class="btn btn-danger btn-lg btn-confirm-delete" data-dismiss="modal">Sí, eliminar</button>-->
            </div>

            </div>
        </div>
    </div>
@endsection
@section('my_scripts')
    <!-- Datatables -->
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.js') !!}
    {!! Html::script('assets/vendors/datatables.net/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/dataTables.responsive.min.js') !!}
    {!! Html::script('assets/mine/js/dataTables/responsive.bootstrap.js') !!}
    <script type="text/javascript">
        var aplicaciones = $.parseJSON(escaparCharEspeciales('{{json_encode($data)}}'));
        var esquema = $.parseJSON(escaparCharEspeciales('{{$esquema}}'));
        setTimeout(function() {        
            generarEsquema(aplicaciones);
        }, 500);

        // CAMBIA ETIQUETA
        function cambiaEtiqueta(id,anio,mes,dia){
            $("#intervalo_text"+id).html(obtieneIntervalo(anio,mes,dia));
        }

        // GENERA EL ESQUEMA DENTRO DEL DIV-CONTENT
        function generarEsquema(aplicaciones){  
            $('#content-esquema').empty();  
            $('#title-esquema').empty().html('<a class="btn btn-danger btn-lg"><i class="fa fa-calendar"></i> '+esquema.descripcion+'</a>');        
            ultimo_esquema = aplicaciones; // LAS VALIDACIONES DEL ESQUEMA ESTÁN AQUÍ
            var key_plus = 0;
            $.each(aplicaciones, function( key, ve ) {
                key_plus++;                                  
                if(aplicaciones.length - 1 > key){ // último registro de esquemasvacunas
                    $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #'+ve.color_rgb+'; background-color:#'+ve.color_rgb+' !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipoAplicacion(ve.tipo_aplicacion)+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#'+ve.color_rgb+'" id="intervalo_text'+ve.id+'">'+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+'</span> </span> </div> </div> <div class="row"> <div class="bt-flabels__wrapper"> <span class="col-md-12 text-primary" style="text-align:center; padding:3px; background-color:white; font-size:x-large;">  __-__-_____ </span> </div> </div> </div> </div>');
                    if(aplicaciones[key_plus].fila != ve.fila){
                        $('#content-esquema').append('<div class="clearfix"></div>');
                    }
                } else {
                    $('#content-esquema').append('<div class="animated flipInY col-md-2 col-xs-12"><br> <div class="tile-stats" style="color:white; margin:0px; padding:3px; border:solid 2px #'+ve.color_rgb+'; background-color:#'+ve.color_rgb+' !important;"> <div class="row"> <div class="col-md-12" onClick="verDetalles('+key+')" data-toggle="modal" data-target=".bs-example-modal-lg"> <span style="font-size:large;font-weight:bold;"> '+ve.clave+' <small> '+tipoAplicacion(ve.tipo_aplicacion)+' </small> </span> <span style="font-size:large;" class="pull-right"> <span class="badge bg-white" style="color:#'+ve.color_rgb+'" id="intervalo_text'+ve.id+'">'+obtieneIntervalo(ve.etiqueta_ideal_anio,ve.etiqueta_ideal_mes,ve.etiqueta_ideal_dia)+'</span> </span> </div> </div> <div class="row"> <div class="bt-flabels__wrapper"> <span class="col-md-12 text-primary" style="text-align:center; padding:3px; background-color:white; font-size:x-large;">  __-__-_____ </span> </div> </div> </div> </div>');
                }
            });
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

        // REEMPLAZA LO QUE SE LE PIDA EN UNA CADENA
        function reemplazarTodo(str, find, replace) {
            return str.replace(new RegExp(find, 'g'), replace);
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
    </script>
@endsection