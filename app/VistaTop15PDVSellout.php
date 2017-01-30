<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class VistaTop15PDVSellout extends Model
{
    protected $table = 'top_15_pdv_sellout';
    protected $fillable = ['puntoventa','sucursal','sellout'];
}