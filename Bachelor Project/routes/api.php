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

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/forum', 'ForumController@store');
    Route::get('/forum','ForumController@index');
    

    Route::get('/post/{postHash}/{title}','ForumController@show');
    Route::get('/postLikes','ForumController@checkLikes');
    Route::post('/postLikeAction','ForumController@likeAction');
    Route::delete('/postDelete/{postHash}/{title}','ForumController@destroy');
    Route::put('/postEdit/{postHash}/{title}','ForumController@update');


    Route::post('/commentAdd','ForumCommentsController@store');
    Route::get('/commentGet/{postHash}/{title}','ForumCommentsController@show');
    Route::post('/commentLikeAction','ForumCommentsController@likeAction');
    Route::post('/commentReply','ForumCommentsController@addReply');
    Route::delete('/commentDelete/{commentId}','ForumCommentsController@destroy');

    Route::get('/profile/{id}', 'UsersController@showAccount');
    Route::put('/profile/{id}', 'UsersController@update');
    Route::get('/user/{username}', 'UsersController@showProfile');

    Route::get('/matches', 'MatchesController@show');
    Route::get('/matches/{league}','MatchesController@show');
    Route::get('/seasons/{league}','SeasonsController@show');
    Route::get('/standings/{league}','StandingsController@show');

    Route::post('/bets','BetsController@store');

    Route::get('/leaderboards', 'ScoresController@index');
    Route::get('/leaderboards/{id}/{leagues}', 'ScoresController@show');
    
});
Route::post('/logout', 'AuthController@logout');
Route::post('/verify','AuthController@verify');
Route::post('/login','AuthController@login');
Route::post('/register','AuthController@register');
Route::post('/refreshToken', 'AuthController@refresh');

Route::get('/redirect', ['middleware' => ['cors','web'] , 'uses'=> 'SocialController@redirectToProvider']);
Route::get('/callback', ['middleware' => ['cors','web'] , 'uses'=> 'SocialController@handleProviderCallback']);



