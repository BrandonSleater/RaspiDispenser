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

/* Root */
Route::get('/', 'AppController@page');

/* Home */
Route::get('home', 'HomeController@page');

/* Home - Schedule */
Route::post('schedule/add', 'ScheduleController@add');
Route::post('schedule/update', 'ScheduleController@update');
Route::get('schedule/edit&ID={id}', 'ScheduleController@edit');
Route::get('schedule/table', 'ScheduleController@table');

/* Settings */
Route::get('settings', 'SettingsController@page');

/* Settings - File */
Route::post('file/add', 'FileController@add');
Route::get('file/edit&ID={id}&EN={enable}', 'FileController@edit');
Route::get('file/table', 'FileController@table');

Route::controllers([
	'auth'     => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
