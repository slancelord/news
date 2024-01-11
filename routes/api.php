<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->apiResource('/news', 'App\Http\Controllers\NewsController');
Route::apiResource('/news', 'App\Http\Controllers\NewsController');
Route::put('/news/{news}/restore', 'App\Http\Controllers\NewsController@restore')->name('news.restore');