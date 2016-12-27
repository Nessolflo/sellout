<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model 
{
    protected $table = 'permisos';
    protected $fillable = ['idusuario','idpuntoventa'];
    public function usuario(){
    	return $this->hasOne('App\Usuarios','id','idusuario');
    }
    public function puntoventa(){
    	return $this->hasOne('App\PuntosVentas','id','idpuntoventa');
    }
}