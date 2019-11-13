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
})->name('index');

Auth::routes();

Route::prefix('/ideas')->name('ideas.')->group(function(){
    Route::post('/search', 'IdeaController@search')->name('search');
});

Route::middleware(['auth:admin,web'])->group(function() {
    Route::prefix('/nations')->name('nations.')->group(function(){
        Route::get('/', 'NationController@index')->name('index')->middleware('can:viewAny,App\Nation');
    });
    
    Route::prefix('/media')->name('media.')->group(function(){
        Route::post('/store', 'MediaController@store')->name('store');
        Route::delete('/{media}', 'MediaController@destroy')->name('delete')->middleware('can:delete,media');
    });
    
    Route::prefix('/passports')->name('passports.')->group(function(){
        Route::delete('/{passport}', 'PassportController@destroy')->name('delete')->middleware('can:delete,passport');
    });
    
    Route::prefix('/ideas')->name('ideas.')->group(function(){
        Route::get('/', 'IdeaController@index')->name('index')->middleware('can:viewAny,App\Idea');
        Route::get('/ipi', 'IdeaController@ipi')->name('ipi')->middleware('can:viewAny,App\Idea');
        Route::get('/{idea}', 'IdeaController@show')->name('view')->middleware('can:view,idea');
    });
    
    Route::prefix('/users')->name('users.')->group(function(){
        Route::match(['get', 'post'], '/search', 'UserController@search')->name('search')->middleware('can:searchAny,App\User');        
        Route::get('/{user}', 'UserController@show')->name('view')->middleware('can:view,user');
        Route::get('/{user}/edit', 'UserController@edit')->name('edit')->middleware('can:update,user');
        Route::put('/{user}', 'UserController@update')->name('update')->middleware('can:update,user');
        Route::delete('/{user}', 'UserController@destroy')->name('delete')->middleware('can:delete,user');
    });
    
    Route::prefix('/search_names')->name('search_names.')->group(function(){
        Route::get('/create', 'SearchNameController@create')->name('create')->middleware('can:create,App\SearchName');
        Route::post('/', 'SearchNameController@store')->name('store')->middleware('can:create,App\SearchName');
        Route::get('/{search_name}/edit', 'SearchNameController@edit')->name('edit')->middleware('can:view,search_name');
        Route::put('/{search_name}', 'SearchNameController@update')->name('update')->middleware('can:update,search_name');
    });

});

Route::middleware(['auth:web'])->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::middleware(['auth:admin'])->group(function() {
    Route::get('/admin', 'HomeController@indexAdmin')->name('homeAdmin');
    
    Route::prefix('/users')->name('users.')->group(function(){
        Route::get('/', 'UserController@index')->name('index')->middleware('can:viewAny,App\User');
        Route::put('/{user}/restore', 'UserController@restore')->name('restore')->middleware('can:restore,user');
        Route::put('/{user}/verify', 'UserController@verify')->name('verify')->middleware('can:verify,user');
        Route::delete('/{user}/verify', 'UserController@unverify')->name('unverify')->middleware('can:verify,user');
    });
    
    Route::prefix('/nations')->name('nations.')->group(function(){
        Route::get('/{nation}', 'NationController@show')->name('view')->middleware('can:view,nation');
        Route::delete('/{nation}', 'NationController@destroy')->name('delete')->middleware('can:delete,nation');
        Route::put('/{nation}', 'NationController@restore')->name('restore')->middleware('can:restore,App\Nation');
    });
    
    Route::prefix('/ideas')->name('ideas.')->group(function(){
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
    
    Route::prefix('/petitions')->name('petitions.')->group(function(){
        Route::get('/', 'PetitionController@index')->name('index')->middleware('can:viewAny,App\Petition');
        Route::get('/{petition}', 'PetitionController@show')->name('view')->middleware('can:view,petition');
        Route::delete('/{petition}', 'PetitionController@destroy')->name('delete')->middleware('can:delete,petition');
        Route::put('/{petition}', 'PetitionController@restore')->name('restore')->middleware('can:restore,App\Petition');
    });
    
    Route::prefix('/meetings')->name('meetings.')->group(function(){
        Route::get('/', 'MeetingController@index')->name('index')->middleware('can:viewAny,App\Meeting');
        Route::get('/{meeting}', 'MeetingController@show')->name('view')->middleware('can:view,meeting');
        Route::delete('/{meeting}', 'MeetingController@destroy')->name('delete')->middleware('can:delete,meeting');
        Route::put('/{meeting}', 'MeetingController@restore')->name('restore')->middleware('can:restore,App\Meeting');
    });
    
    Route::prefix('/contents')->name('contents.')->group(function(){
        Route::get('/', 'ContentController@index')->name('index')->middleware('can:viewAny,App\Content');
        Route::get('/{content}', 'ContentController@show')->name('view')->middleware('can:view,content');
        Route::delete('/{content}', 'ContentController@destroy')->name('delete')->middleware('can:delete,content');
        Route::put('/{content}', 'ContentController@restore')->name('restore')->middleware('can:restore,App\Content');
    });
    
});