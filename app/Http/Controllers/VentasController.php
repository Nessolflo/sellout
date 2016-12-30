<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Ventas;
use App\Sucursales;
use App\PuntosVentas;
use App\Sinonimos;
use App\Paises;
use App\Modelos;
use App\Series;
use App\Categorias;
use DB;
use Exception;
class VentasController extends Controller
{
	public $message = "houston tenemos un problema!";
	public $result = false;
	public $records = [];

	public function index(){
		try{
			$tregistros= Ventas::with("sinonimo","puntoventa","usuariocreo")->get();
			$registros= array();
			foreach ($tregistros as $key => $tregistro) {
				$registro=[];
				$registro['anio']=$tregistro->anio;
				$registro['semana']=$tregistro->semana;
				$tsucursal=Sucursales::find($tregistro->puntoventa->idsucursal);
				$codigosucursal= $tsucursal->codigo;
				$pais= Paises::find($tsucursal->idpais);
				$codigopais=$pais->codigo;
				$tstorename= $codigosucursal." ".$codigopais." ".$tregistro->puntoventa->codigo." ".$tregistro->puntoventa->nombre;
				$registro['store_name']=$tstorename;
				$registro['retailer']=$tsucursal->nombre;
				$registro['pais']=$pais->nombre;
				$modelo= Modelos::find($tregistro->sinonimo->idmodelo);
				$serie= Series::find($modelo->idserie);
				$categoria= Categorias::find($serie->idcategoria);
				$registro['modelo']= $modelo->nombre;
				$registro['serie']=$serie->nombre;
				$registro['categoria']=$categoria->nombre;
				$registro['tier']=$modelo->tier;
				$registro['status']=$modelo->status;
				$registro['inventory']=$tregistro->inventory;
				$registro['sellout']=$tregistro->sellout;
				array_push($registros, $registro);
			}
			$this->message = "Consulta exitosa";
			$this->result = true;
			$this->records = $registros;
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

	public function ImportarExcel(Request $request)
	{
		\DB::beginTransaction();
		try
		{
			if( $request->file('file') )
			{
				$archivo = $request->file('file');
				$destino = public_path() . "/excel/";
				$nombre = str_random( 10 ) . "." . $archivo->getClientOriginalExtension();
				$se_subio = $archivo->move( $destino, $nombre );
				if( $se_subio )
				{
					$url_archivo = public_path() . "/excel/" . $nombre;
					$contador = 0;
					$descartados = 0;
					
					\Excel::selectSheets( "inventario" )->load( $url_archivo, function( $lectorExcel ) use ( &$contador, &$descartados, &$request )
					{	
						$lectorExcel->ignoreEmpty();
						foreach ($lectorExcel->all() as $fila) 
						{
							if( strlen($fila->year)>0 & strlen($fila->week)>0 
								& strlen($fila->store_name) > 0 
								& strlen($fila->model) > 0 
								& strlen($fila->inventory) > 0 
								& strlen($fila->sell_out) > 0 
								& strlen($fila->sell_out_usd) > 0)
							{
								$tienda= explode(" ", $fila->store_name);
								$tsucursal= $tienda[0];
								$tpais= $tienda[1];
								$tpdv= $tienda[2]." ".$tienda[3];

								$pais= Paises::where('codigo',$tpais)->first();
								$sucursal= Sucursales::where('codigo', $tsucursal)->Where('idpais',$pais->id)->first();
								$puntoventa= PuntosVentas::where('idsucursal', $sucursal->id)->where('codigo',$tpdv)->first();
								$sinonimo= Sinonimos::where('nombre', $fila->model)->first();
								if(!$pais)
									throw new \Exception("Verifica el nombre del pais, la linea ".$contador);
								if(!$sucursal)
									throw new \Exception("Verifica el nombre de la sucursal, la linea ".$contador);
								if(!$puntoventa)
									throw new \Exception("Verifica el nombre del punto de venta, la linea ".$contador);
								if(!$sinonimo)
									throw new \Exception("Verifica el nombre del modelo, la linea ".$contador);
								$week= $fila->week;
								if($week>52 && $week<1)
									throw new \Exception("Verifica la semana, la linea ".$contador);


								$registro = new Ventas;
								$registro->anio= $fila->year;
								$registro->semana= $fila->week;
								$registro->idsinonimo = $sinonimo->id;
								$registro->idpuntoventa = $puntoventa->id;
								$registro->idusuariocreo = $request->idusuario;
								$registro->sellout = $fila->sell_out;
								$registro->inventory = $fila->inventory;
								$registro->save();

								$contador++;
							}
							else
								{$descartados++;}
						}
					});

					if( $contador == 0 )
					{
						$this->message = "No se encontro data para importar.";
						$this->result = false;
					}
					else
					{
						$this->message = "Registros creados ".$contador.", descartados ".$descartados;
						$this->result = true;
					}
				}
				else
				{
					$this->message = "Ocurrio un problema al subir archivo";
					$this->result = false;
				}
			}
			else
			{
				$this->message = "Debe seleccionar un archivo para importar informacion";
				$this->result = false;
			}

			$this->statusCode = 200;
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			$this->statusCode = 	200;
			$this->message	= 	env('APP_DEBUG')?$e->getMessage():'Registro no se actualizo';
			$this->result  	= 	false;
		}
		finally
		{
			\DB::commit();
			$response = 
			[
			'message'  	=> 	$this->message,
			'result'  	=> 	$this->result,
			'records'  	=> 	$this->records
			];

			return response()->json($response, $this->statusCode);
		}
	}
}
