<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
    
Route::middleware('auth:api')->group(function(){
    Route::post('/users/{user}/invite/{idea}', 'UserController@invite')->name('api.users.invite')->middleware('throttle:100,1,invite', 'can:invite,user,idea');
});

// Moved default throttle from Kernel
Route::middleware('throttle:60,1')->group(function() {
    
    Route::prefix('/nations')->name('nations.')->group(function(){
        Route::get('/', 'NationController@indexApi')->name('indexapi');
    });
    
    Route::prefix('/countries')->name('countries.')->group(function(){
        Route::get('/', 'CountryController@indexApi')->name('indexapi');
    });
    
    Route::prefix('/cities')->name('cities.')->group(function(){
        Route::get('/', 'CityController@indexApi')->name('indexapi');
    });
    
    Route::prefix('/municipalities')->name('municipalities.')->group(function(){
        Route::get('/', 'MunicipalityController@indexApi')->name('indexapi');
    });
    
}); 