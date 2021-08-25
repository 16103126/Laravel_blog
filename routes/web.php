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

Route::view('/test', 'test' );

Route::get('/', 'HomeController@index')->name('home');

Route::get('posts', 'PostController@index')->name('post.index');

Route::get('post/{slug}', 'PostController@details')->name('post.details');

Route::post('/subscriber', 'SubscriberController@store')->name('subscriber.store');

Route::get('category/{slug}', 'PostController@postByCategory')->name('category.post');

Route::get('tag/{slug}', 'PostController@postByTag')->name('tag.post');

Route::get('search', 'SearchController@search')->name('search');

Route::get('/profile/{username}', 'AuthorController@profile')->name('author.profile');

Route::group(['middleware' => ['auth']], function(){
    Route::post('favourite/{post}/add', 'FavouriteController@add')->name('post.favourite');
    Route::post('comment/{post}', 'CommentController@store')->name('comment.store');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function(){
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::put('settings/{id}', 'SettingsController@updateProfile')->name('profile.update');
    Route::put('password/{id}', 'SettingsController@updatePassword')->name('password.update');

    Route::resource('tag', 'TagController');
    Route::resource('category', 'CategoryController');
    Route::resource('post', 'PostController');

    Route::put('/post/{id}/approve', 'PostController@approve')->name('post.approve');
    Route::get('pending/post', 'PostController@pending')->name('post.pending');

    Route::get('/favourite', 'FavouriteController@index')->name('favourite.index');

    Route::get('/authors', 'AuthorController@index')->name('authors.index');
    Route::delete('/authors/{id}', 'AuthorController@destroy')->name('author.destroy');

    Route::get('/subscriber', 'SubscriberController@index')->name('subscriber.index');
    Route::delete('/subscriber/{id}', 'SubscriberController@destroy')->name('subscriber.destroy');

    Route::get('comments', 'CommentController@index')->name('comment.index');
    Route::delete('comments/{id}', 'CommentController@destroy')->name('comment.destroy');



});

Route::group(['prefix' => 'author', 'as' => 'author.', 'namespace' => 'Author', 'middleware' => ['auth', 'author']], function(){
    Route::get('dashboard', 'DashboardController@index')->name('dashboard'); 
    Route::resource('post', 'PostController');

    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::put('settings/{id}', 'SettingsController@updateProfile')->name('profile.update');
    Route::put('password/{id}', 'SettingsController@updatePassword')->name('password.update');

    Route::get('/favourite', 'FavouriteController@index')->name('favourite.index');

    Route::get('comments', 'CommentController@index')->name('comment.index');
    Route::delete('comments/{id}', 'CommentController@destroy')->name('comment.destroy');
});

View::composer('layouts.frontend.partials.footer', function($view){
    $categories = App\Category::all();
    $view->with('categories', $categories);
});
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
