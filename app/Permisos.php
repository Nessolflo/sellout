<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model 
{
    protected $table = 'permisos';
    protected $fillable = ['idusuario','idsucursal'];
    public function usuario(){
    	return $this->hasOne('App\Usuarios','id','idusuario');
    }
    public function sucursal(){
    	return $this->hasOne('App\Sucursales','id','idsucursal');
    }

    protected $casts = [
        'idusuario' => 'int',
        'idsucursal' => 'int',
    ];
}