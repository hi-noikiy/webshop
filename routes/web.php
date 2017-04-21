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

Route::get('/', 'HomeController@home')->name('home');
Route::get('about', 'HomeController@about');
Route::get('contact', 'HomeController@contact');
Route::get('downloads', 'HomeController@downloads');
Route::get('licenses', 'HomeController@licenses');
Route::get('assortment', 'HomeController@assortment');
Route::get('download/{filename}', 'HomeController@download');

Route::group(['as' => 'auth.'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', 'UserController@getLogin')->name('login');
        Route::get('password/email', 'Auth\PasswordController@getEmail')->name('password::email');
        Route::get('password/reset/{token}', 'Auth\PasswordController@getReset')->name('password::reset');
        Route::get('register', 'UserController@register')->name('register');
        Route::get('register/sent', 'UserController@registerSent');

        Route::post('login', 'UserController@postLogin');
        Route::post('register', 'UserController@register_check');
        Route::post('password/email', 'Auth\PasswordController@postEmail');
        Route::post('password/reset', 'Auth\PasswordController@postReset');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('logout', 'UserController@logout')->name('logout');
    });
});

Route::group([
    'as' => 'customer::',
    'prefix' => 'customer',
    'namespace' => 'Customer',
    'middleware' => 'auth'
], function () {
    Route::group(['as' => 'account.'], function () {
        Route::get('/', 'DashboardController@view')->name('dashboard');
        Route::get('accounts', 'SubAccountController@view')->name('accounts');
        Route::get('password', 'PasswordController@view')->name('password');

        Route::get('favorites', 'FavoritesController@view')->name('favorites');
        Route::group([
            'prefix' => 'favorites',
            'as' => 'favorites::'
        ], function () {
            Route::get('check/{product}', 'FavoritesController@check')->name('check');

            Route::post('add/{product}', 'FavoritesController@add')->name('add');
            Route::post('delete/{favorite}', 'FavoritesController@delete')->name('delete');
        });

        Route::get('history', 'OrderHistoryController@view')->name('history');
        Route::get('addresses', 'AddressController@view')->name('addresses');
        Route::get('discountfile', 'DiscountfileController@view')->name('discountfile');
    });
});

Route::group([
    'as' => 'search::',
    'prefix' => 'search',
    'namespace' => 'Search'
], function () {
    Route::post('suggest', 'SuggestController@view')->name('suggest');
});

Route::group([
    'as' => 'catalog::',
    'prefix' => 'catalog',
    'namespace' => 'Catalog'
], function () {
    Route::get('product/{sku}', 'ProductController@view')->name('product');

    Route::group([
        'as' => 'assortment.',
        'prefix' => 'assortment'
    ], function () {
        Route::get('/', 'AssortmentController@view')->name('index');
        Route::get('specials', 'SpecialsController@view')->name('specials');
    });
});

/*
 * Webshop routes
 */
//Route::get('webshop', 'WebshopController@main');
//Route::get('product/{product}', 'ProductController@showProduct')->name('product');
//Route::get('search', 'SearchController@search');
//Route::get('specials', 'SearchController@specials');
//Route::get('clearance', 'SearchController@clearance');

/**
 * Checkout routes
 */
Route::group([
    'middleware' => ['web', 'auth'],
    'namespace' => 'Checkout',
    'prefix' => 'checkout',
    'as' => 'checkout::'
], function() {
    Route::group([
        'prefix' => 'cart',
        'as' => 'cart.'
    ], function () {
        Route::get('/', 'CartController@view')->name('index');

        Route::put('add', 'CartController@add')->name('add');

        Route::patch('update/{item}', 'CartController@update')->name('update'); // Edit a single update

        Route::delete('delete/{item}', 'CartController@delete')->name('delete');
        Route::delete('destroy', 'CartController@destroy')->name('destroy');
    });
});