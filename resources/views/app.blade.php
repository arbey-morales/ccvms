<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CCVMS | @yield('title') </title>

    <!-- Bootstrap -->
    {!! Html::style('assets/vendors/bootstrap/dist/css/bootstrap.min.css') !!}
    <!-- Font Awesome -->
    {!! Html::style('assets/vendors/font-awesome/css/font-awesome.min.css') !!}
    <!-- iCheck -->
    {!! Html::style('assets/vendors/iCheck/skins/flat/green.css') !!}
    <!-- bootstrap-progressbar -->
    {!! Html::style('assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') !!}
    <!-- jVectorMap -->
    <!--{!! Html::style('assets/app/css/maps/jquery-jvectormap-2.0.3.css') !!}-->
    <!-- PNotify -->
    {!! Html::style('assets/vendors/pnotify/dist/pnotify.css') !!}
    {!! Html::style('assets/vendors/pnotify/dist/pnotify.buttons.css') !!}
    {!! Html::style('assets/vendors/pnotify/dist/pnotify.nonblock.css') !!}

    <!-- My styles -->
    {!! Html::style('assets/mine/css/style.css') !!}
    @yield('my_styles')

    <!-- Custom Theme Style -->
    {!! Html::style('assets/build/css/custom.min.css') !!}

    <!-- Laravel styles -->
    @section('styles_laravel')
    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    @show
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
            
               <a href="{{ url('/') }}" class="site_title"> <img src="{{ url('images/sm.png') }}" alt="..." class="img-rounded" width="45px"> <span>CCVMS</span></a>
            </div>

            <div class="clearfix"></div>
			
            <!-- menu profile quick info -->
            @include('partials.layout.profile')
            <!-- /menu profile quick info -->
            <br />            
            <br>
            <!-- sidebar menu -->
            @include('partials.layout.sidebar')
            <!-- /sidebar menu -->
            <!-- /menu footer buttons -->
            @include('partials.layout.footerbtns')
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        @include('partials.layout.topnav')
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="container" style="margin-top:60px">
                <div class="row">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
          @include('partials.layout.footer')
        </footer>
        <!-- /footer content -->
      </div>
    </div>
    
    <!-- Scripts -->    
    <!-- jQuery -->
    {!! Html::script('assets/vendors/jquery/dist/jquery.min.js') !!}
    <!-- Bootstrap -->
    {!! Html::script('assets/vendors/bootstrap/dist/js/bootstrap.min.js') !!}
    <!-- FastClick -->
    {!! Html::script('assets/vendors/fastclick/lib/fastclick.js') !!}
    <!-- NProgress -->
    {!! Html::script('assets/vendors/nprogress/nprogress.js') !!}
    <!-- Chart.js -->
    {!! Html::script('assets/vendors/Chart.js/dist/Chart.min.js') !!}
    <!-- gauge.js -->
    <!--{!! Html::script('assets/vendors/gauge.js/dist/gauge.min.js') !!}-->
    <!-- bootstrap-progressbar -->
    {!! Html::script('assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') !!}
    <!-- iCheck -->
    {!! Html::script('assets/vendors/iCheck/icheck.min.js') !!}
    <!-- bootstrap-daterangepicker -->
    {!! Html::script('assets/app/js/moment/moment.min.js') !!}
    {!! Html::script('assets/app/js/moment/moment-timezone.js') !!}
    {!! Html::script('assets/app/js/moment/moment-with-locales.js') !!}
    {!! Html::script('assets/app/js/datepicker/daterangepicker.js') !!}    
    <!-- jQuery Masked -->
    {!! Html::script('assets/vendors/masked-input-plugin/masked-input.js') !!}
    <!-- Skycons -->
    {!! Html::script('assets/vendors/skycons/skycons.js') !!}
    <!-- Flot -->
    {!! Html::script('assets/vendors/Flot/jquery.flot.js') !!}
    {!! Html::script('assets/vendors/Flot/jquery.flot.pie.js') !!}
    {!! Html::script('assets/vendors/Flot/jquery.flot.time.js') !!}
    {!! Html::script('assets/vendors/Flot/jquery.flot.stack.js') !!}
    {!! Html::script('assets/vendors/Flot/jquery.flot.resize.js') !!}
    <!-- Flot plugins -->
    {!! Html::script('assets/app/js/flot/jquery.flot.orderBars.js') !!}
    {!! Html::script('assets/app/js/flot/date.js') !!}
    {!! Html::script('assets/app/js/flot/jquery.flot.spline.js') !!}
    {!! Html::script('assets/app/js/flot/curvedLines.js') !!}
    <!-- jVectorMap -->
    <!--{!! Html::script('assets/app/js/maps/jquery-jvectormap-2.0.3.min.js') !!}-->
    <!-- PNotify -->
    {!! Html::script('assets/vendors/pnotify/dist/pnotify.js') !!}
    {!! Html::script('assets/vendors/pnotify/dist/pnotify.buttons.js') !!}
    {!! Html::script('assets/vendors/pnotify/dist/pnotify.nonblock.js') !!}
    {!! Html::script('assets/vendors/pnotify/dist/pnotify.confirm.js') !!}

    <!-- Custom Theme Scripts -->
    {!! Html::script('assets/build/js/custom.min.js') !!}

    <script>
      var userId = '{{ Auth::user()->id }}';
      var URL = "{{ url() }}";
      var path = "{{ Request::path() }}";
      // LANZA EL NOTIFY
      function notificar(titulo,texto,tipo,retardo){
          new PNotify({
              title: titulo,
              text: texto,
              type: tipo,
              delay: retardo,
              animate_speed: 'fast',
              styling: 'bootstrap3'
          });
      }

        // HORA
        $.mask.definitions['H'] = "[0-2]";
        $.mask.definitions['h'] = "[0-9]";
        $.mask.definitions['I'] = "[0-5]";
        $.mask.definitions['i'] = "[0-9]";
        $.mask.definitions['S'] = "[0-5]";
        $.mask.definitions['s'] = "[0-9]";
        // FECHA NORMAL
        $.mask.definitions['Y'] = "[2]";
        $.mask.definitions['y'] = "[0-1]";
        $.mask.definitions['A'] = "[0-1]";
        $.mask.definitions['a'] = "[0-9]";
        $.mask.definitions['M'] = "[0-1]";
        $.mask.definitions['m'] = "[0-9]";
        $.mask.definitions['D'] = "[0-3]";
        $.mask.definitions['d'] = "[0-9]";

        // FECHA TUTOR
        $.mask.definitions['T'] = "[1-2]";
        $.mask.definitions['t'] = "[0,1,9]";
      
        $(document).ready(function(){
          $(".hora").mask("Hh:Ii:Ss", {placeholder: "__:__:__"});
          $(".fecha").mask("YyAa-Mm-Dd", {placeholder: "____-__-__"});
          $(".fecha-nacimiento").mask("Dd-Mm-YyAa", {placeholder: "__-__-____"});
          $(".fecha-nacimiento-tutor").mask("Dd-Mm-Ttaa", {placeholder: "__-__-____"});

          $(".numero").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
              // Allow: Ctrl/cmd+A
              (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
              // Allow: Ctrl/cmd+C
              (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
              // Allow: Ctrl/cmd+X
              (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
              // Allow: home, end, left, right
              (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
              e.preventDefault();
            }
          });
        });
    </script>

    @role('root|red-frio')
      <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
      <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('8e24f7266f6dbf39a74e', {
          cluster: 'us2',
          encrypted: true
        });
        
        // aca debe de existir una forma para obtener el usuario para abrir el canal
        var channel = pusher.subscribe('reporteC'+userId);
        var mensajes = ``;
        channel.bind('my-event', function(data) {
          var totalNotificaciones = Number($('.total-notificaciones').html()) + 1;
          $(".total-notificaciones").empty().html(totalNotificaciones);
          notificar(data.message.titulo, data.message.mensaje+' a las '+data.message.created_at+'<br><br> <a href="/reporte-contenedor/'+data.message.reportes_contenedores_id+'/edit" class="btn btn-sm btn-success"> <i class="fa fa-cogs"></i> Revisar </a> <a id="mcl" href="#mcl" onClick="leida('+data.message.reportes_contenedores_id+','+data.message.notificaciones_usuarios_id+')" class="btn btn-sm btn-danger"> <i class="fa fa-check"></i>  Leída </a> <a href="/reporte-contenedor" class="btn btn-sm btn-default pull-right"> <i class="fa fa-tasks"></i> Ver todas </a>','info',6000);
        });

         $(document).ready(function(){
          misNotificaciones();
        });

        function leida(idReporteContenedor,idNotificacionUsuario){
          $.ajax({
            url: URL+'/reporte-contenedor/leida/'+idNotificacionUsuario,
            type : "GET",
            dataType : 'json',
            data : {},
            success : function(response, status) {
              if(response.status==200){
                var totalNotificaciones = Number($('.total-notificaciones').html()) - 1;
                $(".total-notificaciones").empty().html(totalNotificaciones);
                PNotify.removeAll();
                // $(".total-notificaciones").empty().html(response.total_n); 
                // console.log(URL,path);
                // if(response.total_n>0 && path!="reporte-contenedor" && path!="reporte-contenedor/create")
                //   notificar('Información','Tiene un total de '+response.total_n+' notificaciones sin leer <br><br> <a href="/reporte-contenedor" class="btn btn-sm btn-default pull-right"> Ver todas </a>','info',8000);
              }
              if(response.status==500){
                notificar('Error',response.error,'warning',200);
              }
              if(response.status==304){
                notificar('Info',response.messages,'warning',200);
              }
            },
            error: function(xhr, resp, text) {
                console.log(xhr, resp, text);
            }
          });
        }

        function misNotificaciones(){
          $.ajax({
            url: URL+'/notificacion',
            type : "GET",
            dataType : 'json',
            data : $("#form").serialize(),
            success : function(response, status) {
              if(response.status==200){
                $(".total-notificaciones").empty().html(response.total_n); 
                console.log(URL,path);
                if(response.total_n>0 && path!="reporte-contenedor" && path!="reporte-contenedor/create")
                  notificar('Información','Tiene un total de '+response.total_n+' notificaciones sin leer <br><br> <a href="/reporte-contenedor" class="btn btn-sm btn-default pull-right"> Ver todas </a>','info',8000);
              }
            },
            error: function(xhr, resp, text) {
                console.log(xhr, resp, text);
            }
          });
        }

      </script>
    @endrole

    <!-- My Scripts -->
    @yield('my_scripts')

  </body>
</html>