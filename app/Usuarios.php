<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model 
{
    protected $table = 'usuarios';
    protected $fillable = ['idtipo','usuario','password'];
    protected $hidden = array('password');
    public function tipoUsuario(){
    	return $this->hasOne('App\TiposUsuarios','id','idtipo');
    }
}