<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\enderecoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [enderecoController::class, 'index']);

Route::get('/endereco/create', [enderecoController::class, 'create']);//html para criar
Route::post("/endereco",[enderecoController::class,'store']);  //guarda
Route::get("/endereco/edit/{id}",[enderecoController::class,'edit']); //editar
Route::put("/endereco/update/{id}",[enderecoController::class,'update']); //guarda editar
Route::get("/endereco/delete/{id}",[enderecoController::class,'destroy']); //delete

Route::get("endereco/municipio/{id}",[enderecoController::class,'showMunicipio']);
Route::get("estados/municipios/{iduf}",[enderecoController::class, 'showMunicipiosDoEstado']);
Route::get("endereco/estados",[enderecoController::class, 'showEstados']);