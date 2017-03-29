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
    <!--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>-->
    @show
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
            
               <a href="{{ url('/') }}" class="site_title"> <img src="{{ url('images/salud-mesoamerica.png') }}" alt="..." class="img-rounded" width="120px"> <span>CCVMS</span></a>
            </div>

            <div class="clearfix"></div>
			
            <!-- menu profile quick info -->
            @include('partials.layout.profile')
            <!-- /menu profile quick info -->
            <br />
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

    <!-- My Scripts -->
    @yield('my_scripts')

    <!-- Custom Theme Scripts -->
    {!! Html::script('assets/build/js/custom.min.js') !!}

  </body>
</html>