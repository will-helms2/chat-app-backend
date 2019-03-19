<?php

Route::group(['prefix' => 'api'], function() {
  Route::group(['middleware' => ['jwt.verify']], function() {
    Route::resource('teams', 'TeamsController', [
        'except' => ['edit', 'create']
    ]);

    Route::resource('teams/registration', 'TeamsRegistrationController', [
        'only' => ['store', 'destroy']
    ]);

    Route::post('teams/photo', [
        'uses' => 'PhotosController@teamPhoto'
    ]);

    Route::resource('channels/registration', 'ChannelsRegistrationController', [
        'only' => ['store', 'destroy']
    ]);

    Route::resource('channels', 'ChannelsController', [
        'except' => ['edit, create']
    ]);

    Route::post('channels/dm', [
        'uses' => 'ChannelsController@createDM'
    ]);

    Route::resource('messages', 'MessagesController', [
        'except' => ['edit', 'create']
    ]);

    Route::post('messages/file', [
        'uses' => 'PhotosController@messageFile'
    ]);

    Route::resource('invites', 'InvitesController', [
        'except' => ['show', 'edit', 'create']
    ]);

    Route::get('user/validate', [
        'uses' => 'AuthController@validateUser'
    ]);

    Route::get('user/profile', [
        'uses' => 'AuthController@getUserData'
    ]);
  });

    Route::post('user', [
        'uses' => 'AuthController@store'
    ]);

    Route::post('user/signin', [
        'uses' => 'AuthController@signin'
    ]);

    Route::post('user/photo', [
        'uses' => 'PhotosController@userPhoto'
    ]);
});

// Display all SQL executed in Eloquent
// \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
//     var_dump($query->sql);
//     var_dump($query->bindings);
//     var_dump($query->time);
// });
