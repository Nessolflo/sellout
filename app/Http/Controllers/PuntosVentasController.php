<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PuntosVentas;
use App\CategoriasPlantillas;
use DB;
use Exception;

class PuntosVentasController extends Controller
{
    public $message = "houston tenemos un problema!";
    public $result = false;
    public $records = [];
    public $count = 0;

    public function index()
    {
        try {
            $this->message = "Consulta exitosa";
            $this->result = true;
            $this->records = PuntosVentas::with("sucursal")->get();
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
                $registro = PuntosVentas::create(
                    [
                        "idsucursal" => $request->input("idsucursal"),
                        "nombre" => $request->input("nombre"),
                        "codigo" => $request->input("codigo")
                    ]);
                if (!$registro)
                    throw new Exception("Ocurrio un problema al crear el registro");
                else
                    return $registro;
            });
            $nuevoRegistro->sucursal;
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
            $registro = PuntosVentas::find($id);
            if ($registro) {
                $registro->sucursal;
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
                $registro = PuntosVentas::find($id);
                if (!$registro) throw new Exception("El registro no existe");
                else {
                    $registro->idsucursal = $request->input("idsucursal", $registro->idsucursal);
                    $registro->nombre = $request->input("nombre", $registro->nombre);
                    $registro->codigo = $request->input("codigo", $registro->codigo);
                    $registro->save();
                    return $registro;
                }
            });
            $actualizarRegistro->sucursal;
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
            $this->records = PuntosVentas::destroy($id);
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

    public function puntosventas_por_sucursal(Request $request)
    {
        try {

            //$registros = PuntosVentas::where('idsucursal', $request->input('idsucursal'))->with("sucursal")->orderBy('id','asc')->get();

            $todos = PuntosVentas::find(1);
            $todos->id = 0;
            $todos->nombre = 'Todos';
            //dd($todos);
            $registros= PuntosVentas::where('idsucursal', $request->input('idsucursal'))->with("sucursal")->orderBy('id','asc')->get();
            $registros->prepend($todos);




            $this->count = PuntosVentas::where('idsucursal', $request->input('idsucursal'))->with("sucursal")->count();
            $this->message = "Consulta exitosa";
            $this->result = true;
            $this->records = $registros;
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al cargar registros";
            $this->result = false;
        } finally {
            $response = [
                "message" => $this->message,
                "result" => $this->result,
                "count" => $this->count,
                "records" => $this->records
            ];
            return response()->json($response);
        }
    }

    public function puntosventas_por_plantilla(Request $request)
    {
        try {
            $plantilla= CategoriasPlantillas::find($request->input('id'));
            $registros = PuntosVentas::where('idsucursal', $plantilla->idsucursal)->with("sucursal")->orderBy('id','asc')->get();
            $this->message = "Consulta exitosa";
            $this->result = true;
            $this->records = $registros;
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al cargar registros";
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
