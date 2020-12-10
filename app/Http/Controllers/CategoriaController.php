<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\categoria;
use App\Models\producto;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CategoriaController extends Controller
{
  use ApiResponser;
  public function GetCategories(){
    $data = categoria::orderBy('nombre')->get();
    return $this->success($data, "success", 201);
  }
  public function Create(Request $req)
  {

    $req->validate([
      'nombre' => 'required|string'
    ]);
    if (Auth::user()->user_type === 'admin') {
      $categoria = new categoria();
      $categoria->nombre = $req->nombre;
      $categoria->save();
      return $this->success($req->nombre, 'Creada con exito', 200);
    } else {
      return $this->success('Algo Fallo', 401);
    }
  }

  public function update(Request $req)
  {
    $req->validate([
      'id' => 'required|exists:App\Models\categoria,id',
      'nombre' => 'required|string'
    ]);
    if (Auth::user()->user_type === 'admin') {
      $categoria = categoria::find($req->id);
      if ($req->nombre != $categoria->nombre) {
        $categoria->nombre = $req->nombre;
        $categoria->save();
      }
      return $this->success($req->nombre, 'Updated category', 200);
    } else {
      return $this->error('Algo Fallo', 401);
    }
  }
  public function delete(Request $req){
    $req->validate([
      'id'=>'required|integer'
    ]);
    //comprobar que no hayan productos en la categoria aun 
      $productos=producto::where('categoria_id',$req->id)->get();
      if(count($productos)>=1){
          return $this->error("Products found in this category", 500);
      }else{
        //si no hay productos comprobar que sea usuario admin y eliminar
        if (Auth::user()->user_type === 'admin') {
          $delete = categoria::find($req->id);
            if($delete){
               $result= $delete->delete();
                if($result){
                  return $this->success("Category deleted",200);
                }else{
                  return $this->error('Error', 500);
                }
            }else{
              return $this->error('Category not found', 400);
            }
      
          }else{
            return $this->error('Unauthorized user', 401);
          }
      }
  }
}
