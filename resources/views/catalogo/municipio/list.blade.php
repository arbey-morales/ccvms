<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">clave</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Jurisdiccion</th>
        </tr>
    </thead>
    <tbody>
        @foreach($municipios as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->clave }}</td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->jurisdiccion->nombre }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">clave</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Jurisdiccion</th>
        </tr>
    </tfoot>
</table>

