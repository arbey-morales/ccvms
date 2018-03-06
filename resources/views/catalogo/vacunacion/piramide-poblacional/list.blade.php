<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
<thead>
    <tr>
        <th style="vertical-align:middle; text-align:center;" rowspan="2">Municipio / Edades</th>
        <th style="text-align:center; color:#4d81bf;" colspan="11">Hombres</th>
        <th style="text-align:center; color:#ed1586;" colspan="11">Mujeres</th>
    </tr>
    <tr>
        <th style="vertical-align:middle; text-align:center;">*0</th>
        <th style="vertical-align:middle; text-align:center;">1</th>
        <th style="vertical-align:middle; text-align:center;">2</th>
        <th style="vertical-align:middle; text-align:center;">3</th>
        <th style="vertical-align:middle; text-align:center;">4</th>
        <th style="vertical-align:middle; text-align:center;">5</th>
        <th style="vertical-align:middle; text-align:center;">6</th>
        <th style="vertical-align:middle; text-align:center;">7</th>
        <th style="vertical-align:middle; text-align:center;">8</th>
        <th style="vertical-align:middle; text-align:center;">9</th>
        <th style="vertical-align:middle; text-align:center;">10</th>
        <th style="vertical-align:middle; text-align:center;">*0</th>
        <th style="vertical-align:middle; text-align:center;">1</th>
        <th style="vertical-align:middle; text-align:center;">2</th>
        <th style="vertical-align:middle; text-align:center;">3</th>
        <th style="vertical-align:middle; text-align:center;">4</th>
        <th style="vertical-align:middle; text-align:center;">5</th>
        <th style="vertical-align:middle; text-align:center;">6</th>
        <th style="vertical-align:middle; text-align:center;">7</th>
        <th style="vertical-align:middle; text-align:center;">8</th>
        <th style="vertical-align:middle; text-align:center;">9</th>
        <th style="vertical-align:middle; text-align:center;">10</th>
    </tr>
</thead>
<tbody>
    @foreach($data as $key=>$item)
        <tr data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top">    
            <td class="text-left"><a class="btn btn-success" href="#" class="button"> <strong>{{ $item->clues }}</strong> </a> {{$item->clue_nombre}}</td>        
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_0}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_1}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_2}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_3}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_4}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_5}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_6}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_7}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_8}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_9}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->hombres_10}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_0}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_1}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_2}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_3}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_4}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_5}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_6}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_7}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_8}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_9}}</td>
            <td style="vertical-align:middle; text-align:center;">{{$item->mujeres_10}}</td>           
        </tr>
    @endforeach
</tbody>
</table>

