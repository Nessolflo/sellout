<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plantillas extends Model
{
    protected $table = 'plantillas';
    protected $fillable = ['id',
        'idcategoria_plantilla',
        'idpuntoventa',
        'idmodelo'];

    public function categoriasPlantillas(){
        return $this->hasOne('App\CategoriasPlantillas','id','idcategoria_plantilla');
    }
    public function puntosVentas(){
        return $this->hasOne('App\PuntosVentas','id','idpuntoventa')->with('sucursal');
    }

    public function sucursales(){
        return $this->hasOne('App\Sucursales','id','idsucursal');
    }

    public function modelos(){
        return $this->hasOne('App\Modelos','id','idmodelo');
    }

    protected $casts = [
        'idcategoria_plantilla' => 'int',
        'idpuntoventa' => 'int',
        'idmodelo' => 'int',
    ];

}