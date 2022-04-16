<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\File;
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
    return File::get(public_path() . '/4fiops/index.html');
});

Route::get('/depositar', [UserController::class, 'depositar']);

//Route::get('/transferir', [UserController::class, 'transferir']);

//Route::get('/retirar', [UserController::class, 'retirar']);
