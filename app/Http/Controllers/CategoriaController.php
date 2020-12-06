<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\categoria;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CategoriaController extends Controller
{
    use ApiResponser;
  public function Create(Request $req){
      $req->validate([
          'nombre'=>'required|string'
      ]);
      if(Auth::user()->user_type ==='admin'){
          $categoria = new categoria();
          $categoria->nombre = $req->nombre;
          $categoria->save();
          return $this->success($req->nombre, 'Creada con exito',200);
      }else{
        return $this->success( 'Algo Fallo',401);
      }
  }
}
