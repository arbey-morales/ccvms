@extends('app')
@section('title')
    Inicio
@endsection
@section('content') 
    <div class="container">
        <div class="col-md-4 col-md-offset-4 text-center" style="padding-top:100px;">
            <!--<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="/images/img.jpg" alt="">
                <h1>{{ Auth::user()->name }} <small>Bienvenido!</small></h1>
            </a>-->
                        <div class="x_panel">
                          <div class="x_content">

                            <div class="flex">
                              <ul class="list-inline widget_profile_box">
                                <li>
                                  <a>
                                    <i class="fa fa-lock"></i>
                                  </a>
                                </li>
                                <li>
                                  <img src="{{ url('storage/user/profile/'.Auth::user()->foto) }}" alt="{{ Auth::user()->name }}" class="img-circle profile_img">
                                </li>
                                <li>
                                  <a>
                                    <i class="fa fa-unlock"></i>
                                  </a>
                                </li>
                              </ul>
                            </div>
                            <br>
                            <p class="lead">Nombre: <strong>{{ Auth::user()->nombre }} {{ Auth::user()->paterno }} {{ Auth::user()->materno }}</strong> </p>
                            <br>
                            <p class="lead">Roles</p>
                            @role('root') <button type="button" class="btn btn-danger btn-large"> <i class="fa fa-cogs"></i> Root </button> @endrole
                            @role('admin') <button type="button" class="btn btn-success btn-large"> <i class="fa fa-user"></i> Admin </button> @endrole
                            @role('captura') <button type="button" class="btn btn-info btn-large"> <i class="fa fa-keyboard-o"></i> captura </button> @endrole
                            <br>

                            <div class="table-responsive">
                              <table class="table">
                                <tbody>
                                <!-- Roles -->
                                  <tr>
                                    <th>Fecha de registro:</th>
                                    <td>{{ Auth::user()->creadoAl }} </td>
                                  </tr>
                                  <tr>
                                    <th>Última modificación:</th>
                                    <td>{{ Auth::user()->modificadoAl }} </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
        </div>
    </div>
@endsection