<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@show')->name('frontend');

Route::get('/paid', ['middleware' => 'subscribed', function () {
    // Route ONLY for Subscribed User
}]);
Route::get('/dashboard', 'HomeController@show')->name('dashboard');
