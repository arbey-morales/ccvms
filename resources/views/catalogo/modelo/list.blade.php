<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Marca</th>
            <th class="text-left">Slug</th>
        </tr>
    </thead>
    <tbody>
        @foreach($modelos as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->marca->nombre }}</td>
                <td class="text-left">{{ $item->slug }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Marca</th>
            <th class="text-left">Slug</th>
        </tr>
    </tfoot>
</table>

