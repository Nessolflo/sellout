<?php  
use App\Http\Requests;
$desde= Request::input('desde');
$hasta= Request::input('hasta');
$anio= Request::input('aniodesde');

////////
$idgrupo= Request::input('idgrupo');
$idsucursal= Request::input('idsucursal');
$idpuntoventa= Request::input('idpuntoventa');
$idmodelo= Request::input('idmodelo');
////

$registros= Array();


if($idgrupo==0 && $idmodelo==0){
    $registros= DB::select('CALL doigtmt('.$desde.','.$hasta.','.$anio.')'); 
}
if($idgrupo!=0  && $idmodelo==0){
    $registros= DB::select('CALL doigsmt('.$desde.','.$hasta.','.$anio.','.$idgrupo.')');    
}
if($idgrupo==0  && $idmodelo!=0){
    $registros= DB::select('CALL doigtms('.$desde.','.$hasta.','.$anio.','.$idmodelo.')');    
}
if($idgrupo!=0  && $idmodelo!=0){
    $registros= DB::select('CALL doigsms('.$desde.','.$hasta.','.$anio.','.$idgrupo.','.$idmodelo.')');    
}
if($idgrupo!=0  && $idsucursal!=0 && $idmodelo==0){
    $registros= DB::select('CALL doigscsmt('.$desde.','.$hasta.','.$anio.','.$idgrupo.','.$idsucursal.')');    
}
if($idgrupo!=0  && $idsucursal!=0 && $modelo!=0){
    $registros= DB::select('CALL doigscsms('.$desde.','.$hasta.','.$anio.','.$idgrupo.','.$idsucursal.','.$idmodelo.')');    
}
if($idgrupo!=0  && $idsucursal!=0 && $idpuntoventa!=0){
    $registros= DB::select('CALL doigscspsmt('.$desde.','.$hasta.','.$anio.','.$idgrupo.','.$idsucursal.','.$idpuntoventa.')');    
}
if($idgrupo!=0  && $idsucursal!=0 && $idpuntoventa!=0 && $idmodelo!=0){
    $registros= DB::select('CALL doigscspsms('.$desde.','.$hasta.','.$anio.','.$idgrupo.','.$idsucursal.','.$idpuntoventa.','.$idmodelo.')');    
}


?>
<table>
   <tbody>
       <tr>

           <td>Semana :<strong>{{$desde}}</strong></td>
           <td>A</td>
           <td>Semana: <strong>{{$hasta}}</strong> </td>
           <td>Año: <strong>{{$anio}}</strong> </td>
           
           
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