@extends('app')
@section('title')
    Usuarios
@endsection
@section('my_styles')
@endsection
@section('content') 
    <div class="x_panel">
        <div class="x_title">
        <h2><i class="fa fa-user"></i> Usuarios <i class="fa fa-angle-right text-danger"></i><small> Detalles </small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="" href="{{ route('usuario.index') }}">
                        <i class="fa fa-long-arrow-left"></i>
                    </a>
                </li>
                <li>
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab_content1" id="general-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos Generales</a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#tab_content2" role="tab" id="roles-tab" data-toggle="tab" aria-expanded="false">Roles</a>
                    </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="perfil-tab">
                         <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>{{ $usuario->nombre }} {{ $usuario->paterno }} {{ $usuario->materno }}</h2>
                                        <!--<ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                            <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Settings 1</a>
                                            </li>
                                            <li><a href="#">Settings 2</a>
                                            </li>
                                            </ul>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                        </ul>-->
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">

                                        <div class="col-md-7 col-sm-7 col-xs-12">
                                            <div class="product-image">
                                                <img src="@if($usuario->foto) {{ url('storage/user/profile/'.$usuario->foto) }}  @else {{ url('storage/user/profile/user-default.png') }} @endif" alt="..." />
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-sm-5 col-xs-12" style="border:0px solid #e5e5e5;">

                                            <h3 class="prod_title">Más detalles</h3>
                                            <div class="">
                                                <ul class="list-unstyled user_data">
                                                    <li><i class="fa fa-map-marker user-profile-icon"></i> <strong>{{ $usuario->direccion }}</strong>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-envelope user-profile-icon"></i> Email: {{ $usuario->email }}
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-calendar user-profile-icon"></i> Registro: {{ $usuario->creadoAl }}
                                                    </li>
                                                    <li>
                                                        @if($usuario->activo==1) <i class="fa fa-heart text-danger user-profile-icon"></i> En actividad! @else
                                                        <i class="fa fa-heartbeat text-dafault user-profile-icon"></i> Usuario Inactivo
                                                        @endif
                                                    </li>
                                                </ul>
                                                <br>
                                                    @permission('update.usuarios')<a href="{{ route('usuario.edit', $usuario->id) }}" class="btn btn-success"><i class="fa fa-edit m-right-xs"></i> Hacer Cambios</a>@endpermission
                                                <br />
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="roles-tab">
                        <br>
                        @if(count($usuario->rolesuser)>0)
                            <div class="col-md-12">
                                <ul class="list-unstyled timeline">
                                @foreach ($usuario->rolesuser as $key => $value)
                                    <li>
                                        <div class="block">
                                            <div class="tags">
                                                <a href="" class="tag">
                                                    <span>{{$value->role->name}}</span>
                                                </a>
                                            </div>
                                            <div class="block_content">
                                                <h2 class="title">
                                                    <a>{{$value->role->name}}</a>
                                                </h2>
                                            <div class="byline">
                                                <span>{{$value->role->created_at}}</span>  <a><i class="fa {{$value->icon}}"></i></a>
                                            </div>
                                                <h4 data-id="{{$value->role->id}}" class="text-info"> {{$value->role->description}}</h4>
                                                @if(count($value->role->permissions)>0)
                                                    @foreach ($value->role->permissions as $k => $prms)
                                                        <strong>{{++$k}}</strong>.- {{ $prms->permission->name }}
                                                    @endforeach
                                                @else
                                                    <h5>Sin permisos...</h5>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="col-md-12 text-primary"> <h4>Sin Roles...</h4> </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('my_scripts')

@endsection