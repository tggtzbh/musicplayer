<?php

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

Route::get('/', "MusicMainControler@getIndexView");
Route::get('getMusicList',"MusicMainControler@getMusicList");
Route::get('getIndexList',"MusicMainControler@getIndexList");
Route::post('getMusicResouse',"MusicMainControler@getMusicResouse");
