<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['prefix'=>'product'],function(){
    Route::get('/', [ProductoController::class, 'GetProductos']);
    Route::get('/{id}', [ProductoController::class, 'GetProducto']);
    Route::get('/user/{nickname}',[ProductoController::class, 'GetUserProducts']); //obtener productos por usuario

});

Route::group(['prefix' => 'Auth'], function () {
    Route::post('login',[AuthController::class,'login']);
    Route::post('signup',[AuthController::class,'signup']);
});

Route::group(['middleware' => 'auth:api'], function () {
    //rutas de usuarios
    Route::get('user',[AuthController::class,'user'] );
    Route::get('logout', [AuthController::class,'logout']);


    //ruta de productos que requieren login
    Route::group(['prefix'=>'product'],function(){
        Route::post('add',[ProductoController::class,'Create']);
        Route::put('update',[ProductoController::class, 'update']);
        //post imagen de producto
        Route::post('upload/{id}',[ProductoController::class,'image']);
    });

    //Ruta de categorias 
    Route::group(['prefix'=>'category'], function(){
        Route::post('add', [CategoriaController::class,'Create']);
    });
});