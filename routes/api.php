<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'namespace'=>'App\Api\V1\Controllers'], function ($route){
    $route->post('auth/login','AuthController@login');
    $route->post('auth/register','AuthController@register');

    $route->group(['middleware' => ['auth:sanctum']],function ($api){
        // Protected routes here...
        $api->post('auth/me','AuthController@me');
    });
});

