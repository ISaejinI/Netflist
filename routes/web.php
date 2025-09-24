<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SinglemovieController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'getHomeDatas'])->name('home');

Route::controller(MovieController::class)->group(function() {
    Route::get('/popularMovies/{page?}', 'getPopularMovies')->name('popularmovies');
    Route::get('/bestRatedMovies', 'getBestRated')->name('bestratedmovies');
    Route::post('/storeMovie', 'storeMovie')->name('storemovie');
    Route::post('/markAsWatched', 'markAsWatched')->name('watched');
    Route::get('/myWatchlist', 'index')->name('savedmovies');
    Route::delete('/deleteMovie', 'deleteMovie')->name('deletemovie');
    Route::get('/search', 'searchMovie')->name('search');
    // Series
    Route::get('/popularSeries', 'getPopularTV')->name('popularseries');
});

Route::get('/movie/{id}', [SinglemovieController::class, 'movieDetail'])->name('moviedetail');

Route::controller(UserController::class)->group(function() {
    Route::get('/login', 'loginPage')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/register', 'registerPage')->name('register');
    Route::post('/registerInfo', 'registerInfo')->name('registerinfo');
    Route::get('/logout', 'logout')->name('logout');
});