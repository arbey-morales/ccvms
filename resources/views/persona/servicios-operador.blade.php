<table id="datatable-responsive" class="table table-striped projects">
    <thead>
        <tr>
            <th>U Móvil</th>
            <th>Solicitud</th>
            <th>Ubicación</th>
            <th>Status</th>
            <th>T/Llegada</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($operador->servicios as $servicio)
            <tr class="@if($servicio->cancelado==1) ? warning @endif">
                <td>
                    <h3>{{ $servicio->unidad->numeroMovil }}</h3>
                </td>
                <td>
                    <a>{{ $servicio->cliente->nombreCompleto }}</a>
                    <br />
                    <small>{{ $servicio->fechaSolicitud }} hrs</small>
                    </br>
                    <h5>Por: {{ $servicio->linea->nombre }}</h5>
                </td>
                <td class="project_progress">
                    {{ $servicio->ubicacion->direccion }}, {{ $servicio->ubicacion->localidad->nombre }}, {{ $servicio->ubicacion->localidad->municipio->nombre }}
                </td>
                <td>
                    @if($servicio->fechaLlegada==NULL) 
                        <button type="button" class="btn btn-warning btn-sm"> <i class="fa fa-frown-o"></i> Aún no llega </button>
                    @else 
                        <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-smile-o"></i> Llegó: {{ $servicio->fechaLlegada }} </button>
                        @if($servicio->fechaAbordaje==NULL) 
                            <button type="button" class="btn btn-warning btn-sm"> <i class="fa fa-frown-o"></i> Sin abordar </button>
                        @else 
                            <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-smile-o"></i> Abordó: {{ $servicio->fechaAbordaje }} </button>
                            @if($servicio->fechaFin==NULL) 
                                <button type="button" class="btn btn-warning btn-sm"> <i class="fa fa-frown-o"></i> Aún no termina </button>
                            @else 
                                <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-smile-o"></i> Finalizó: {{ $servicio->fechaFin }} </button>
                            @endif
                        @endif
                    @endif

                    @if($servicio->cancelado==1) 
                        <button type="button" class="btn btn-danger btn-sm"> <i class="fa fa-gavel"></i> Cancelado </button>
                    @endif
                    
                </td>
                <td>
                    <button type="button" class="btn btn-info btn-xs"> <i class="fa fa-clock-o"></i> {{ $servicio->tiempoLlegada }} min </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>