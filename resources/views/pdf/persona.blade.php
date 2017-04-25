<html>
<head>
  <style>
    body{
      font-family: sans-serif;
      font-size:12px;
    }
    @page {
      margin: 110px 50px;
    }
    header { position: fixed;
      left: 0px;
      top: -95px;
      right: 0px;
      height: 140px;
      /*background-color: #ddd;*/
      text-align: center;
    }
    header h1{
      margin: 10px 0;
    }
    header h2{
      margin: 0 0 10px 0;
    }
    footer {
      position: fixed;
      left: 0px;
      bottom: -50px;
      right: 0px;
      height: 40px;
      border-bottom: 2px solid #ed1586;
    }
    footer .page:after {
      content: counter(page);
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }
    table {
        overflow: hidden;
        border: 1px solid #d3d3d3;
        background: #fefefe;
        width: 99%;
        margin: 5% auto 0;
        border-radius:5px;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
    }
    th, td {padding:5px; text-align:left; }
      th {padding-top:10px; text-shadow: 1px 1px 1px #fff; background:#e8eaeb;}
      td {border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0;}
      tr.odd-row td {background:#f6f6f6;}
      td.first, th.first {text-align:left}
      td.last {border-right:none;}
  </style>
<body>
  <header>
    <span style="width:20%; text-align:left; vertical-align:bottom; display:inline-block;">
      <img src="images/salud-mesoamerica.png" alt="Logo SM2015" border="0px" width="250px">
    </span>
    <span style="width:60%; text-align:center; vertical-align:bottom; display:inline-block;">
      <h1>Censo Nominal</h1>
      <h2>Jurisdicción {{Auth::user()->jurisdiccion->clave}} {{Auth::user()->jurisdiccion->nombre}}</h2>
    </span>
    <span style="width:18%; text-align:right; vertical-align:bottom;  display:inline-block;">
      <img src="images/censia.png" alt="Logo CeNSIA" border="0px" width="120px">
    </span>
  </header>
  <footer>
      <p class="page">
        Página
      </p>
  </footer>
  <div id="content">
    <table cellspacing="0">
        <tr>
          <th>Nombre</th>
          <th>Nacimiento</th>
          <th> </th>
          <th>CURP</th>
          <th>DH</th>
          <th>TP</th>
          <th>CD</th>
          <th>Dirección</th>
          <th>CLUES</th>
          <th>AGEB</th>
          <th>Sector</th>
          <th>Mz</th>
          <th>Esquema de vacunación</th>
        </tr>
        @if(count($data)>0)
          @foreach($data as $item)
              <tr>
                  <td>{{ $item->nombre }}  {{ $item->apellido_paterno }} {{ $item->apellido_materno }}</td>
                  <td>{{ $item->fecha_nacimiento }}</td>
                  <td>{{ $item->genero }}</td>
                  <td>{{ $item->curp }}</td>
                  <td>{{ $item->afiliacion['nombreCorto'] }}</td>
                  <td>{{ $item->tipoParto->descripcion }}</td>
                  <td>{{ $item->codigo['nombre'] }}</td>
                  <td>{{ $item->calle }} {{ $item->numero }}, {{ $item->colonia }}, {{ $item->localidad->nombre }}, {{ $item->municipio->nombre }} </td>
                  <td><strong>{{$item->clue->clues}}</strong>, {{$item->clue->nombre}}</td>
                  <td>{{ $item->ageb['id'] }}</td>
                  <td>{{ $item->sector }}</td>
                  <td>{{ $item->manzana }}</td>
                  <td>
                    @if(count($item->personasVacunasEsquemas)>0)
                      @foreach($item->personasVacunasEsquemas as $key=>$value)
                        {{$value->fecha_aplicacion}}
                      @endforeach
                    @else 
                      S/A
                    @endif  
                  </td>
              </tr>
          @endforeach
        @else
          <tr>
              <td colspan="13"> Sin Resultados</td>
          </tr>
        @endif
    </table>
  </div>
</body>
</html>