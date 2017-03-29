<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Año</th>
            <th class="text-left">Descripción</th>
            <th class="text-left"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($esquemas as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->id }}</td>
                <td class="text-left">{{ $item->descripcion }}</td>
                <td class="text-center col-md-1"> <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id) }}" class="button"> <i class="fa fa-info"></i> </a> </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Año</th>
            <th class="text-left">Descripción</th>
            <th class="text-left"></th>
        </tr>
    </tfoot>
</table>

