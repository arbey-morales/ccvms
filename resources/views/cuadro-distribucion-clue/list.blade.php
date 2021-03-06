<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">CURP</th>
            <th class="text-left"> Nacimiento</th>
            <th class="text-left">Dirección</th>
            <th class="text-left">Unidad de salud</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($personas as $key=>$item)
            <tr data-id="{{ $item->id }}" class="@if($item->activo === 0) ? warning @endif" data-toggle="tooltip" data-placement="top" title="@if($item->activo===0) Operador Inactivo @endif">
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left"><a class="btn btn-default" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id) }}" class="button">@if($item->genero=='M') <i class="fa fa-male" style="color:#4d81bf; font-size:large;"></i> @endif @if($item->genero=='F') <i class="fa fa-female" style="color:#ed1586; font-size:large;"></i> @endif </a> {{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}</td>
                <td class="text-left"><strong>{{ $item->curp }}</strong></td>
                <td class="text-left">{{$item->fecha_nacimiento}}</td>
                <td class="text-left">{{ $item->calle }} {{ $item->numero }}, {{ $item->colonia }}, {{ $item->localidad->nombre }}, {{ $item->municipio->nombre }} </td>
                <td class="text-left"><strong>{{$item->clue->clues}}</strong>, {{$item->clue->nombre}}</td>
                <td class="text-center col-md-1">
                    <!--<a class="btn btn-success" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id) }}" class="button"> <i class="fa fa-info-circle"></i> </a>-->
                    <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-refresh"></i> </a>
                    
                    <a class="btn btn-danger btn-delete" href="#" class="button"> <i class="fa fa-trash"></i> </a>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">CURP</th>
            <th class="text-left"> Nacimiento</th>
            <th class="text-left">Dirección</th>
            <th class="text-left">Unidad de salud</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </tfoot>
</table>

