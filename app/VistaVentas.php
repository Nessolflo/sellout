<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class VistaVentas extends Model 
{
    protected $table = 'vistaventas';
    protected $fillable = ['id', 'fecha', 'idmodelo','modelo','status', 'tier','idserie','serie','idcategoria','categoria','puntoventa','idsucursal','sucursal','idpais','pais','usuariocreo','idpuntoventa','idsinonimo','idusuariocreo','semana','anio','sellout','inventory'];

    protected $casts = [
        'idmodelo' => 'int',
        'idserie' => 'int',
        'idcategoria' => 'int',
		'idsucursal' => 'int',
        'idpais' => 'int',
        'idpuntoventa' => 'int',
        'idsinonimo' => 'int',
        'idusuariocreo' => 'int',
    ];
}