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

Route::middleware(['auth:web'])->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::middleware(['auth:admin'])->group(function() {
    Route::get('/admin', 'HomeController@indexAdmin')->name('homeAdmin');
    
    Route::prefix('/users')->name('users.')->group(function(){
        Route::get('/', 'UserController@index')->name('index')->middleware('can:viewAny,App\User');
        Route::get('/{user}', 'UserController@show')->name('view')->middleware('can:view,user');
        Route::delete('/{user}', 'UserController@destroy')->name('delete')->middleware('can:delete,user');
        Route::put('/{user}', 'UserController@restore')->name('restore')->middleware('can:restore,App\User');
    });
    
    Route::prefix('/nations')->name('nations.')->group(function(){
        Route::get('/', 'NationController@index')->name('index')->middleware('can:viewAny,App\Nation');
        Route::get('/{nation}', 'NationController@show')->name('view')->middleware('can:view,nation');
        Route::delete('/{nation}', 'NationController@destroy')->name('delete')->middleware('can:delete,nation');
        Route::put('/{nation}', 'NationController@restore')->name('restore')->middleware('can:restore,App\Nation');
    });
    
    Route::prefix('/ideas')->name('ideas.')->group(function(){
        Route::get('/', 'IdeaController@index')->name('index')->middleware('can:viewAny,App\Idea');
        Route::get('/{idea}', 'IdeaController@show')->name('view')->middleware('can:view,idea');
        Route::delete('/{idea}', 'IdeaController@destroy')->name('delete')->middleware('can:delete,idea');
        Route::put('/{idea}', 'IdeaController@restore')->name('restore')->middleware('can:restore,App\Idea');
    });
    
    Route::prefix('/user_types')->name('user_types.')->group(function(){
        Route::get('/', 'UserTypeController@index')->name('index')->middleware('can:viewAny,App\UserType');
        Route::get('/{user_type}', 'UserTypeController@show')->name('view')->middleware('can:view,user_type');
        Route::delete('/{user_type}', 'UserTypeController@destroy')->name('delete')->middleware('can:delete,user_type');
        Route::put('/{user_type}', 'UserTypeController@restore')->name('restore')->middleware('can:restore,App\UserType');
    });
    
    Route::prefix('/campaigns')->name('campaigns.')->group(function(){
        Route::get('/', 'CampaignController@index')->name('index')->middleware('can:viewAny,App\Campaign');
        Route::get('/{campaign}', 'CampaignController@show')->name('view')->middleware('can:view,campaign');
        Route::delete('/{campaign}', 'CampaignController@destroy')->name('delete')->middleware('can:delete,campaign');
        Route::put('/{campaign}', 'CampaignController@restore')->name('restore')->middleware('can:restore,App\Campaign');
    });
    
});