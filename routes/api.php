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
    
    Route::prefix('/approval_rating')->name('approval_rating.')->group(function(){
        Route::post('/', 'ApprovalRatingController@voteApi')->name('voteapi');
    });
});

Route::prefix('/approval_rating')->name('approval_rating.')->group(function(){
    Route::get('/', 'ApprovalRatingController@indexApi')->name('indexapi');
});

// Moved default throttle from Kernel
Route::middleware('throttle:60,1')->group(function() {

    Route::prefix('/comments')->name('comments.')->group(function(){
        Route::post('/{comment}/moderate/{action}', 'CommentController@moderate')->name('moderate')->middleware('auth:api', 'can:moderate,comment');
    });
    
    Route::prefix('/nations')->name('nations.')->group(function(){
        Route::get('/', 'NationController@indexApi')->name('indexapi');
    });
    
    Route::prefix('/parties')->name('parties.')->group(function(){
        Route::get('/', 'PartyController@indexApi')->name('indexapi');
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
    
    Route::prefix('/subdivisions')->name('subdivisions.')->group(function(){
        Route::get('/', 'SubdivisionController@indexApi')->name('indexapi')->middleware('auth:api');
    });
    
    Route::prefix('/leads')->name('leads.')->group(function(){
        Route::get('/my', 'UserController@indexApi')->name('indexapi')->middleware('auth:api');
    });
    
    Route::prefix('/ideas')->name('ideas.')->group(function(){
        Route::get('/{idea}', 'IdeaController@showApi')->name('viewapi')->middleware('auth:api', 'can:view,idea')->where('idea', '[0-9]+');
    });
}); 