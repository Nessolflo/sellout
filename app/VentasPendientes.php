<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentasPendientes extends Model
{
    protected $table = 'ventas_pendientes';
    protected $fillable = [
        'anio',
        'semana',
        'store_name',
        'model',
        'inventory',
        'sell_out',
        'sell_out_usd',
        'mensaje'
    ];

}