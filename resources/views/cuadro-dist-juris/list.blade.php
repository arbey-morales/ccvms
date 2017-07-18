<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">Folio y Fecha</th>
            <th class="text-left">Descripción</th>
            <th class="text-left">Pedido</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top">
                <td class="text-left"><a class="btn btn-success" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id) }}" class="button"> <strong>{{ $item->folio }}</strong> </a> {{$item->fecha}}</td>
                <td class="text-left">{{ $item->descripcion }} </td>
                <td class="text-left"><strong>{{ $item->pedido_estatal->fecha }}</strong> / {{ $item->pedido_estatal->descripcion }} / {{ $item->pedido_estatal->proveedor->nombre }}</td>
                
                <td class="text-center col-md-1">                   
                    <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-refresh"></i> </a>
                    <a class="btn btn-danger btn-delete" href="#" class="button"> <i class="fa fa-trash"></i> </a>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">Folio y Fecha</th>
            <th class="text-left">Descripción</th>
            <th class="text-left">Pedido</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </tfoot>
</table>

