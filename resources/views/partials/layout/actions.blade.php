<ul id="actions" class="nav">
    <li class="">
        <a id="actions-modulo" href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <span class=" fa fa-plus-square"></span>
        </a>
        <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li>
                <a href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id) }}">
                    <i class="fa fa-info-circle pull-right text-success" style="padding-top:4px;"></i>
                    Ver
                </a>
            </li>
            <li>
                <a href="{{ url(Route::getCurrentRoute()->getPath().'/'.$item->id.'/edit') }}">
                    <i class="fa fa-edit pull-right text-info" style="padding-top:4px;"></i>
                    Editar
                </a>
            </li>
            <li>
                <a href="" class="btn-delete">
                    <i class="fa fa-trash pull-right text-danger" style="padding-top:4px;"></i>
                    Borrar 
                </a>
            </li>
        </ul>
    </li>
</ul>