<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function (){
    Route::post('register','Register');
    Route::post('login','login');
    Route::get('profile','profile')->middleware('auth:sanctum');
    Route::post('changePassword','changePassword')->middleware('auth:sanctum');
});

Route::controller(MenuController::class)->group(function(){
    Route::post('Store_menu','Store')->middleware('auth:sanctum');
    Route::get('index_menu','index')->middleware('auth:sanctum');
    Route::put('update_menu/{id}','update')->middleware('auth:sanctum');
    Route::delete('delete_menu/{id}','destroy')->middleware('auth:sanctum');
    Route::get('show_menu/{id}','show')->middleware('auth:sanctum');
});

Route::controller(ImageController::class)->group(function(){
    Route::post('upload_image/{menu}','uploadImage')->middleware('auth:sanctum');
    Route::put('update_image/{menu}','updateImage')->middleware('auth:sanctum');
});

Route::controller(OrderController::class)->group( function(){
    Route::post('store_order','store')->middleware('auth:sanctum');
    Route::delete('destroy/{id}','destroy')->middleware('auth:sanctum');
    Route::get('index_order','index')->middleware('auth:sanctum');
    Route::get('show_order/{id}','show')->middleware('auth:sanctum');
    Route::post('update_order/{id}','update')->middleware('auth:sanctum');
});