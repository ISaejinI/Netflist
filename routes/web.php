<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'getHomeDatas'])->name('home');

Route::get('/popular', [MovieController::class, 'getPopular'])->name('popularmovies');
// Route::get('/popular/{page}', [App\Http\Controllers\MovieController::class, 'getPopular'])->name('popularmovies');

//Ajouter une route pour ajouter un film à la DB
Route::post('/store-movie', [MovieController::class, 'storeMovie'])->name('storemovie');

// Faire un groupe avec les deux routes de MoviesController

Route::post('/markAsWatched', [MovieController::class, 'markAsWatched'])->name('watched');

Route::get('/index', [MovieController::class, 'index'])->name('savedmovies');

Route::delete('/deleteMovie', [MovieController::class, 'deleteMovie'])->name('deletemovie');