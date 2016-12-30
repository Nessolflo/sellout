<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model 
{
    protected $table = 'ventas';
    protected $fillable = ['idsinonimo','idpuntoventa','idusuariocreo', 'semana','anio', 'sellout','inventory'];
    public function sinonimo(){
    	return $this->hasOne('App\Sinonimos','id','idsinonimo');
    }
    public function puntoventa(){
    	return $this->hasOne('App\PuntosVentas','id','idpuntoventa');
    }
    public function usuariocreo(){
    	return $this->hasOne('App\Usuarios','id','idusuariocreo');
    }
}