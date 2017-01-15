<?php  
use App\Http\Requests;
use App\Ventas;
use App\Sucursales;
use App\PuntosVentas;
use App\Sinonimos;
use App\Paises;
use App\Modelos;
use App\Series;
use App\Categorias;
use App\VistaVentas;
use App\Permisos;
use App\Usuarios;

$desde= Request::input('desde');
$hasta= Request::input('hasta');
$aniodesde= Request::input('aniodesde');
$aniohasta= Request::input('aniohasta');
$fechadesde= getFecha($aniodesde, $desde);
$fechahasta= getFecha($aniohasta, $hasta);
$pais= Request::input('idpais');
$sucursal= Request::input('idsucursal');
$puntoventa= Request::input('idpuntoventa');
$categoria= Request::input('idcategoria');
$serie= Request::input('idserie');
$modelo= Request::input('idmodelo');
$registros= Array();
            //solo por pais
if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria==''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->get();
            //pais, sucursal
else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria==''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->get();
            //pais, sucursal, puntoventa
else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria==''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->get();
            //pais, categoria
else if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idcategoria',$categoria)->get();
            //pais, categoria, serie
else if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idcategoria',$categoria)->where('idserie',$serie)->get();
            //pais, categoria, serie, modelo
else if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo!='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
            //02
            //pais, sucursal, categoria
else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria!=''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idcategoria',$categoria)->get();
            //pais, sucursal, categoria, serie
else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idcategoria',$categoria)->where('idserie',$serie)->get();
            //pais, sucursal, categoria, serie, modelo
else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo!='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
            //03
            //pais, sucursal, puntoventa, categoria
else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria!=''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->where('idcategoria',$categoria)->get();
            //pais, sucursal, puntoventa, categoria, serie
else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria!=''&&$serie!=''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->where('idcategoria',$categoria)->where('idserie',$serie)->get();
            //pais, sucursal, puntoventa, categoria, serie, modelo
else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria!=''&&$serie!=''&&$modelo!='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
            //04
            //solo por categoria
else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idcategoria',$categoria)->get();
            //categoria, series
else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idcategoria',$categoria)->where('idserie',$serie)->get();
            //categoria, serie, modelo
else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo!='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
            //05
            //solo por fecha
else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria==''&&$serie==''&&$modelo=='')
    $registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->get();  

function getFecha($year, $week){
        $anio= strval($year);
        $semana= strval($week);
        if(strlen($semana)==1)
            $semana= "0".$semana;
        return date("Y-m-d", strtotime($anio."W".$semana));
    }

?>

<table >
    <thead>
        <tr>
            <th>#</th>
            <th>Year</th>
            <th>Week</th>
            <th>Store name</th>
            <th>Retailer</th>
            <th>Country</th>
            <th>Model</th>
            <th>Inventory</th>
            <th>Sell Out</th>
            <th>Series</th>
            <th>Status</th>
            <th>Tier</th>
            <th>Category</th>
        </tr>
    </thead>
    <tbody>
    @foreach($registros as $item)
    <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->anio}}</td>
            <td>{{$item->semana}}</td>
            <td>{{$item->puntoventa}}</td>
            <td>{{$item->sucursal}}</td>
            <td>{{$item->pais}}</td>
            <td>{{$item->modelo}}</td>
            <td>{{$item->inventory}}</td>
            <td>{{$item->sellout}}</td>
            <td>{{$item->serie}}</td>
            <td>{{$item->status}}</td>
            <td>{{$item->tier}}</td>
            <td>{{$item->categoria}}</td>
        </tr>
        @endforeach
    </tbody>
</table>