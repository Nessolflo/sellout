<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\TiposUsuarios;
use DB;
use Exception;

class TiposUsuariosController extends Controller
{
	public $message = "houston tenemos un problema!";
	public $result = false;
	public $records = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	try
    	{
    		$this->message = "Consulta exitosa";
    		$this->result = true;
    		$this->records = TiposUsuarios::all();
    	}
    	catch(\Exception $e)
    	{
    		$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registros";
    		$this->result = false;
    	}
    	finally
    	{
    		$response = [
    		"message" => $this->message,
    		"result" => $this->result,
    		"records" => $this->records
    		];

    		return response()->json($response);
    	}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	try
    	{
    		$nuevoRegistro = DB::transaction( function() use($request){

    			$registro = TiposUsuarios::create(
    				[
    				"nombre" => $request->input("nombre")
    				]);

    			if(!$registro)
    				throw new Exception("Ocurrio un problema al crear el registro");
    			else
    				return $registro;
    		});

    		$this->message = "Registro creado";
    		$this->result = true;
    		$this->records = $nuevoRegistro;
    	}
    	catch(\Exception $e)
    	{
    		$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al crear registro";
    		$this->result = false;
    	}
    	finally
    	{
    		$response = [
    		"message" => $this->message,
    		"result" => $this->result,
    		"records" => $this->records
    		];

    		return response()->json($response);
    	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	try
    	{
    		$registro = TiposUsuarios::find($id);
    		if($registro)
    		{
    			$this->message = "Consulta exitosa";
    			$this->result = true;
    			$this->records = $registro;
    		}
    		else
    		{
    			$this->message = "El registro no existe";
    			$this->result = false;
    		}
    	}
    	catch(\Exception $e)
    	{
    		$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al consultar registro";
    		$this->result = false;
    	}
    	finally
    	{
    		$response = [
    		"message" => $this->message,
    		"result" => $this->result,
    		"records" => $this->records
    		];

    		return response()->json($response);
    	} 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	try
    	{
    		$actualizarRegistro = DB::transaction( function() use($request, $id){

    			$registro = TiposUsuarios::find($id);

    			if(!$registro)
    				throw new Exception("El registro no existe");
    			else
    			{
    				$registro->nombre = $request->input("nombre", $registro->nombre);
    				$registro->save();
    				return $registro;  
    			}
    		});

    		$this->message = "Registro actualizado";
    		$this->result = true;
    		$this->records = $actualizarRegistro;
    	}
    	catch(\Exception $e)
    	{
    		$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al actualizar registro";
    		$this->result = false;
    	}
    	finally
    	{
    		$response = [
    		"message" => $this->message,
    		"result" => $this->result,
    		"records" => $this->records
    		];

    		return response()->json($response);
    	}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	try
    	{
    		$this->message = "Registro eliminado";
    		$this->result = true;
    		$this->records = TiposUsuarios::destroy($id);
    	}
    	catch(\Exception $e)
    	{
    		$this->message = env("APP_DEBUG") ? $e->getMessage() : "Error al eliminar registro";
    		$this->result = false;
    	}
    	finally
    	{
    		$response = [
    		"message" => $this->message,
    		"result" => $this->result,
    		"records" => $this->records
    		];

    		return response()->json($response);
    	}
    }
}
