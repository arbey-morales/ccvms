<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Dirección</th>
            <th class="text-left">Email</th>
            <th class="text-left">Jurisdicción</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr data-id="{{ $item->id }}" class="@if($item->activo === 0) ? warning @endif" data-toggle="tooltip" data-placement="top" title="@if($item->activo===0) Propietario Inactivo @endif">
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left">{{ $item->nombre }} {{ $item->paterno }} {{ $item->materno }}</td>
                <td class="text-left">{{ $item->direccion }}</td>
                <td class="text-left">{{ $item->email }}</td>
                <td class="text-left"><strong>{{ $item->jurisdiccion->clave }}</strong> {{ $item->jurisdiccion->nombre }}</td>
                <td class="text-center col-md-1">
                    @include('partials.layout.actions')
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Dirección</th>
            <th class="text-left">Email</th>
            <th class="text-left">Jurisdicción</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </tfoot>
</table>

