<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VentasPendientes;
use DB;
use Exception;

class VentasPendientesController extends Controller
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
            $this->count = VentasPendientes::count();
            $this->records = VentasPendientes::limit(30)->get();
        } catch (\Exception $e) {
            $this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
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

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        try {
            $registro = VentasPendientes::find($id);
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

    }

    public function destroy($id)
    {
        try {
            $this->message = "Registro eliminado";
            $this->result = true;
            $this->records = VentasPendientes::destroy($id);
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
