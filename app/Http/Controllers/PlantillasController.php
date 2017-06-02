<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Plantillas;
use DB;
use Exception;

class PlantillasController extends Controller
{
    public $message = "houston tenemos un problema!";
    public $result = false;
    public $records = [];

    public function index()
    {
        try {
            set_time_limit(300000);
            $this->message = "Consulta exitosa";
            $this->result = true;
           /* $this->records = Plantillas::where('idpuntoventa','=','1')->where('idmodelo','=','1')->with('categoriasPlantillas')
                ->with('puntosVentas')
                ->with('sucursales')
                ->with('modelos')
                ->get();*/
            $this->records ="";
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
    public function filtrarplantilla(Request $request)
    {
        //dd("wil");
        //dd($request->input('wil'));
        //dd($request->input('idsucursal'));
        try {
            set_time_limit(300000);
            $this->message = "Consulta exitosa";
            $this->result = true;


            if($request->input('idmodelo')=='0'||$request->input('idmodelo')==null){
                /*$this->records = Plantillas::where('idpuntoventa','=',$request->input('idsucursal'))->with('categoriasPlantillas')
                ->with('puntosVentas')
                ->with('sucursales')
                ->with('modelos')
                ->get();*/

                $this->records =DB::select('Call filtroPlantillassinMod('.$request->input('idsucursal').');');
            }else{
               /* $this->records = Plantillas::where('idpuntoventa','=',$request->input('idsucursal'))->where('idmodelo','=',$request->input('idmodelo'))
                ->with('categoriasPlantillas')
                ->with('puntosVentas')
                ->with('sucursales')
                ->with('modelos')
                ->get();*/
                $this->records=DB::select('Call filtroPlantillas('.$request->input('idsucursal').','.$request->input('idmodelo').');');
            }
           



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
        try {
            $nuevoRegistro = DB::transaction(function () use ($request) {
                $registro = Plantillas::create(
                    [
                        "idcategoria_plantilla" => $request->input("idcategoria_plantilla"),
                        "idpuntoventa" => $request->input("idpuntoventa"),
                        "idmodelo" => $request->input("idmodelo")
                    ]);
                if (!$registro)
                    throw new Exception("Ocurrio un problema al crear el registro");
                else
                    return $registro;
            });
            $this->message = "Registro creado";
            $this->result = true;
            $this->records = $nuevoRegistro;
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al crear registros";
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

    public function show($id)
    {
        try {
            $registro = Plantillas::find($id);
            if ($registro) {
                $this->message = "Consulta exitosa";
                $this->result = true;
                $this->records = $registro;
            } else {
                $this->message = "El registro no existe";
                $this->result = false;
            }
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registro";
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

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        try {
            $actualizarRegistro = DB::transaction(function () use ($request, $id) {
                $registro = Plantillas::find($id);
                if (!$registro) throw new Exception("El registro no existe");
                else {
                    $registro->idcategoria_plantilla = $request->input("idcategoria_plantilla", $registro->idcategoria_plantilla);
                    $registro->idpuntoventa = $request->input("idpuntoventa", $registro->idpuntoventa);
                    $registro->idmodelo = $request->input("idmodelo", $registro->idmodelo);
                    $registro->save();
                    return $registro;
                }
            });

            $this->message = "Registro actualizado";
            $this->result = true;
            $this->records = $actualizarRegistro;
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al actualizar registros";
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

    public function destroy($id)
    {
        try {
            $this->message = "Registro eliminado";
            $this->result = true;
            $this->records = Plantillas::destroy($id);
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al eliminar registros";
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

}
