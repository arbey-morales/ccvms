<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">AGEB</th>
            <th class="text-left">Municipio</th>
            <th class="text-left">Localidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach($agebs as $key=>$item)
            <tr>
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->id }}</td>
                <td class="text-left">{{ $item->municipio->nombre }}</td>
                <td class="text-left">{{ $item->localidad->nombre }}</td>
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

