<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos_pedido extends Model
{
    protected $table='productos_pedido';
    use HasFactory;

    public function pedido(){
        //un producto puede estar en muchos pedidos(productos pedidos)
        return $this->belongsTo('App\Models\pedido');
    }
    public function producto(){
        //un producto puede estar en muchos pedidos(productos pedidos)
        return $this->belongsTo('App\Models\producto');
    }
}
