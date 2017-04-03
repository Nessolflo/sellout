<?php

namespace App\Http\Controllers;

use App\VistaVentas;
use App\PuntosVentas;
use Illuminate\Http\Request;

use App\Http\Requests;

class DashCoberturaController extends Controller
{
    public $message = "houston tenemos un problema!";
    public $result = false;
    public $records = [];
    public $modelo="";
    public $semana=0;

    public function index()
    {
        try {
            $this->message = "Consulta realizada con exito";
            $this->result = true;
            //$this->records= VistaTop15ModelSellout::all();
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
            $this->result = false;
        } finally {
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "records" => $this->records
            ];
            return response()->json($response);
        }
    }

    public function create()
    {
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {

    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }

    public function calcularCobertura(Request $request)
    {


        /**
          SELECT
          pdv.nombre,
          ifnull(sum(v.sellout),0) as sellout,
          ifnull(sum(v.inventory),0) as inventory,
          ifnull((select cp.cantidad from categoriasplantillas cp inner join plantillas p ON p.idcategoria_plantilla=cp.id where p.idpuntoventa=pdv.id),0) as plantilla
          FROM pdv.puntosventas AS pdv
          left JOIN pdv.vistaventas as v ON v.idpuntoventa= pdv.id and v.semana=51 and v.idmodelo=60
          where pdv.idsucursal=1
          group by pdv.id
         */

          try {

            $this->semana= $request->input('semana');
            $sucursal= $request->input('idsucursal');
            $modelos= $request->input('modelos');
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
                
            }
            $registros = PuntosVentas::
            selectRaw($consulta)
                ->leftJoin('vistaventas', function ($join) {
                    $join->on('vistaventas.idpuntoventa', '=', 'puntosventas.id')
                        ->where('vistaventas.semana', '=', $this->semana);
                })
                ->where('puntosventas.idsucursal', $sucursal)
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

            $this->message = "Consulta realizada con exito";
            $this->result = true;
            $this->records = $temp;
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
            $this->result = false;
        } finally {
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "records" => $this->records
            ];
            return response()->json($response);
        }
        
        /*
        try {

            $this->semana= $request->input('semana');
            $sucursal= $request->input('idsucursal');
            $modelos= $request->input('modelos');
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

            }
            $registros = PuntosVentas::
            selectRaw($consulta)
                ->leftJoin('vistaventas', function ($join) {
                    $join->on('vistaventas.idpuntoventa', '=', 'puntosventas.id')
                        ->where('vistaventas.semana', '=', $this->semana);
                })
                ->where('puntosventas.idsucursal', $sucursal)
                ->groupBy('puntosventas.id')
                ->get();

            $this->message = "Consulta realizada con exito";
            $this->result = true;
            $this->records = $registros;
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
            $this->result = false;
        } finally {
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "records" => $this->records
            ];
            return response()->json($response);
        }
        */

    }

    public function exportarexcel()
    {
        try {
            \Excel::create('Reporte', function ($excel) {

                $excel->sheet('reporte', function ($sheet) {

                    $sheet->loadView('reportecobertura');

                });

            })->export('xls');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
