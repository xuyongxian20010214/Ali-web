<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    //用户管理
    $router->resource('/demo/users/', UserController::class);
    //文件上传管理
    $router->resource('/upload/file/', UploadController::class);
});
