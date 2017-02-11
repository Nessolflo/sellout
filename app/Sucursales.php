<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model 
{
    protected $table = 'sucursales';
    protected $fillable = ['idpais','idcuenta','nombre','codigo'];
    public function pais(){
    	return $this->hasOne('App\Paises','id','idpais');
    }
    public function cuenta(){
        return $this->hasOne('App\Cuentas','id','idcuenta');
    }
}