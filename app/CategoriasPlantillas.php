<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriasPlantillas extends Model
{
    protected $table = 'categoriasplantillas';
    protected $fillable = ['id','idsucursal','nombre', 'cantidad'];
	protected $casts = [
        'idsucursal' => 'int',
    ];
}