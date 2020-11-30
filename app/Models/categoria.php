<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    use HasFactory;
   protected $table='categorias';

   public function productos(){

        //una categoria puede tener varios productos
       return $this->hasMany('app\Models\producto');
   }
}
