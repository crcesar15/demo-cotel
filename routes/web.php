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

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/map',function (){
    return view('map');
})->name('map');

Route::get('/devices','devicesController@getDevices')->name('devices');
Route::get('/devices_by_type/{device_type_id}','devicesController@getDevicesByType')->name('devices_by_type');

Route::get('/search', function () {
    return view('search');
})->name('search');

Route::post('/get_devices_near', 'devicesController@getDevicesNear')->name('get_devices_near');

Route::get('/routes', function (){return view('admin');})->name('routes');