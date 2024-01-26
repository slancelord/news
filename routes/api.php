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

Route::apiResource('/news', 'App\Http\Controllers\NewsController');
Route::put('/news/{news}/restore', 'App\Http\Controllers\NewsController@restore')->name('news.restore');

Route::get('/tag', 'App\Http\Controllers\TagController@index')->name('tag.index');

Route::get('/user', 'App\Http\Controllers\UserController@index')->name('user.index');