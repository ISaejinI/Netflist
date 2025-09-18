<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'getHomeDatas'])->name('home');

// Route::get('/popularMovies', [MovieController::class, 'getPopular'])->name('popularmovies');
// Route::get('/popular/{page}', [App\Http\Controllers\MovieController::class, 'getPopular'])->name('popularmovies');

// Route::post('/storeMovie', [MovieController::class, 'storeMovie'])->name('storemovie');

// Route::post('/markAsWatched', [MovieController::class, 'markAsWatched'])->name('watched');

// Route::get('/myWatchlist', [MovieController::class, 'index'])->name('savedmovies');

// Route::delete('/deleteMovie', [MovieController::class, 'deleteMovie'])->name('deletemovie');

// Route::get('/search', [MovieController::class, 'searchMovie'])->name('search');


Route::controller(MovieController::class)->group(function() {
    Route::get('/popularMovies', 'getPopular')->name('popularmovies');
    Route::post('/storeMovie', 'storeMovie')->name('storemovie');
    Route::post('/markAsWatched', 'markAsWatched')->name('watched');
    Route::get('/myWatchlist', 'index')->name('savedmovies');
    Route::delete('/deleteMovie', 'deleteMovie')->name('deletemovie');
    Route::get('/search', 'searchMovie')->name('search');
});