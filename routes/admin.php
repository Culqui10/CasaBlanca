<?php
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandmodelsController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\FotosController;
use App\Http\Controllers\Admin\ProductosController;
use Illuminate\Support\Facades\Route;


Route::resource('/categoria', CategoriasController::class)->names('admin.categorias');
Route::resource('/producto', ProductosController::class)->names('admin.productos');
Route::get('categoriabyfamilia/{id}', [CategoriasController::class, 'categoriabyfamilia'])->name('admin.categoriabyfamilia');
Route::resource('foto', FotosController::class)->names('admin.foto');


?>