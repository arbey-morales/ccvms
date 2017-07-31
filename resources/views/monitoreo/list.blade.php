
@foreach($data as $key=>$item)
    <div class="col-md-6 col-sm-6 col-xs-12 profile_details">
        <div class="well profile_view">
            <div class="col-sm-12">
                <h4 class="brief">
                    @foreach($item->rolesuser as $k=>$rol) 
                        @if($rol->role->slug=='admin')
                            <strong>OFICINA CENTRAL</strong> 
                        @else
                            <strong>CAPTURA:</strong> <i> {{ $item->jurisdiccion->clave }}</i> {{ $item->jurisdiccion->nombre }}
                        @endif
                    @endforeach 
                </h4>
                <div class="left col-xs-7">
                    <h2>{{$item->nombre}} {{$item->paterno}} {{$item->materno}}</h2>
                    <ul class="list-unstyled">
                    <li><i class="fa fa-building"></i> Dirección: {{$item->direccion}}</li>
                    <li><i class="fa fa-at"></i> Email: {{$item->email}}</li>
                    </ul>
                    <p><strong>Creación: </strong>{{$item->creadoAl}}</p>
                    <p><strong>Actualización: </strong>{{$item->modificadoAl}}</p>
                </div>
                <div class="right col-xs-5 text-center">
                    <img src="@if($item->foto) {{ url('storage/user/profile/'.$item->foto) }}  @else {{ url('storage/user/profile/user-default.png') }} @endif" alt="..." class="img-circle img-responsive" width="135px"/>
                </div>
            </div>
            <div class="col-xs-12 bottom text-center">
                <div class="col-xs-12 col-sm-6 emphasis">
                    <p class="ratings">
                    <a>  <span href="#" class="label label-success">Hoy: <strong style="font-size:xx-large;" class="text-primary">{{$item->hoy}}</strong></span> <span href="#" class="label label-warning">Ayer: <strong class="text-primary" style="font-size:xx-large;">{{$item->ayer}}</strong></span> <span href="#" class="label label-danger">Última. Semana: <strong class="text-primary" style="font-size:xx-large;">{{$item->ultima_semana}}</strong></span></a>
                </div>
            <div class="col-xs-12 col-sm-6 emphasis">
                <!--<button type="button" class="btn btn-success btn-large">
                    Otros filtros
                </button>-->
                <a href="{{ url('usuario/'.$item->id) }}" type="button" class="btn btn-info btn-large">
                    <i class="fa fa-user"> </i> Ver perfil
                </a>
            </div>
            </div>
        </div>
    </div>
@endforeach

