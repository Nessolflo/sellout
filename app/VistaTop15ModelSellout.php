<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class VistaTop15ModelSellout extends Model
{
    protected $table = 'top_15_model_sellout';
    protected $fillable = ['model','sellout'];
}