<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Ventas;
use DB;
use Exception;
class VentasController extends Controller
{
    public $message = "houston tenemos un problema!";
	public $result = false;
	public $records = [];

	public function index(){
		try{
			$this->message = "Consulta exitosa";
			$this->result = true;
			$this->records = Ventas::with("sinonimo","puntoventa","usuariocreo")->get();
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
				$registro = Ventas::create(
					[
					"idsinonimo" => $request->input("idsinonimo"),
					"idpuntoventa" => $request->input("idpuntoventa"),
					"idusuariocreo" => $request->input("idusuariocreo"),
					"sellout" => $request->input("sellout"),
					"inventory" => $request->input("inventory")
					]);
				if(!$registro)
					throw new Exception("Ocurrio un problema al crear el registro");
				else
					return $registro;
			});
			$nuevoRegistro->sinonimo;
			$nuevoRegistro->puntoventa;
			$nuevoRegistro->usuariocreo;
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
			$registro= Ventas::find($id);
			if($registro){
				$registro->sinonimo;
			$registro->puntoventa;
			$registro->usuariocreo;
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
                $registro = Ventas::find($id);
				if(!$registro) throw new Exception("El registro no existe");
                else{
                    $registro->idsinonimo = $request->input("idsinonimo", $registro->idsinonimo);
                    $registro->idpuntoventa = $request->input("idpuntoventa", $registro->idpuntoventa);
                    $registro->idusuariocreo = $request->input("idusuariocreo", $registro->idusuariocreo);
                    $registro->sellout = $request->input("sellout", $registro->sellout);
                    $registro->inventory = $request->input("inventory", $registro->inventory);
                    $registro->save();
					return $registro;  
                }
            });
            $actualizarRegistro->sinonimo;
			$actualizarRegistro->puntoventa;
			$actualizarRegistro->usuariocreo;
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
            $this->records = Ventas::destroy($id);
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
