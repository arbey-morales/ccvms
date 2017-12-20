<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">AGEB</th>
            <th class="text-left">Municipio</th>
            <th class="text-left">Localidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->id }}</td>
                <td class="text-left">{{ $item->municipio }}</td>
                <td class="text-left">{{ $item->localidad }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">AGEB</th>
            <th class="text-left">Municipio</th>
            <th class="text-left">Localidad</th>
        </tr>
    </tfoot>
</table>

