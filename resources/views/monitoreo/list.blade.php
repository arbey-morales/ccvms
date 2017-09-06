<ul class="list-unstyled timeline">
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
                                    <th>Usuarios:</th>
                                    @foreach($item->usuarios as $k=>$i) 
                                        <th>
                                            {{$i->nombre}} {{$i->paterno}} {{$i->materno}}<br>
                                            <i style="font-weight:normal;">{{$i->email}}</i>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr scope="row">
                                    <td>Capturas:</td>
                                    @foreach($item->usuarios as $k=>$i) 
                                        <td style="text-align:center; font-size:large; color:#000;">
                                            {{$i->captura}}
                                        </td>
                                    @endforeach
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

