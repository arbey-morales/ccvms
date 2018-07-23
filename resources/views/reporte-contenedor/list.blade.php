<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">Folio</th>
            <th class="text-left">Cont. Serie</th>
            <th class="text-left">Reportó</th>
            <th class="text-left">Posible falla</th>
            <th class="text-left">Observación</th>
            <th class="text-left">Estatus</th>
            @role('root|red-frio')<th class="text-left"> </th>@endrole
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            <tr id="{{ $item->id }}" data-id="{{ $item->id }}">
                <td class="text-left">{{ $item->folio }}</td>
                <td class="text-left">{{ $item->contenedor->serie }}</td>
                <td class="text-left">{{ $item->reporto }} / {{ $item->fecha }} / {{ $item->hora }}</td>
                <td class="text-left">{{ $item->fallaContenedor->descripcion }}</td>  
                <td class="text-left">{{ $item->observacion }}</td>  
                <td class="text-left">
                    <ul class="list-inline prod_color">
                        @if($item->estatus_reporte==1) <li>
                                            <div class="color bg-orange"></div>
                                        </li><!--<a class="btn btn-default"> <i class="fa fa-flag-o green"></i> Ini. </a>--> @endif
                        @if($item->estatus_reporte==2) <li>
                                            <div class="color bg-blue"></div>
                                        </li><!--<a class="btn btn-primary"> <i class="fa fa-cogs red"></i> Proc. </a>--> @endif
                        @if($item->estatus_reporte==3) <li>
                                            <div class="color bg-green"></div>
                                        </li><!--<a class="btn btn-primary"> <i class="fa fa-flag-checkered green"></i> Term. </a>--> @endif
                    </ul>
                </td>    
                @role('root|red-frio')
                    <td class="text-center col-md-2">                   
                        <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-flash"></i> Seguimiento</a>
                        
                    <!-- <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button> -->
                    </td>
                @endrole
            </tr>
        @endforeach
    </tbody>
</table>

