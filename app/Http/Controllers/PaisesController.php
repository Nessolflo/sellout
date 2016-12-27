<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Paises;
use DB;
use Exception;
class PaisesController extends Controller
{
    public $message = "houston tenemos un problema!";
	public $result = false;
	public $records = [];

	public function index(){
		try{
			$this->message = "Consulta exitosa";
			$this->result = true;
			$this->records = Paises::all();
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
				$registro = Paises::create(
					[
					"nombre" => $request->input("nombre"),
					"codigo" => $request->input("codigo")
					]);
				if(!$registro)
					throw new Exception("Ocurrio un problema al crear el registro");
				else
					return $registro;
			});
			$nuevoRegistro->modelo;
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
			$registro= Paises::find($id);
			if($registro){
				$registro->modelo;
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
                $registro = Paises::find($id);
				if(!$registro) throw new Exception("El registro no existe");
                else{
                    $registro->nombre = $request->input("nombre", $registro->nombre);
                    $registro->codigo = $request->input("codigo", $registro->codigo);
                    $registro->save();
					return $registro;  
                }
            });
            $actualizarRegistro->modelo;
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
            $this->records = Paises::destroy($id);
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
}
