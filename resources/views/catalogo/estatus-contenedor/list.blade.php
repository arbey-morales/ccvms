<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">Simbología</th>
            <th class="text-left">Descripción</th>
            <th class="text-left">Creado al</th>
            <th class="text-left">Última modificación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr>
                <td class="text-center"><button type="button" class="btn btn-round" style="background-color:{{ $item->color }}; padding:8px 10px;">  <i class="fa {{ $item->icono }}" style="color:white; font-size:x-large;"></i>  </button></td>
                <td class="text-left">{{ $item->descripcion }}</td>
                <td class="text-left">{{ $item->created_at }}</td>
                <th class="text-left">{{ $item->updated_at }}</th>
            </tr>
        @endforeach
    </tbody>
</table>

