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

Route::get('/home', function () {
//    return view('welcome');
    return redirect('/');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
//Route::get('/', function(){
//    return "Hola";
//});

/** ABILITIES */
Route::resource('abilities','PermissionController');

/** FILES */
Route::resource('file','FileController');
Route::get('view-file/{folder}/{filename}','FileController@viewFile');
Route::get('download-file/{folder}/{filename}','FileController@downloadFile');

/** EXCEL */
Route::resource('excel','ExcelController');
Route::apiResource('permission', 'PermissionController');
Route::get('send-email', 'EmailController@send');
Route::get('/seguimiento', function () {
    return view('seguimiento');
});
Route::get('/formulario/{id}', function () {
    return view('formulario_experiencia');
});