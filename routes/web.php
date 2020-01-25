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

Route::get('/', function () {
    if (Auth::guest()) {
        return Redirect::to('login');
    }
    if (Auth::check()) {
        return Redirect::to('home');
    }
});

//
// support links
//
Route::get('accessibility', function () {
    return view('support.accessibility');
});
Route::get('cookies', function () {
    return view('support.cookies');
});
Route::get('privacy-policy', function () {
    return view('support.privacy-policy');
});

//
// GitHub authentication
//
Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

//
// email verification
//
Auth::routes(['verify' => true]);

Route::group(['middleware' => ['guest']], function () {
    Route::get('user/request', 'Auth\UserController@request');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('entries/search', 'Catalogue\EntriesController@search')->name('entry.find');
    Route::get('catalogue/search', 'Catalogue\EntriesController@searchCatalogue');

    Route::get('entries', 'Catalogue\EntriesController@index');
    // contributor role required to add or update entries
    Route::group(['middleware' => ['is_contributor']], function () {
        Route::get('entries/create', 'Catalogue\EntriesController@create')->name('entry.create');
        Route::post('entries', 'Catalogue\EntriesController@store')->name('entry.store');
        Route::get('entries/{entry}/edit', 'Catalogue\EntriesController@edit')->name('entry.edit');
        Route::put('entries/{entry}', 'Catalogue\EntriesController@update')->name('entry.update');
        Route::delete('entries/{entry}', 'Catalogue\EntriesController@destroy')->name('entry.delete');
        Route::get('entries/{entry}/copy', 'Catalogue\EntriesController@copy')->name('entry.copy');
    });
    //
    // this route is here because it will stop 'entries/something-else' working
    //
    Route::get('entries/{entry}', 'Catalogue\EntriesController@show')->name('entry.show');
    //
    // dependencies (links)
    //
    Route::get('entries/{entry}/links', 'Catalogue\LinksController@index');
    // contributor role required to add or update links
    Route::group(['middleware' => ['is_contributor']], function () {
        Route::get('entries/{entry}/links/create', 'Catalogue\LinksController@create');
        Route::get('entries/{entry}/links/search', 'Catalogue\LinksController@searchCatalogue');
        Route::post('entries/{entry}/links', 'Catalogue\LinksController@store');
        Route::delete('entries/{entry}/links/{link}', 'Catalogue\LinksController@destroy');
    });
    //
    // user defined tags
    //
    Route::get('entries/{entry}/tags', 'Catalogue\TagsController@index');
    // contributor role required to add or update tags
    Route::group(['middleware' => ['is_contributor']], function () {
        Route::post('entries/{entry}/tags', 'Catalogue\TagsController@store');
        Route::delete('entries/{entry}/tags/{tag}', 'Catalogue\TagsController@destroy');
        Route::post('tags', 'Catalogue\TagsController@createAndStore');
    });
    //
    // admin routes
    //
    Route::group(['middleware' => ['is_admin']], function () {
        Route::get('/admin', 'AdminController@menu');
        // Route::get('/admin/user', 'Auth\UserController@create');
        // Route::post('/admin/user', 'Auth\UserController@store');
        if (App::environment('local')) {
            Route::get('catalogue/export', 'Catalogue\EntriesController@exportCatalogue');
            Route::post('catalogue/import', 'Catalogue\EntriesController@importCatalogue');
            Route::get('catalogue/delete', 'Catalogue\EntriesController@deleteCatalogue');
            Route::get('catalogue/upload', 'Catalogue\EntriesController@uploadCatalogue');
        }
    });

    Route::get('/home', 'HomeController@index')->name('home');
});
