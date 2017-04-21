<?php

Route::group(['as' => 'account.'], function () {
    Route::get('/', 'DashboardController@view')->name('dashboard');

    Route::get('accounts', 'SubAccountController@view')->name('accounts');
    Route::group([
        'prefix' => 'accounts',
        'as' => 'accounts::'
    ], function () {
        Route::post('/', 'SubAccountController@store');
        Route::post('update/{id}', 'SubAccountController@update')->name('update');
        Route::post('remove', 'SubAccountController@destroy')->name('delete');
    });

    Route::get('password', 'PasswordController@view')->name('password');
    Route::post('password', 'PasswordController@doChangePassword');

    Route::get('favorites', 'FavoritesController@view')->name('favorites');
    Route::group([
        'prefix' => 'favorites',
        'as' => 'favorites::'
    ], function () {
        Route::post('add', 'FavoritesController@add')->name('add');
        Route::post('check', 'FavoritesController@check')->name('check');
        Route::post('delete', 'FavoritesController@delete')->name('delete');
    });

    Route::get('history', 'OrderHistoryController@view')->name('history');
    Route::group([
        'prefix' => 'history',
        'as' => 'history::'
    ], function () {
        Route::get('{order}', 'OrderHistoryController@addOrderToCart')->name('reorder');
    });

    Route::get('addresses', 'AddressController@view')->name('addresses');
    Route::group([
        'prefix' => 'addresses',
        'as' => 'addresses::'
    ], function () {
        Route::post('add', 'AddressController@add')->name('add');
        Route::post('delete/{id}', 'AddressController@delete')->name('delete');
    });

    Route::get('discountfile', 'DiscountfileController@view')->name('discountfile');
    Route::group([
        'prefix' => 'discountfile',
        'as' => 'discountfile::'
    ], function () {
        Route::get('generate/{type}/{method}', 'DiscountfileController@generate')->name('generate');
    });
});