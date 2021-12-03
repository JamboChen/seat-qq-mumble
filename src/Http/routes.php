<?php
/*
This file is part of SeAT

Copyright (C) 2015 to 2020  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

// Namespace all of the routes for this package.
Route::group([
    'namespace'  => 'Jambo\Seat\QQ\Http\Controllers',
    'middleware' => ['web', 'auth', 'locale'],
    'prefix' => '',
], function () {

    // Your route definitions go here.
    Route::get('/bind-qq', [
        'as'   => 'qq.home',
        'uses' => 'QQController@getHome'
    ]);

    Route::post('/bind-qq', [
        'as'   => 'qq.home',
        'uses' => 'QQController@setQQ'
    ]);

    Route::get('/mumble', [
        'as'   => 'mumble.home',
        'uses' => 'MumbleController@getHome'
    ]);

    Route::post('/mumble', [
        'as'   => 'mumble.home',
        'uses' => 'MumbleController@setpw'
    ]);


});


// API
Route::group([
    'namespace' => 'Jambo\Seat\QQ\Http\Resources',
    'middleware' => ['api.auth'],
    'prefix' => 'api/v2/qq',
], function () {

    Route::get('/bind-qq/{qq?}',[
        'as'   => 'qqApi.api',
        'uses' => 'QQApiController@getQQInfo'
    ]);
});