<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposUsuarios extends Model 
{
    protected $table = 'tiposusuarios';
    protected $fillable = ['nombre'];
}