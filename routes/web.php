<?php

Route::group(['prefix' => 'api'], function() {
    Route::resource('teams', 'TeamsController', [
        'except' => ['edit', 'create']
    ]);

    Route::resource('teams/registration', 'TeamsRegistrationController', [
        'only' => ['store', 'destroy'] //user_id, team_id, type="member, channel"
    ]);

    Route::resource('channels/registration', 'ChannelsRegistrationController', [
        'only' => ['store', 'destroy'] //user_id, channel_id
    ]);

    Route::resource('channels', 'ChannelsController', [
        'except' => ['edit, create']
    ]);

    Route::resource('messages', 'MessagesController', [
        'except' => ['edit', 'create']
    ]);

    Route::post('user', [
        'uses' => 'AuthController@store'
    ]);

    Route::post('user/signin', [
        'uses' => 'AuthController@signin'
    ]);
});
