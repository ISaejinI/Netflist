<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'getHomeDatas'])->name('home');

Route::controller(MovieController::class)->group(function() {
    Route::get('/popularMovies/{page?}', 'getPopular')->name('popularmovies');
    Route::get('/bestRatedMovies', 'getBestRated')->name('bestratedmovies');
    Route::post('/storeMovie', 'storeMovie')->name('storemovie');
    Route::post('/markAsWatched', 'markAsWatched')->name('watched');
    Route::get('/myWatchlist', 'index')->name('savedmovies');
    Route::delete('/deleteMovie', 'deleteMovie')->name('deletemovie');
    Route::get('/search', 'searchMovie')->name('search');
});