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

// GET Requests will be handled here
Route::get('/', 'HomeController@home');                                         // Homepage
Route::get('about', 'HomeController@about');                                       // About us
Route::get('contact', 'HomeController@contact');                                 // Contact
Route::get('downloads', 'HomeController@downloads');                             // Downloads
Route::get('licenses', 'HomeController@licenses');                              // Licenses
Route::get('assortment', 'HomeController@assortment');                          // Assortment
Route::get('download/{filename}', 'HomeController@download');                   // Download a file

Route::group(['middleware' => 'guest'], function () {
    Route::get('password/email', 'Auth\PasswordController@getEmail');            // Forgot password page
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');    // Password reset page
    Route::get('register', 'UserController@register');                            // Show the registration page
    Route::get('register/sent', 'UserController@registerSent');                    // Show the registration success page

    Route::post('login', 'UserController@login');                               // Login handler
    Route::post('register', 'UserController@register_check');                    // Validate the registration reguest
    Route::post('password/email', 'Auth\PasswordController@postEmail');            // Reset password handler
    Route::post('password/reset', 'Auth\PasswordController@postReset');
});

/*
 * Webshop routes
 */
Route::get('webshop', 'WebshopController@main');                                // Main webshop page
Route::get('product/{product}', 'ProductController@showProduct');            // Product page
Route::get('search', 'SearchController@search');                                   // Page with the search results
Route::get('specials', 'SearchController@specials');                               // Show only the specials
Route::get('clearance', 'SearchController@clearance');                             // Show only the clearance products

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', 'UserController@logout')->name('auth.logout'); // Logout the current user
    Route::get('reorder/{order_id}', 'WebshopController@reorder');                // Add the items from a previous order to the cart again

    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', 'CartController@view');                                   // Show the cart
        Route::get('destroy', 'CartController@destroy');                        // Remove all items from the cart
        Route::get('order/finished', 'WebshopController@orderFinished');        // Show the order finished page

        Route::post('add', 'CartController@addProduct');                        // Add product to cart
        Route::post('update', 'CartController@update');                            // Update or remove product from cart
        Route::post('order', 'CartController@order');                            // Send the order
    });

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'AccountController@overview');                          // Account overview
        Route::get('favorites', 'AccountController@favorites');                 // Favorites
        Route::get('orderhistory', 'AccountController@orderHistory');           // Order history
        Route::get('addresslist', 'AccountController@addressList');             // Addresslist
        Route::get('discountfile', 'AccountController@discountFile');           // ICC/CSV Discount generation page
        Route::get('generate_{type}/{method}', [
            'middleware' => 'RemoveTempFile',                                    // Middleware to remove the temp csv/icc file after download
            'uses'       => 'AccountController@generateFile',                            // Discount file generation handler
        ]);

        /*
         * Account routes
         */
        Route::group(['namespace' => 'Account'], function () {
            Route::group(['prefix' => 'accounts', 'middleware' => 'manager'], function () {
                Route::get('/', 'SubAccountController@index');

                Route::post('/', 'SubAccountController@store');
                Route::post('update/{id}', 'SubAccountController@update')->name('update_subaccount');
                Route::post('remove', 'SubAccountController@destroy')->name('delete_subaccount');
            });

            // Change password page
            Route::get('password', 'PasswordController@getChangePassword')->name('change_password');

            // Handle the change password request
            Route::post('password', 'PasswordController@doChangePassword');
        });

        Route::post('addAddress', 'AccountController@addAddress');                 // Add address to the database
        Route::post('removeAddress', 'AccountController@removeAddress');        // Remove address from the database
        Route::post('modFav', 'AccountController@modFav');                         // Change the favorites
        Route::post('isFav', 'AccountController@isFav');                           // Check the product array
    });
});

// POST Requests will be handled here
// TODO: Change to something that is not deprecated
Route::when('*', 'csrf', ['post', 'put', 'delete']);
