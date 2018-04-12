<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">Folio</th>
            <th class="text-left">Descripción</th>
            <th class="text-left">Año</th>
            <th class="text-left">Fecha</th>
            <th class="text-left">Estatus</th>
            <th class="text-left">Unidad de salud</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr data-id="{{ $item->id }}">
                <td class="text-center"> </td>
                <td class="text-left"> </td>
                <td class="text-left"> </td>
                <td class="text-left"> </td>
                <td class="text-left"> </td>
                <td class="text-left"> </td>
                <td class="text-center col-md-1">
                    <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-refresh"></i> </a>
                    <a class="btn btn-danger btn-delete" href="#" class="button"> <i class="fa fa-trash"></i> </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

