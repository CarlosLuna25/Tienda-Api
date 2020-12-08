<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\producto;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\categoria;

class ProductoController extends Controller
{
    use ApiResponser;
  public function GetProductos(){
    $data= producto::all();
    return $this->success($data,"success", 201);
  }
  public function Create(Request $req){
    $user= Auth::user();
    $req->validate([
        
        ]);
    $producto= new producto();
    
     $save=producto::create([
      'nombre' => $req->nombre,
      'user_id' => $user->id,
      'categoria_id' => $req->categoria_id,
      'descripcion' => $req->descripcion,
      'stock' =>$req->stock,
      'precio'=>$req->precio,
      'imagen'=>null,
  ]);
    
    
    
    
        if ($save){
            return $this->success( $save->id ,'Producto Creado con exito',200);
        }else{
            return $this->error('Error al crear',401);  
        }
  }
}
