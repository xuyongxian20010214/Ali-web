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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->Middleware('check');

//TEST-代码测试
Route::get('/test/static','Test\TestController@index')->Middleware('check');

//冒泡排序
Route::get('/test/sort','Test\TestController@sort')->Middleware('check');
Route::get('/test/selectsort','Test\TestController@selectsort')->Middleware('check');

//TEST-防刷
Route::get('test/fs','Test\TestController@FS');