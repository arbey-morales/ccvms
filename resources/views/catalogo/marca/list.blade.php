<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Creado por</th>
            <th class="text-left">Creado el</th>
            <th class="text-left">Últ. actualización</th>
            <th class="text-left"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->usuario_id }}</td>
                <td class="text-left">{{ $item->created_at }}</td>
                <td class="text-left">{{ $item->updated_at }}</td>
                <td class="text-center col-md-1">                   
                    <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-refresh"></i> </a>
                    <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

