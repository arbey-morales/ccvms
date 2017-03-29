<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RadioTel | Error {{$error}}</title>


    <!-- Bootstrap -->
    {!! Html::style('assets/vendors/bootstrap/dist/css/bootstrap.min.css') !!}
    <!-- Font Awesome -->
    {!! Html::style('assets/vendors/font-awesome/css/font-awesome.min.css') !!}

    <!-- Custom Theme Style -->
    {!! Html::style('assets/build/css/custom.min.css') !!}

    {!! Html::style('assets/mine/css/page-errors.css') !!}
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">{{$error}}</h1>
              <h2><i class="fa fa-{{ $icon }} text-danger"></i> {{$title}}</h2>
              <p>{{$message}}
              </p>
              <div class="mid_center">
                <a href="{{ url('home') }}">
                  <img src="{{ url('images/anger.png') }}" class="img-responsive" alt="Error">
                </a>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    {!! Html::script('assets/vendors/jquery/dist/jquery.min.js') !!}
    <!-- Bootstrap -->
    {!! Html::script('assets/vendors/bootstrap/dist/js/bootstrap.min.js') !!}
    
     <!-- Custom Theme Scripts -->
    {!! Html::script('assets/build/js/custom.min.js') !!}
  </body>
</html>

