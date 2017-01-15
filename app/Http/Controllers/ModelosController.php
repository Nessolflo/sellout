<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Modelos;
use DB;
use Exception;
class ModelosController extends Controller
{
    public $message = "houston tenemos un problema!";
	public $result = false;
	public $records = [];

	public function index(){
		try{
			$this->message = "Consulta exitosa";
			$this->result = true;
			$this->records = Modelos::with("serie")->get();
		}catch(\Exception $e){
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
		try{
			$nuevoRegistro=DB::transaction(function() use($request){
				$registro = Modelos::create(
					[
					"idserie" => $request->input("idserie"),
					"nombre" => $request->input("nombre"),
					"status" => $request->input("status"),
					"tier" => $request->input("tier"),
					]);
				if(!$registro)
					throw new Exception("Ocurrio un problema al crear el registro");
				else
					return $registro;
			});
			$nuevoRegistro->serie;
            $this->message = "Registro creado";
            $this->result = true;
            $this->records = $nuevoRegistro;
		}catch(\Exception $e){
			$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al crear registros";
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
	public function show($id){
		try{
			$registro= Modelos::find($id);
			if($registro){
				$registro->serie;
				$this->message = "Consulta exitosa";
                $this->result = true;
                $this->records = $registro;
			}else{
				$this->message = "El registro no existe";
                $this->result = false;
			}
		}catch(\Exception $e){
			$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registro";
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
	public function edit($id){}
	public function update(Request $request, $id){
		try{
			$actualizarRegistro = DB::transaction( function() use($request, $id){
                $registro = Modelos::find($id);
				if(!$registro) throw new Exception("El registro no existe");
                else{
                    $registro->idserie = $request->input("idserie", $registro->idserie);
                    $registro->nombre = $request->input("nombre", $registro->nombre);
                    $registro->status = $request->input("status", $registro->status);
                    $registro->tier = $request->input("tier", $registro->tier);
                    $registro->save();
					return $registro;  
                }
            });
			$this->message = "Registro actualizado";
            $this->result = true;
            $this->records = $actualizarRegistro;
		}catch(\Exception $e){
			$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al actualizar registros";
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
	public function destroy($id){
		try{
			$this->message = "Registro eliminado";
            $this->result = true;
            $this->records = Modelos::destroy($id);
		}catch(\Exception $e){
			$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al eliminar registros";
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
	public function modelos_por_serie(Request $request)
	{
		try
		{
			$registros= Modelos::where('idserie', $request->input('idserie'))->get();
			$this->message = "Consulta exitosa";
            $this->result = true;
            $this->records= $registros;


		}catch(\Exception $e)
		{
			$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al cargar registros";
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
}
