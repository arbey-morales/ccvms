<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">CLUES</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Ubicación</th>
            <th class="text-left">Jurisdición</th>
            @role('root|admin')<th class="text-left"> </th>@endrole
        </tr>
    </thead>
    <tbody>
        @foreach($clues as $key=>$item)
            <tr id="{{ $item->id }}" data-id="{{ $item->id }}" data-nombre="{{ $item->clues }}, {{ $item->nombre }}" data-toggle="tooltip" data-placement="top">
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->clues }}</td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->localidad->nombre }}, {{ $item->municipio->nombre }}</td>
                <td class="text-left">{{ $item->jurisdiccion->nombre }}</td>
                @role('root|admin')<td class="text-center col-md-1">                   
                    <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-refresh"></i> </a>
                    <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button>
                </td>@endrole
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">CLUES</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Ubicación</th>
            <th class="text-left">Jurisdición</th>
            @role('root|admin')<th class="text-left"> </th>@endrole
        </tr>
    </tfoot>
</table>

