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

Route::get('/', 'WelcomeController@index');

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/user', 'UserController@index');
Route::post('/user', 'UserController@update');


Route::get('/messages', 'MessageController@index');
Route::get('/message/{id}', 'MessageController@show');
Route::post('/message/update', 'MessageController@postUpdate');
Route::post('message/post', 'MessageController@postMessage');
Route::post('message/post/image', 'MessageController@postImage');


Route::get('/users', 'UsersController@index');
Route::get('/users/{id}', 'UsersController@show');
Route::post('/users/update', 'UsersController@postUpdate');
Route::post('/users/update/{id}', 'UsersController@putUpdate');


//------ Auth Routes--------------------\\

Route::get('login', 'Auth\LoginController@getlogin')->name('login');
Route::post('login', 'Auth\LoginController@login');

Route::post('register', 'Auth\RegisterController@register');
Route::get('register', 'Auth\RegisterController@getregister')->name('register');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');




Route::POST('/follows/{id}', 'FollowController@update');