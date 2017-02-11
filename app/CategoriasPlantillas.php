<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriasPlantillas extends Model
{
    protected $table = 'categoria_plantilla';
    protected $fillable = ['id','nombre', 'cantidad'];

}