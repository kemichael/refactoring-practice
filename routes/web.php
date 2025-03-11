<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/lists', [App\Http\Controllers\ProductController::class, 'ichiran'])->name('lists');
Route::get('/detail/{id}', [App\Http\Controllers\ProductController::class, 'showDetail'])->name('detail');
Route::get('/regist', [App\Http\Controllers\ProductController::class, 'showStoreForm'])->name('regist');
Route::post('/regist', [App\Http\Controllers\ProductController::class, 'registSubmit'])->name('registSubmit');
Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class, 'showEdit'])->name('edit');
Route::post('/edit/{id}', [App\Http\Controllers\ProductController::class, 'registEdit'])->name('registEdit');
Route::delete('/products/{id}',[App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
