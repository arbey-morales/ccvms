<table id="data-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">CURP</th>
            <th class="text-left">Nacimiento</th>
            <th class="text-left">Dirección</th>
            <th class="text-left">Unidad de salud</th>
            <th class="text-left col-md-1"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$item)
            @if($key<=0)
                <tr>
                    <td colspan="7" style="background-color:#E0E0E0; font-size:x-large; color:black;"> 
                        {{ $item->mun_nombre }}
                    </td>
                </tr>
                <tr>
                    <td colspan="7" style="background-color:#F0F0F0; font-size:large; color:black;"> 
                    {{ $item->clu_clues }} - {{ $item->clu_nombre }}
                    </td>
                </tr>                
            @else 
                @if($item->municipios_id!=$data[$key - 1]->municipios_id)
                    <tr>
                        <td colspan="7" style="background-color:#E0E0E0; font-size:x-large; color:black;"> 
                            {{ $item->mun_nombre }}
                        </td>
                    </tr>
                @endif
                @if($item->clues_id!=$data[$key - 1]->clues_id)
                    <tr>
                        <td colspan="7" style="background-color:#F0F0F0; font-size:large; color:black;"> 
                        {{ $item->clu_clues }} - {{ $item->clu_nombre }}
                        </td>
                    </tr>
                @endif
            @endif
            <tr id="{{ $item->id }}" data-id="{{ $item->id }}" data-nombre="{{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}" data-toggle="tooltip" data-placement="top" data-original-title="{{$item->usuario_id}} / {{$item->created_at}}">
                <td class="text-center"><strong> {{ ++$key }} </strong></td>
                <td class="text-left"><a class="btn btn-default" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id) }}" class="button">@if($item->genero=='M') <i class="fa fa-male" style="color:#4d81bf; font-size:large;"></i> @endif @if($item->genero=='F') <i class="fa fa-female" style="color:#ed1586; font-size:large;"></i> @endif </a>  {{ $item->apellido_paterno }} {{ $item->apellido_materno }} {{ $item->nombre }} </td>
                <td class="text-left"><strong>{{ $item->curp }}</strong></td>
                <td class="text-left">{{$item->fecha_nacimiento}}</td>
                <td class="text-left">{{ $item->calle }} {{ $item->numero }}, {{ $item->col_nombre }}, {{ $item->loc_nombre }}, {{ $item->mun_nombre }} </td>
                <td class="text-left"> <strong>{{$item->clu_clues}}</strong>, {{$item->clu_nombre}}</td>
                <td class="text-center col-md-1">
                    <!--<a class="btn btn-success" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id) }}" class="button"> <i class="fa fa-info-circle"></i> </a>-->
                    <a class="btn btn-primary" href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}" class="button"> <i class="fa fa-edit"></i> </a>
                    <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target=".bs-example-modal-lg"> <i class="fa fa-trash"></i></button>
                    <!--<a class="btn btn-danger btn-delete" href="#" class="button"> <i class="fa fa-trash"></i> </a>-->
                </td>
            </tr>
        @endforeach
    </tbody>
</table>



