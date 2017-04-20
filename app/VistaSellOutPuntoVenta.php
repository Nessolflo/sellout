<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class VistaSellOutPuntoVenta extends Model 
{
    protected $table = 'total_sellout_puntoventa';
    protected $fillable = ['sellout', 'codigo', 'nombre'];
}