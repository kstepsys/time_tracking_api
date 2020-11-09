<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;

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

Route::post('users', [UserController::class, 'store']);
Route::post('users/login', [UserController::class, 'login']);
//Routes that require login
Route::group(["middleware" => ["jwtauth"]], function () {
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/export', [TaskController::class, 'export']);
    Route::post('tasks', [TaskController::class, 'store']);
});


