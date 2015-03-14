<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'AppController@home');

Route::get('home', 'HomeController@home');

// Sound file processing
Route::get('file/sound', 'FileController@getTable');
Route::get('file/edit/{id}-{enable}', 'FileController@edit');
Route::post('file/upload', 'FileController@upload');

Route::get('settings', 'SettingsController@home');

Route::controllers([
	'auth'     => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
