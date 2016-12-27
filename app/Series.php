<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model 
{
    protected $table = 'series';
    protected $fillable = ['idcategoria','nombre'];
    public function categoria(){
    	return $this->hasOne('App\Categorias','id','idcategoria');
    }
}