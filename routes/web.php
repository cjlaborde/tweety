<?php


//DB::listen(function ($query) { var_dump($query->sql, $query->bindings); });

use Illuminate\Support\Facades\Route;


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

Route::middleware('auth')->group(function () {
    Route::get('/', 'TweetsController@index')->name('home');
    Route::post('/tweets', 'TweetsController@store');
});

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');

Auth::routes();