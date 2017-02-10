<?php

namespace App\Http\Controllers;

use App\VistaVentas;
use Illuminate\Http\Request;

use App\Http\Requests;

class DashboardSelloutVentasController extends Controller
{
    public $message = "houston tenemos un problema!";
    public $result = false;
    public $records = [];

    /*
     * public function tendenciaPorCategoria(Request $request){
        try{
            $desde = $request->input('desde');
            $hasta = $request->input('hasta');
            $aniodesde= $request->input('aniodesde');
            $aniohasta= $request->input('aniohasta');
            $fechadesde= $this->getFecha($aniodesde,$desde);
            $fechahasta= $this->getFecha($aniohasta,$hasta);
            $idpais= $request->input('idpais');
            $idsucursal= $request->input('idsucursal');
            $idpuntoventa= $request->input('idpuntoventa');
            $registros= Array();
            //Solo por pais
            if($idpais!='' && $idsucursal=='' && $idpuntoventa==''){
                $registros = VistaVentas::
                selectRaw('semana, categoria, idcategoria, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->groupBy('semana')->groupBy('categoria')->groupBy('idcategoria')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            } //pais, sucursal
            else if($idpais!='' && $idsucursal!='' && $idpuntoventa==''){
                $registros = VistaVentas::
                selectRaw('semana, categoria, idcategoria, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->where('idsucursal', $idsucursal)
                    ->groupBy('semana')->groupBy('categoria')->groupBy('idcategoria')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            } //pais, sucursal, puntoventa
            else if($idpais!='' && $idsucursal!='' && $idpuntoventa!=''){
                $registros = VistaVentas::
                selectRaw('semana, categoria, idcategoria, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->where('idsucursal', $idsucursal)
                    ->where('idpuntoventa', $idpuntoventa)
                    ->groupBy('semana')->groupBy('categoria')->groupBy('idcategoria')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            } //solo fechas
            else{
                $registros = VistaVentas::
                selectRaw('semana, categoria, idcategoria, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->groupBy('semana')->groupBy('categoria')->groupBy('idcategoria')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            }
            $this->message = "consulta exitosa sucursal ";
            $this->result = true;
            $this->records = $registros;
        }catch (\Exception $e){
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
            $this->result = false;
        }finally{
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "records" => $this->records
            ];
            return response()->json($response);
        }
    }
     */

    public function tendenciaPorCategoria(Request $request){
        try{
            $desde = $request->input('desde');
            $hasta = $request->input('hasta');
            $aniodesde= $request->input('aniodesde');
            $aniohasta= $request->input('aniohasta');
            $fechadesde= $this->getFecha($aniodesde,$desde);
            $fechahasta= $this->getFecha($aniohasta,$hasta);
            $idpais= $request->input('idpais');
            $idsucursal= $request->input('idsucursal');
            $idpuntoventa= $request->input('idpuntoventa');
            $idcategoria= $request->input('idcategoria');
            $idserie= $request->input('idserie');
            $registros= Array();
            if($idpais!='' && $idsucursal=='' && $idpuntoventa=='' && $idcategoria=='' && $idserie==''){
                $registros = VistaVentas::
                selectRaw('semana, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->groupBy('semana')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            }else if($idpais!='' && $idsucursal!='' && $idpuntoventa=='' && $idcategoria=='' && $idserie==''){
                $registros = VistaVentas::
                selectRaw('semana, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->where('idsucursal', $idsucursal)
                    ->groupBy('semana')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            }else if($idpais!='' && $idsucursal!='' && $idpuntoventa!='' && $idcategoria=='' && $idserie==''){
                $registros = VistaVentas::
                selectRaw('semana, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->where('idsucursal', $idsucursal)
                    ->where('idpuntoventa', $idpuntoventa)
                    ->groupBy('semana')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            }else
             if($idpais!='' && $idsucursal!='' && $idpuntoventa!='' && $idcategoria!='' && $idserie==''){
                $registros = VistaVentas::
                selectRaw('semana, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->where('idsucursal', $idsucursal)
                    ->where('idpuntoventa', $idpuntoventa)
                    ->where('idcategoria', $idcategoria)
                    ->groupBy('semana')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            }else if($idpais!='' && $idsucursal!='' && $idpuntoventa!='' && $idcategoria!='' && $idserie!=''){
                 $registros = VistaVentas::
                 selectRaw('semana, sum(sellout) as sellout')
                     ->whereBetween('fecha', array($fechadesde, $fechahasta))
                     ->where('idpais', $idpais)
                     ->where('idsucursal', $idsucursal)
                     ->where('idpuntoventa', $idpuntoventa)
                     ->where('idcategoria', $idcategoria)
                     ->where('idserie', $idserie)
                     ->groupBy('semana')
                     ->orderBy('sellout','desc')
                     ->limit(15)
                     ->get();
             }else if($idpais!='' && $idsucursal=='' && $idpuntoventa=='' && $idcategoria!='' && $idserie==''){
                 $registros = VistaVentas::
                 selectRaw('semana, sum(sellout) as sellout')
                     ->whereBetween('fecha', array($fechadesde, $fechahasta))
                     ->where('idpais', $idpais)
                     ->where('idcategoria', $idcategoria)
                     ->groupBy('semana')
                     ->orderBy('sellout','desc')
                     ->limit(15)
                     ->get();
             }else if($idpais=='' && $idsucursal=='' && $idpuntoventa=='' && $idcategoria!='' && $idserie==''){
                 $registros = VistaVentas::
                 selectRaw('semana, sum(sellout) as sellout')
                     ->whereBetween('fecha', array($fechadesde, $fechahasta))
                     ->where('idcategoria', $idcategoria)
                     ->groupBy('semana')
                     ->orderBy('sellout','desc')
                     ->limit(15)
                     ->get();
             }else if($idpais!='' && $idsucursal!='' && $idpuntoventa=='' && $idcategoria!='' && $idserie==''){
                 $registros = VistaVentas::
                 selectRaw('semana, sum(sellout) as sellout')
                     ->whereBetween('fecha', array($fechadesde, $fechahasta))
                     ->where('idcategoria', $idcategoria)
                     ->where('idsucursal', $idsucursal)
                     ->where('idpais', $idpais)
                     ->groupBy('semana')
                     ->orderBy('sellout','desc')
                     ->limit(15)
                     ->get();
             }

             else{
                $mensaje="";
                if($idpais=='')
                    $mensaje.="Pais ";
                if($idcategoria=='')
                    $mensaje.="Categoria ";
                 throw new \Exception($mensaje." obligatorio(s)");
             }

            $this->message = "consulta exitosa sucursal ";
            $this->result = true;
            $this->records = $registros;
        }catch (\Exception $e){
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
            $this->result = false;
        }finally{
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "records" => $this->records
            ];
            return response()->json($response);
        }
    }

    public function tendenciaPorSerie(Request $request){
        try{
            $desde = $request->input('desde');
            $hasta = $request->input('hasta');
            $aniodesde= $request->input('aniodesde');
            $aniohasta= $request->input('aniohasta');
            $fechadesde= $this->getFecha($aniodesde,$desde);
            $fechahasta= $this->getFecha($aniohasta,$hasta);
            $idpais= $request->input('idpais');
            $idsucursal= $request->input('idsucursal');
            $idpuntoventa= $request->input('idpuntoventa');
            $registros= Array();
            //Solo por pais
            if($idpais!='' && $idsucursal=='' && $idpuntoventa==''){
                $registros = VistaVentas::
                selectRaw('serie, idserie, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->groupBy('serie')->groupBy('idserie')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            } //pais, sucursal
            else if($idpais!='' && $idsucursal!='' && $idpuntoventa==''){
                $registros = VistaVentas::
                selectRaw('serie, idserie, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->where('idsucursal', $idsucursal)
                    ->groupBy('serie')->groupBy('idserie')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            } //pais, sucursal, puntoventa
            else if($idpais!='' && $idsucursal!='' && $idpuntoventa!=''){
                $registros = VistaVentas::
                selectRaw('serie, idserie, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->where('idpais', $idpais)
                    ->where('idsucursal', $idsucursal)
                    ->where('idpuntoventa', $idpuntoventa)
                    ->groupBy('serie')->groupBy('idserie')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            } //solo fechas
            else{
                $registros = VistaVentas::
                selectRaw('serie, idserie, sum(sellout) as sellout')
                    ->whereBetween('fecha', array($fechadesde, $fechahasta))
                    ->groupBy('serie')->groupBy('idserie')
                    ->orderBy('sellout','desc')
                    ->limit(15)
                    ->get();
            }
            $this->message = "consulta exitosa sucursal ";
            $this->result = true;
            $this->records = $registros;
        }catch (\Exception $e){
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
            $this->result = false;
        }finally{
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "records" => $this->records
            ];
            return response()->json($response);
        }
    }

    public function getFecha($year, $week)
    {
        $anio = strval($year);
        $semana = strval($week);
        if (strlen($semana) == 1)
            $semana = "0" . $semana;
        return date("Y-m-d", strtotime($anio . "W" . $semana));
    }

    public function index(){
        try{
            $this->message="Consulta realizada con exito";
            $this->result= true;
            /**
             *
             * select
            vistaventas.categoria AS categoria,
            vistaventas.idcategoria AS idcategoria,
            sum(vistaventas.sellout) AS sellout
            from pdv.vistaventas
            WHERE vistaventas.fecha BETWEEN '2016-01-01 00:00:00' and '2016-01-02 23:59:59'
            group by vistaventas.categoria, vistaventas.idcategoria
            order by sellout desc limit 0,15
             */
            $this->records = VistaVentas::
                selectRow('categoria, idcategoria, sum(sellout) as sellout')
                ->whereBetween('fecha', array('2016-01-01 00:00:00', '2016-12-19 23:59:59'))
                ->groupBy('categoria')->groupBy('idcategoria')
                ->orderBy('sellout','desc')
                ->limit(15)
                ->get();
        }catch (\Exception $e){
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
            $this->result = false;
        }finally{
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "records" => $this->records
            ];
            return response()->json($response);
        }
    }

    public function create(){}
    public function store(Request $request){

    }
    public function show($id){

    }
    public function edit($id){}
    public function update(Request $request, $id){

    }
    public function destroy($id){

    }
}
