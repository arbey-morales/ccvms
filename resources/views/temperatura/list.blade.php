<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Contenedor</th>
            <th class="text-left">Fecha</th>
            <th class="text-left">Hora</th>
            <th class="text-left">Temperatura</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr id="{{ $item->id }}" data-id="{{ $item->id }}" data-nombre="{{ $item->contenedores_id }}" data-toggle="tooltip" data-placement="top" data-original-title="{{$item->usuario_id}} / {{$item->created_at}}">
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{$item->contenedores_id}}</td>
                <td class="text-left">{{ $item->fecha }}</td>
                <td class="text-left">{{ $item->hora }}</td>
                <td class="text-left">{{ $item->temperatura }} </td>                
            </tr>
        @endforeach
    </tbody>
</table>

