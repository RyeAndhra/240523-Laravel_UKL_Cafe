<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\UserController;
use App\http\Controllers\MenuController;
use App\http\Controllers\MejaController;
use App\http\Controllers\TransaksiController;
use App\http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/getuser', [UserController::class, 'getuser']);
Route::get('/getuser/{id}', [UserController::class, 'detailuser']);
Route::get('/getrole/{role}', [UserController::class, 'getrole']);
Route::post('/createuser', [UserController::class, 'createuser']);
Route::put('/updateuser/{id}', [UserController::class, 'updateuser']);
Route::delete('/deleteuser/{id}', [UserController::class, 'deleteuser']);

Route::get('/getmenu', [MenuController::class, 'getmenu']);
Route::get('/getmenu/{id}', [MenuController::class, 'detailmenu']);
Route::post('/createmenu', [MenuController::class, 'createmenu']);
Route::put('/updatemenu/{id}', [MenuController::class, 'updatemenu']);
Route::post('/updatephoto/{id}', [MenuController::class, 'updatephoto']);
Route::delete('/deletemenu/{id}', [MenuController::class, 'deletemenu']);

Route::get('/getmeja', [MejaController::class, 'getmeja']);
Route::get('/getmeja/{id}', [MejaController::class, 'detailmeja']);
Route::post('/createmeja', [MejaController::class, 'createmeja']);
Route::put('/updatemeja/{id}', [MejaController::class, 'updatemeja']);
Route::delete('/deletemeja/{id}', [MejaController::class, 'deletemeja']);


Route::get('/gettransaksi', [TransaksiController::class, 'gettransaksi']);
Route::get('/gettransaksi/{id}', [TransaksiController::class, 'detailtransaksi']);
Route::get('/dtransaksi/{id}', [TransaksiController::class, 'dtransaksi']);
Route::post('/createtransaksi', [TransaksiController::class, 'createtransaksi']);
Route::put('/payment/{id}', [TransaksiController::class, 'payment']);
Route::delete('/deletetransaksi/{id}', [TransaksiController::class, 'deletetransaksi']);

Route::get('/incomeToday/{date}', [TransaksiController::class, 'getTotalIncomeToday']);
Route::get('/incomePerDay/{date}', [TransaksiController::class, 'getTotalIncomePerDay']);
Route::get('/incomePerMonth/{year}/{month}', [TransaksiController::class, 'getTotalIncomePerMonth']);
Route::get('/filterIncome', [TransaksiController::class, 'filterIncome']);
