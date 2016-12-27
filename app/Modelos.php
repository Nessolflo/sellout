<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modelos extends Model 
{
    protected $table = 'modelos';
    protected $fillable = ['idserie','nombre','status','tier'];

    public function serie(){
    	return $this->hasOne('App\Series','id','idserie');
    }
}