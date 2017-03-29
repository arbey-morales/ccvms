<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Clave</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Municipio</th>
        </tr>
    </thead>
    <tbody>
        @foreach($localidades as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->clave }}</td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->municipio->nombre }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Clave</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Municipio</th>
        </tr>
    </tfoot>
</table>

