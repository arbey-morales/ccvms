<ul class="list-unstyled timeline">
    <?php $col = 0; ?>
    @foreach($data2 as $key=>$item)
        <?php if(count($item->usuarios)>$col){$col = count($item->usuarios);} ?>
    @endforeach
    <?php $cols = round(100 / ($col)); ?>
    @foreach($data2 as $key=>$item)    
        <li>
            <div class="block">
            <div class="tags">
                <a href="" class="tag">
                    <span>{{$item->nombre}}</span>
                </a>
            </div>
            <div class="block_content">
                <div class="row">
                    <div class="col-md-1" style="font-size:xx-large; color:tomato; text-align:center;">{{$item->captura_jurisdiccion}}</div>
                    <div class="col-md-11"> 
                        <table class="table">
                            <thead>
                                <tr scope="row">
                                    @foreach($item->usuarios as $k=>$i) 
                                        <th width="{{$cols}}%">
                                            {{$i->nombre}} {{$i->paterno}} {{$i->materno}}<br>
                                            <i style="font-weight:normal;">{{$i->email}}</i>
                                        </th>
                                    @endforeach
                                    @for($ii = (count($item->usuarios)+1); $ii < ($col + 1); $ii++) 
                                        <th width="{{$cols}}%">---
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr scope="row">
                                    @foreach($item->usuarios as $k=>$i) 
                                        <td style="text-align:center; font-size:large; color:#000;">
                                            {{$i->captura}}
                                        </td>
                                    @endforeach
                                    @for($ii = (count($item->usuarios)+1); $ii < ($col + 1); $ii++) 
                                        <th width="{{$cols}}%" style="text-align:center; font-size:large; color:#000;">---
                                        </th>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </li>
    @endforeach
</ul>

