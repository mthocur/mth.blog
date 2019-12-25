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

use App\PostView;
use Carbon\Carbon;

Auth::routes();

Route::get('/404', function(){
    return abort(404);
})->name("404");

Route::get('/', 'BlogController@blog');
Route::get('/home', 'BlogController@blog')->name('home');

Route::get('/blog', 'BlogController@blog')->name('blogList');
Route::get('/blog/post/{slug}', 'BlogController@post')->name('blogPost');
Route::post('/blog/comments/create/{post_id}', 'CommentController@make')->name('writeComment');
Route::get('/blog/category/{slug}', 'BlogController@category')->name('blogCategory');


Route::group(['prefix' => 'admin',  'middleware' => 'auth'], function () {

    Route::get('/', "AdminController@dashboard")->name("dashboard");

    Route::prefix('/category')->group(function () {
        Route::get('/', "CategoryController@index")->name("categoryMain");
        Route::get('/add', "CategoryController@create")->name("categoryCreate");
        Route::post('/store', "CategoryController@store")->name("categoryStore");
        Route::get('/edit/{id}', "CategoryController@edit")->name("categoryEdit");
        Route::post('/update/{id}', "CategoryController@update")->name("categoryUpdate");
        Route::get('/list', "CategoryController@index")->name("categoryIndex");
        Route::get('/listAjax', "CategoryController@ajax")->name("categoryAjax");
        Route::get('/delete/{id}', "CategoryController@destroy")->name("categoryDestroy");
    });

    Route::prefix('/post')->group(function () {
        Route::get('/', "PostController@index")->name("postMain");
        Route::get('/add', "PostController@create")->name("postCreate");
        Route::post('/store', "PostController@store")->name("postStore");
        Route::get('/edit/{id}', "PostController@edit")->name("postEdit");
        Route::post('/update/{id}', "PostController@update")->name("postUpdate");
        Route::get('/list', "PostController@index")->name("postIndex");
        Route::get('/listAjax', "PostController@ajax")->name("postAjax");
        Route::get('/delete/{id}', "PostController@destroy")->name("postDestroy");
    });

    Route::prefix('/comment')->group(function () {
        Route::get('/', "CommentController@index")->name("commentMain");
        Route::get('/edit/{id}', "CommentController@edit")->name("commentEdit");
        Route::post('/update/{id}', "CommentController@update")->name("commentUpdate");
        Route::get('/list', "CommentController@index")->name("commentIndex");
        Route::get('/listAjax', "CommentController@ajax")->name("commentAjax");
        Route::get('/delete/{id}', "CommentController@destroy")->name("commentDestroy");
    });
});
