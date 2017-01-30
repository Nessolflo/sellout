<?php

namespace App\Http\Controllers;

use App\VistaTop15ModelSellout;
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
}
