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
use App\VistaVentas;
use App\Permisos;
use App\Usuarios;
use DB;
use Exception;
class VentasController extends Controller
{
	public $message = "houston tenemos un problema!";
	public $result = false;
	public $records = [];

	public function index(){
		try{
			$fecha=date('Y-m-d');
			$fechadesde=$fecha." 00:00:00";
			$fechahasta=$fecha." 23:59:59";
			$tregistros= Ventas::with("sinonimo","puntoventa","usuariocreo")->whereBetween('created_at', array($fechadesde,$fechahasta))->get();
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
	public function filtro(Request $request){
		try{
			$desde= $request->input('desde');
			$hasta= $request->input('hasta');
			$aniodesde= $request->input('aniodesde');
			$aniohasta= $request->input('aniohasta');
			$fechadesde= $this->getFecha($aniodesde, $desde);
			$fechahasta= $this->getFecha($aniohasta, $hasta);
			$pais= $request->input('idpais');
			$sucursal= $request->input('idsucursal');
			$puntoventa= $request->input('idpuntoventa');
			$categoria= $request->input('idcategoria');
			$serie= $request->input('idserie');
			$modelo= $request->input('idmodelo');
			$registros= Array();
			//solo por pais
			if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria==''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->get();
			//pais, sucursal
			else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria==''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->get();
			//pais, sucursal, puntoventa
			else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria==''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->get();
			//pais, categoria
			else if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idcategoria',$categoria)->get();
			//pais, categoria, serie
			else if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idcategoria',$categoria)->where('idserie',$serie)->get();
			//pais, categoria, serie, modelo
			else if($pais!=''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo!='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
			//02
			//pais, sucursal, categoria
			else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria!=''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idcategoria',$categoria)->get();
			//pais, sucursal, categoria, serie
			else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idcategoria',$categoria)->where('idserie',$serie)->get();
			//pais, sucursal, categoria, serie, modelo
			else if($pais!=''&&$sucursal!=''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo!='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
			//03
			//pais, sucursal, puntoventa, categoria
			else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria!=''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->where('idcategoria',$categoria)->get();
			//pais, sucursal, puntoventa, categoria, serie
			else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria!=''&&$serie!=''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->where('idcategoria',$categoria)->where('idserie',$serie)->get();
			//pais, sucursal, puntoventa, categoria, serie, modelo
			else if($pais!=''&&$sucursal!=''&&$puntoventa!=''&&$categoria!=''&&$serie!=''&&$modelo!='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idpais',$pais)->where('idsucursal',$sucursal)->where('idpuntoventa',$puntoventa)->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
			//04
			//solo por categoria
			else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idcategoria',$categoria)->get();
			//categoria, series
			else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idcategoria',$categoria)->where('idserie',$serie)->get();
			//categoria, serie, modelo
			else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria!=''&&$serie!=''&&$modelo!='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->where('idcategoria',$categoria)->where('idserie',$serie)->where('idmodelo',$modelo)->get();
			//05
			//solo por fecha
			else if($pais==''&&$sucursal==''&&$puntoventa==''&&$categoria==''&&$serie==''&&$modelo=='')
				$registros= VistaVentas::whereBetween('fecha', array($fechadesde,$fechahasta))->get();	
			

			$this->message = "consulta exitosa sucursal ";
			$this->result = true;
			$this->records = $registros;
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

	public function getFecha($year, $week){
		$anio= strval($year);
		$semana= strval($week);
		if(strlen($semana)==1)
			$semana= "0".$semana;
		return date("Y-m-d", strtotime($anio."W".$semana));
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
					$contador = 2;
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
								if(!$pais)
									throw new \Exception("Verifica el nombre del pais, la linea ".$contador);
								$sucursal= Sucursales::where('codigo', $tsucursal)->where('idpais',$pais->id)->first();
								if(!$sucursal)
									throw new \Exception("Verifica el nombre de la sucursal, la linea ".$contador);
								$puntoventa= PuntosVentas::where('idsucursal', $sucursal->id)->where('codigo',$tpdv)->where('idsucursal',$sucursal->id)->first();
								if(!$puntoventa)
									throw new \Exception("Verifica el nombre del punto de venta, la linea ".$contador);
								$sinonimo= Sinonimos::where('nombre', $fila->model)->first();
								if(!$sinonimo)
									throw new \Exception("Verifica el nombre del modelo, la linea ".$contador);
								
								$week= $fila->week;
								if($week>52 && $week<1)
									throw new \Exception("Verifica la semana, la linea ".$contador);

								$tusuario=Usuarios::find($request->idusuario);
								if($tusuario->idtipo!=1)
								{
									$permiso=Permisos::where('idusuario',$request->idusuario)->where('idpuntoventa',$puntoventa->id)->first();
									if(!$permiso)
										throw new \Exception("Este usuario no tiene permisos para subir info de este punto de venta, la linea ".$contador);
								}
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
	public function exportarexcel(){
		try{
			\Excel::create('Reporte', function($excel) {

				$excel->sheet('reporte', function($sheet) {

					$sheet->loadView('reporte');

				});

			});
		}catch(\Exception $e){echo $e->getMessage();}
	}
}
