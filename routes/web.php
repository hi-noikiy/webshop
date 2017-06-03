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



// TODO: Move to packages ?




Route::group([
    'as' => 'search::',
    'prefix' => 'search',
    'namespace' => 'Search'
], function () {
    Route::post('suggest', 'SuggestController@view')->name('suggest');
});