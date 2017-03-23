<?php
namespace App\Http\Controllers;

    use App\VistaTop15ModelSellout;
    use App\VistaVentas;
    use Illuminate\Http\Request;

    use App\Http\Requests;

    class Top15ModelSelloutController extends Controller
    {
        public $message = "houston tenemos un problema!";
        public $result = false;
        public $records = [];

        public function index(){
            try{
                $this->message="Consulta realizada con exito";
                $this->result= true;
                $this->records= VistaTop15ModelSellout::all();
            }catch (\Exception $e)
            {
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

        public function obtenerTop5Model(Request $request){
            try{
                $semanadesde= $request->input("semanadesde");
                $semanahasta= $request->input("semanahasta");
                $anio= $request->input("anio");
                $dias= (($semanahasta-$semanadesde)+1)*5;
                $consulta="modelo AS modelo, sum(sellout) AS sellout, sum(inventory) as inventory";
                $orden=$request->input("orden");
                
                $registros= VistaVentas::selectRaw($consulta)->whereBetween('semana', array($semanadesde,$semanahasta))->where('inventory','>','0')->where('sellout','>','0')->where('anio', $anio)->groupBy('modelo')->orderBy('sellout', $orden)->limit(5)->offset(0)->get();
                /*$registros= VistaVentas::selectRaw($consulta)->whereBetween('semana', array($semanadesde,$semanahasta))->where('inventory','>','0')->where('sellout','>','0')->groupBy('modelo')->get();*/
                $nuevoreg= [];
                for ($i=0; $i <count($registros) ; $i++) { 
                    $json= $registros[$i];
                    $sellout= $json->sellout;
                    $inventory= $json->inventory;
                    $doi= ($inventory/$sellout)*$dias;
                    $json['doi']= round($doi);
                    array_push($nuevoreg, $json);
                }
               
                usort($nuevoreg, function($a, $b) {
                            return $a['doi']>$b['doi'];
                });
                    
                
                $this->message = "Consulta exitosa";
                $this->result = true;
                $this->records = $nuevoreg;
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

    
    }
