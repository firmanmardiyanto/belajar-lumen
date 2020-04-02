<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/kamar','KamarController@index');
$router->get('/kamar/{id}','KamarController@show');

Route::group(['middleware' => ['auth']], function ($router){
$router->get('/pelanggan','PelangganController@index');
$router->post('/pelanggan','PelangganController@store');
$router->get('/pelanggan/{id}','PelangganController@show');
$router->put('/pelanggan/{id}','PelangganController@update');
$router->delete('/pelanggan/{id}','PelangganController@delete');

$router->post('/kamar','KamarController@store');
$router->put('/kamar/{id}','KamarController@update');
$router->delete('/kamar/{id}','KamarController@delete');

$router->get('/pemesanan','PemesananController@index');
$router->post('/pemesanan','PemesananController@store');
$router->get('/pemesanan/{id}','PemesananController@show');
$router->put('/pemesanan/{id}','PemesananController@update');
$router->delete('/pemesanan/{id}','PemesananController@delete');

$router->get('/pembayaran','PembayaranController@index');
$router->post('/pembayaran','PembayaranController@store');
$router->get('/pembayaran/{id}','PembayaranController@show');
$router->put('/pembayaran/{id}','PembayaranController@update');
$router->delete('/pembayaran/{id}','PembayaranController@delete');

$router->get('/users','UsersController@index');
$router->post('/users','UsersController@store');
$router->get('/users/{id}','UsersController@show');
$router->put('/users/{id}','UsersController@update');
$router->delete('/users/{id}','UsersController@delete');

$router->post('/auth/logout','AuthController@logout');
});

//authentication
$router->group(['prefix' => 'auth'], function () use ($router) {
$router->post('/register','AuthController@register');
$router->post('/login','AuthController@login');
});
