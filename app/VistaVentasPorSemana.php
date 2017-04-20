<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class VistaVentasPorSemana extends Model 
{
    protected $table = 'ventasporsemana';
    protected $fillable = ['fecha', 'sellout', 'semana','anio'];

}