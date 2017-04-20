<?php  
use App\Http\Requests;
$desde= Request::input('desde');
$hasta= Request::input('hasta');
$anio= Request::input('aniodesde');
$sucursal= Request::input('sucursal');

$registros= Array();

$registros=  DB::select('CALL DOI('.$desde.','.$hasta.','.$anio.','.$sucursal.')'); 



?>
<table>
   <tbody>
       <tr>

           <td>Semana :<strong>{{$desde}}</strong></td>
           <td>A</td>
           <td>Semana: <strong>{{$hasta}}</strong> </td>
           <td>Año: <strong>{{$anio}}</strong> </td>
           <td>Sucursal: <strong>{{$registros[0]->sucursal}}</strong> </td>
           
       </tr>
   </tbody>
</table>
<table >
    <thead>
        <tr>
            
            <th>Modelo</th>
            <th>OH</th>
            <th>Sell Out</th>
            <th>PDS</th>
            <th>DOI</th>
        </tr>
    </thead>
    <tbody>
    @foreach($registros as $item)
    <tr>
            <td>{{$item->modelo}}</td>
            <td>{{$item->inventory}}</td>
            <td>{{$item->sellout}}</td>
            <td>{{$item->pdS}}</td>
            <td>{{$item->DOI}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>