<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RadioTel | Acceso</title>

    {!! Html::style('assets/css/bootstrap.min.css') !!}
    {!! Html::style('assets/css/font-awesome.min.css') !!}
    {!! Html::style('assets/css/animate.min.css') !!}
  
    {!! Html::style('assets/css/custom.min.css') !!}
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <div class="container">
                @if (Session::has('errors'))
                    <div class="alert alert-warning" role="alert">
                    <ul>
                        <strong>Oops! algo va mal: </strong>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
          </div>
          <section class="login_content">
            {!! Form::open(['route' => 'auth/login', 'class' => 'form']) !!}
             <h1>Acceso RT V1.0</h1>
              <div class="form-group">
                {!! Form::label('email', 'Correo Electrónico') !!}
                {!! Form::email('email', '', ['class'=> 'form-control', 'id' => 'email', 'required' => 'required', 'placeholder' => 'correo electrónico']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('password', 'Contraseña') !!}
                {!! Form::password('password', ['class'=> 'form-control', 'id' => 'password', 'required' => 'required', 'placeholder' => 'Contraseña']) !!}
              </div>
              <div>
                {!! Form::submit('Ingresar',['class' => 'btn btn-default']) !!}
                <a class="reset_pass" href="#">olvide mi contraseña!</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />
                <div>
                  <h1><i class="fa fa-taxi"></i> RadioTel!</h1>
                  <p>©2016 Todos los derechos reservados. RadioTel</p>
                </div>
              </div>
            {!! Form::close() !!}
          </section>
        </div>
      </div>
    </div>
  </body>
</html>