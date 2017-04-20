<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sinonimos extends Model 
{
    protected $table = 'sinonimos';
    protected $fillable = ['idmodelo','nombre'];
    public function modelo(){
    	return $this->hasOne('App\Modelos','id','idmodelo');
    }

    protected $casts = [
        'idmodelo' => 'int',
    ];
}