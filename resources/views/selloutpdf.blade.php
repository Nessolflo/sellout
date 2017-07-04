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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pdf</title>
  <style type="text/css">
    
  </style>
</head>
<body>
<img src="images/intcomex_logo.png" alt="">

<table>
   <tbody>
        <tr><td><h4>Reporte Sellout</h4></td></tr>
       <tr>
           <td>Semana :<strong>{{$desde}}</strong></td>
           <td>a</td>
           <td>Semana: <strong>{{$hasta}}</strong> </td>
           <td>Año: <strong>{{$anio}}</strong> </td>
           
           
       </tr>
   </tbody>
</table>
<hr>
<table >
    <thead>
        <tr>
            <th style="width: 50px"> </th>
            <th style="width: 200px; text-align: center;">Modelo</th>
            <th style="width: 100px; text-align: right;">OH</th>
            <th style="width: 100px; text-align: right;">Sell Out</th>
            <th style="width: 100px; text-align: right;">PDS</th>
            <th style="width: 100px; text-align: right;">DOI</th>
        </tr>
    </thead>
    <tbody>
    @foreach($registros as $item)
    <tr>
            <td style="width: 50px"> </td>
            <td style="width: 200px">{{$item->modelo}}</td>
            <td style="width: 100px; text-align: right;">{{$item->inventory}}</td>
            <td style="width: 100px; text-align: right;">{{$item->sellout}}</td>
            <td style="width: 100px; text-align: right;">{{$item->pdS}}</td>
            <td style="width: 100px; text-align: right;">{{$item->DOI}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>

