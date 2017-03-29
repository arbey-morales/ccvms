<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">CLUES</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Ubicación</th>
            <th class="text-left">Jurisdición</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clues as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->clues }}</td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->localidad->nombre }}, {{ $item->municipio->nombre }}</td>
                <td class="text-left">{{ $item->jurisdiccion->nombre }}</td>
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
        </tr>
    </tfoot>
</table>

