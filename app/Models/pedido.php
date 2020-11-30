<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pedido extends Model
{
    protected $table='pedidos';
    use HasFactory;

    public function user(){
        //muchos pedidos pueden pertenecer a un usuario
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function pedidos(){
        //un pedido puede tener muchos elementos en la tabla (productos pedidos)
        return $this->hasMany('App\Models\productos_pedido');
    }
}
