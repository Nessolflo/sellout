<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plantillas extends Model
{
    protected $table = 'plantillas';
    protected $fillable = ['id',
        'idcategoria_plantilla',
        'idsucursal',
        'idpuntoventa',
        'idsinonimo'];

    public function categoriasPlantillas(){
        return $this->hasOne('App\CategoriasPlantillas','id','idcategoria_plantilla');
    }
    public function sucursales(){
        return $this->hasOne('App\Sucursales','id','idsucursal');
    }
    public function puntosVentas(){
        return $this->hasOne('App\PuntosVentas','id','idpuntoventa');
    }
    public function sinonimos(){
        return $this->hasOne('App\Sinonimos','id','idsinonimo');
    }

}