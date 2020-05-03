<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::get('/2016', 'DashboardController@index')->name('sales2016');
Route::get('/2017', 'DashboardController@index')->name('sales2017');
Route::get('/2018', 'DashboardController@index')->name('sales2018');

Route::get('/', 'DashboardController@index');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/search', 'DashboardController@search');

Route::get('/graphs', 'GraphController@index');
Route::get('/graphs/taxshipping/2016', 'GraphController@index')->name('2016');
Route::get('/graphs/taxshipping/2017', 'GraphController@index')->name('2017');
Route::get('/graphs/taxshipping/2018', 'GraphController@index')->name('2018');



