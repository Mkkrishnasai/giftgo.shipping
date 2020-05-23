<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

//Outbound order routes
Route::get('/datatable','OutboundOrderController@datatable')->name('outbound_order_datatable');

Route::get('/outboundorder','OutboundOrderController@index')->name('outboundorder');

Route::post('/storecsv','OutboundOrderController@storeCSVFile')->name('storecsvfile');
