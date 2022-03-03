<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\V1\UserController as UserV1;
use App\Http\Controllers\Api\V1\TransactionController as TransactionV1;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
return $request->user();
});
 */

//OBTENER USUARIOS
Route::apiResource('v1/users', UserV1::class)->only(['index'])->middleware('auth:sanctum');

//OBTENER MIS TRANSACCIONES
Route::get('v1/transactions', [TransactionV1::class, 'index'])->middleware('auth:sanctum');

//INICIAR SESION
Route::post('login', [
    LoginController::class,
    'login',
]);

//INICIAR SESION
Route::post('loginadmin', [
    LoginController::class,
    'loginadmin',
]);

//REGISTRAR USUARIO
Route::post('register', [
    RegisterController::class,
    'register',
]);

//CONSULTAR BALANCE
Route::post('v1/users/myBalance', [UserV1::class, 'myBalance'])->middleware('auth:sanctum');

//OBTENER MIS TRANSACCIONES
Route::post('v1/users/viewMyTransactions', [TransactionV1::class, 'viewMyTransactions'])->middleware('auth:sanctum');

//REALIZAR TRANSFERENCIA
Route::post('v1/transactions/makeTransfer', [TransactionV1::class, 'makeTransfer'])->middleware('auth:sanctum');

//REALIZAR TRANSFERENCIA
Route::post('v1/transactions/makeOrderPay', [TransactionV1::class, 'makeOrderPay'])->middleware('auth:sanctum');

//REALIZAR TRANSFERENCIA
Route::post('v1/users/getUser', [UserV1::class, 'encontrarUsuario'])->middleware('auth:sanctum');
