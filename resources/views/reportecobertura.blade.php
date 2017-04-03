<?php  
use App\Http\Requests;
use App\PuntosVentas;
use App\Modelos;

$sucursal= Request::input('idsucursal');
$models= Request::input('modelos');

$registros= Array();
$modelos= explode(",", $models);
$nModelos= Array();
$consulta="puntosventas.nombre as PDV, ";
for ($i = 0; $i < count($modelos); $i++) {
    $modelo=$modelos[$i];
    $ultimo= count($modelos)-1;
    $consulta.="sum(case when vistaventas.idmodelo=".$modelo." then vistaventas.sellout else 0 end) as sellout_".$modelo.", ";
    $consulta.="sum(case when vistaventas.idmodelo=".$modelo." then vistaventas.inventory else 0 end) as inventory_".$modelo.", ";
    $consulta.="ifnull((select cp.cantidad from categoriasplantillas cp inner join plantillas p ON p.idcategoria_plantilla=cp.id where p.idpuntoventa=puntosventas.id and p.idmodelo=".$modelo."),0) as plantilla_".$modelo.", ";
    
    
    if($ultimo==$i)
        $consulta.="0 as xvender_".$modelo."";
    else
        $consulta.="0 as xvender_".$modelo.", ";


    $tempModel= Modelos::find($modelo);
    if($tempModel){
        array_push($nModelos, $tempModel->nombre);
    }
}
$registros = 
PuntosVentas::selectRaw($consulta)->leftJoin('vistaventas', function ($join) {
    $join->on('vistaventas.idpuntoventa', '=', 'puntosventas.id')
    ->where('vistaventas.semana', '=', Request::input('semana'));
})->where('puntosventas.idsucursal', $sucursal)
->groupBy('puntosventas.id')
->get();

$temp= array();
foreach ($registros as $key => $tregistro) {
    for ($i = 0; $i < count($modelos); $i++) {
        $modelo=$modelos[$i];
        if($tregistro['inventory_'.$modelo]>0 && $tregistro['sellout_'.$modelo]>0)
            $tregistro['diasexhibicion_'.$modelo]=  round(($tregistro['sellout_'.$modelo] / $tregistro['inventory_'.$modelo])*7,2);
        else
            $tregistro['diasexhibicion_'.$modelo]= 0;

        if($tregistro['inventory_'.$modelo]>1 && $tregistro['sellout_'.$modelo]>0)
            $tregistro['diasventas_'.$modelo]=  round(($tregistro['sellout_'.$modelo] / $tregistro['inventory_'.$modelo])*7,2);
        else
            $tregistro['diasventas_'.$modelo]= 0;
        if($tregistro['plantilla_'.$modelo]>0){
                        $calculo = $tregistro['plantilla_'.$modelo] - $tregistro['inventory_'.$modelo];
                        if($calculo>0)
                            $tregistro['xvender_'.$modelo]=$calculo;
                    }
    }
    array_push($temp, $tregistro);
}
/*
$sucursal= Request::input('idsucursal');
$models= Request::input('modelos');

$registros= Array();
$modelos= explode(",", $models);
$nModelos= Array();
$consulta="puntosventas.nombre as PDV, ";
for ($i = 0; $i < count($modelos); $i++) {
    $modelo=$modelos[$i];
    $ultimo= count($modelos)-1;
    $consulta.="sum(case when vistaventas.idmodelo=".$modelo." then vistaventas.sellout else 0 end) as sellout_".$modelo.", ";
    $consulta.="sum(case when vistaventas.idmodelo=".$modelo." then vistaventas.inventory else 0 end) as inventory_".$modelo.", ";
    $consulta.="ifnull((select cp.cantidad from categoriasplantillas cp inner join plantillas p ON p.idcategoria_plantilla=cp.id where p.idpuntoventa=puntosventas.id and p.idmodelo=".$modelo."),0) as plantilla_".$modelo.", ";
    $consulta.="0 as xvender_".$modelo.", ";
    $consulta.="case when vistaventas.idmodelo=".$modelo." then if(vistaventas.inventory>0, if(vistaventas.sellout>0,((vistaventas.sellout/vistaventas.inventory)*7),vistaventas.inventory),0) else 0 end as diasexhibicion_".$modelo.",";
    if($ultimo==$i)
        $consulta.="case when vistaventas.idmodelo=".$modelo." then if(vistaventas.inventory>1, if(vistaventas.sellout>0,((vistaventas.sellout/vistaventas.inventory)*7),vistaventas.inventory),0) else 0 end as diasventas_".$modelo;
    else
        $consulta.="case when vistaventas.idmodelo=".$modelo." then if(vistaventas.inventory>1, if(vistaventas.sellout>0,((vistaventas.sellout/vistaventas.inventory)*7),vistaventas.inventory),0) else 0 end as diasventas_".$modelo.", ";
    $tempModel= Modelos::find($modelo);
    if($tempModel){
        array_push($nModelos, $tempModel->nombre);
    }
}
$registros = 
    PuntosVentas::selectRaw($consulta)->leftJoin('vistaventas', function ($join) {
            $join->on('vistaventas.idpuntoventa', '=', 'puntosventas.id')
            ->where('vistaventas.semana', '=', Request::input('semana'));
    })->where('puntosventas.idsucursal', $sucursal)
    ->groupBy('puntosventas.id')
    ->get();

*/
    ?>

    <table >
        <thead>
            <tr>
                <th>Punto de venta</th>
                @for ($i = 0; $i < count($modelos); $i++)
                <th>Sellout {{$nModelos[$i]}}</th>
                <th>Inventory {{$nModelos[$i]}}</th>
                <th>Plantilla {{$nModelos[$i]}}</th>
                <th>Comprar {{$nModelos[$i]}}</th>
                <th>Días Exhibición {{$nModelos[$i]}}</th>
                <th>Días Ventas {{$nModelos[$i]}}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
        @foreach($temp as $item)
            <tr>
                <td>{{$item->PDV}}</td>
                @for ($x = 0; $x < count($modelos); $x++)
                <td>{{$item->{'sellout_'.$modelos[$x]} }}</td>
                <td>{{$item->{'inventory_'.$modelos[$x]} }}</td>
                <td>{{$item->{'plantilla_'.$modelos[$x]} }}</td>
                <td>{{$item->{'xvender_'.$modelos[$x]} }}</td>
                <td>{{$item->{'diasexhibicion_'.$modelos[$x]} }}</td>
                <td>{{$item->{'diasventas_'.$modelos[$x]} }}</td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>