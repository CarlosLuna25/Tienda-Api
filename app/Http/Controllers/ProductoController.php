<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\producto;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\categoria;
use App\Models\User;

class ProductoController extends Controller
{
  use ApiResponser;


  public function GetUserProducts($nickname)
  {
    $user = User::where('nickname', $nickname)->first();



    if ($user) {
      $data = producto::where('user_id', $user->id)->orderByDesc('id')
        ->get();
      if ($data) {
        return $this->success($data, "Records Found", 200);
      } else {
        return $this->error("Products not found", 400);
      }
    } else {
      return $this->error("User not found", 400);
    }
  }
  public function GetProducto($req)
  {

    $data = producto::find($req);
    if ($data) {
      if ($data->imagen != null) {
        $path = $data->imagen;
        $data->imagen = "storage/products/" . $path;
      }
      return $this->success($data, "Record Found", 201);
    } else {
      return $this->error("Something was wrong", 400);
    }
  }
  public function GetProductos()
  {
    $data = producto::all();
    return $this->success($data, "success", 201);
  }
  public function Create(Request $req)
  {
    $user = Auth::user();
    $req->validate([
      'nombre' => 'required|string',
      'categoria_id' => 'required|exists:App\Models\categoria,id',
      'descripcion' => 'required|string',
      'stock' => 'required|integer',
      'precio' => 'required'

    ]);
    $producto = new producto();

    $save = producto::create([
      'nombre' => $req->nombre,
      'user_id' => $user->id,
      'categoria_id' => $req->categoria_id,
      'descripcion' => $req->descripcion,
      'stock' => $req->stock,
      'precio' => $req->precio,
      'imagen' => null,
    ]);




    if ($save) {
      return $this->success($save, 'Producto Creado con exito', 200);
    } else {
      return $this->error('Error al crear', 400);
    }
  }
  //update function
  public function update(Request $req)
  {
    $user = Auth::user();
    $req->validate([
      'id' => 'required|integer',
      'nombre' => 'string',
      'categoria_id' => 'exists:App\Models\categoria,id',
      'descripcion' => 'string',
      'stock' => 'integer',


    ]);
    $producto = producto::find($req->id);
    if ($producto) {
      if ($producto->user_id == $user->id) {
        $update = [];
        if ($req->nombre && $req->nombre != $producto->nombre) {
          $producto->nombre = $req->nombre;
          $update['nombre'] = $producto->nombre;
        }
        if ($req->categoria_id && $req->categoria_id != $producto->categoria_id) {
          $producto->categoria_id = $req->categoria_id;
          $update['categoria_id'] = $producto->categoria_id;
        }
        if ($req->descripcion && $req->descripcion != $producto->descripcion) {
          $producto->descripcion = $req->descripcion;
          $update['descripcion'] = $producto->descripcion;
        }
        if ($req->stock && $req->stock != $producto->stock) {
          $producto->stock = $req->stock;
          $update['stock'] = $producto->stock;
        }
        if ($req->nombre && $req->nombre != $producto->nombre) {
          $producto->name = $req->nombre;
          $update['nombre'] = $producto->nombre;
        }
        if ($req->precio && $req->precio != $producto->precio) {
          $producto->precio = $req->precio;
          $update['precio'] = $producto->precio;
        }
        $save = $producto->save();
        if ($save) {
          return $this->success($update, "updated successfully", 200);
        } else {
          return $this->error("Update Error", 400);
        }

        //si no es el usuario que creo el producto enviar error  
      } else {
        return $this->error('Unauthorized', 401);
      }
    } else {
      return $this->error('Record not found', 400);
    }
  }
  public function image(Request $req, $id)
  {
    $user = Auth::user();
    $producto = producto::find($id);
    if ($producto) {
      //comprobar que el producto pertenece al que quiere subir la imagen
      if ($producto->user_id == $user->id) {
        if ($producto->imagen != null) {
          Storage::delete('storage/products/' . $producto->imagen);
        }
        $image_path = time() . $producto->nombre . '.png';
        $image = $req->file('imagen')->storeAs(
          'public/products',
          $image_path

        );
        $producto->imagen = $image_path;
        $result = $producto->save();
        if ($result) {
          return $this->success($producto->imagen, "image Uploaded", 200);
        } else {
          return $this->error("upload failed", 400);
        }
      } else {
        //si no es el dueño del producto 
        return $this->error("Unauthorized user", 401);
      }
    } else {
      //retornar error de producto no encontrado
      return $this->error("Record not found", 400);
    }
  }

  public function GetCategoryProducts($nombre)
  {
    $categoria = categoria::where('nombre', $nombre)->first();
    if ($categoria) {
      $productos = producto::where('categoria_id', $categoria->id)->orderByDesc('id')
        ->get();
      if ($productos) {
        return $this->success($productos, "Records Found", 200);
      } else {
        return $this->error("Products not found", 400);
      }
    } else {
      return $this->error("Category not found", 400);
    }
  }

  public function delete(Request $req)
  {
    $req->validate([
      'id' => 'required|integer'
    ]);
    $delete = producto::find($req->id);
    if ($delete) {
      if (Auth::user()->id == $delete->user_id) {


        $result = $delete->delete();
        if ($result) {
          return $this->success("Product deleted", 200);
        } else {
          return $this->error('Error', 500);
        }
      } else {
        return $this->error('Unauthorized user', 401);
      }
    } else {
      return $this->error('Product not found', 400);
    }
  }
}
