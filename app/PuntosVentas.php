<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntosVentas extends Model 
{
    protected $table = 'puntosventas';
    protected $fillable = ['idsucursal','nombre','codigo'];
    public function sucursal(){
    	return $this->hasOne('App\Sucursales','id','idsucursal');
    }
}