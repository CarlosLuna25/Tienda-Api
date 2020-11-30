<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class producto extends Model
{
    protected $table='productos';
    use HasFactory;

    public function categoria(){

        //muchos productos pueden pertenecer a una categoria
        return $this->belongsTo('App\Models\categoria', 'categoria_id');
    }
    public function user(){
        //muchos productos pueden pertenecer a un usuario
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function pedidos(){
        //un producto puede estar en muchos pedidos(productos pedidos)
        return $this->hasMany('App\Models\productos_pedido');
    }
}
