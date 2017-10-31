<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">C.P.</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Municipio</th>
            <th class="text-left">Ciudad</th>
            @role('root|admin')<th class="text-left"> </th>@endrole
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr id="{{ $item->id }}" data-id="{{ $item->id }}" data-nombre="{{ $item->nombre }}" data-toggle="tooltip" data-placement="top">
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->codigo_postal }}</td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->mun_nombre }}</td>
                <td class="text-left">{{ $item->ciu_nombre }}</td>
                @role('root|admin')<td class="text-center col-md-1">                   
                    <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-refresh"></i> </a>
                    <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button>
                </td>@endrole
            </tr>
        @endforeach
    </tbody>
</table>

