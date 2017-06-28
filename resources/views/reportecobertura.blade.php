<?php  
use App\Http\Requests;
use App\PuntosVentas;
use App\Modelos;
set_time_limit(300000);
            $calculo1 = array();
            $calculo2 = array();
            $nombrem = array();
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
     $consulta.="puntosventas.nombre IN(select cp.cantidad from categoriasplantillas cp inner join plantillas p ON p.idcategoria_plantilla=cp.id where p.idpuntoventa=puntosventas.id and p.idmodelo=".$modelo.") as plantilla_".$modelo.", ";
     
     
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
for ($i = 0; $i < count($modelos); $i++) {
     $cantidadDiasExhibicion=0;
    $cantidadDiasVentas=0;
    $cantidadTiendas=count($registros); 
    foreach ($registros as $key => $tregistro) {
        $modelo=$modelos[$i];
        if($tregistro['inventory_'.$modelo]>0 && $tregistro['sellout_'.$modelo]>0){
           $tempDiasExhibicion= (($tregistro['sellout_'.$modelo] / $tregistro['inventory_'.$modelo])*7);
           $tregistro['diasexhibicion_'.$modelo]=  $tempDiasExhibicion>0?'Si':'No';
                        if($tempDiasExhibicion>0){
                            $cantidadDiasExhibicion++;
                        }
        }
        else{
            $tregistro['diasexhibicion_'.$modelo]= 'No';
        }
 
        if($tregistro['inventory_'.$modelo]>1 && $tregistro['sellout_'.$modelo]>0){
             $tempDiasCobertura = (($tregistro['sellout_'.$modelo] / $tregistro['inventory_'.$modelo])*7);
                            $tregistro['diasventas_'.$modelo]=      $tempDiasCobertura>1?'Si':'No';
                            if($tempDiasCobertura>1)
                                $cantidadDiasVentas++;
        }
        else{
            $tregistro['diasventas_'.$modelo]= 'No';
        }
        if($tregistro['plantilla_'.$modelo]>0){
                        $calculo = $tregistro['plantilla_'.$modelo] - $tregistro['inventory_'.$modelo];
                        if($calculo>0)
                            $tregistro['xvender_'.$modelo]=$calculo;
     }
                    $calculo1[$i]= round(($cantidadDiasExhibicion/$cantidadTiendas)*100,2);
                    $calculo2[$i]= round(($cantidadDiasVentas/$cantidadTiendas)*100,2);
                    $nombrem[$i]   =  DB::table('vistaventas')->where('idmodelo', $modelos[$i])->value('modelo');
                   
                    array_push($temp, $tregistro);
    }
 $tmodelos=count($modelos);
}
 
    ?>
 
<table >
        <thead>
            <tr>
                <th>Modelos</th>
                <th>Cobertura Display</th>
                <th>Cobertura Venta</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0;$i<$tmodelos;$i++)
            <tr>
               
                <th>{{$nombrem[$i]}}</th>
                <th>{{$calculo1[$i]}}</th>
                <th>{{$calculo2[$i]}}</th>
            </tr>
            @endfor
        </tbody>
    </table>
 
    <table >
             <tr>
                <th></th>
                
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Mayor a 0</th>
                <th>Mayor a 1</th>
                
            </tr>
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