<?php

use App\Http\Controllers\APITitlesController;
use App\Http\Controllers\SavedTitlesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Routes qui font appel à l'API
Route::controller(APITitlesController::class)->group(function() {
    Route::get('/popular/{page?}', 'getPopularTitles')->name('populartitles');
    Route::get('/bestRatedMovies', 'getBestRated')->name('bestratedmovies');
    Route::post('/storeMovie', 'storeMovie')->name('storemovie');
    Route::post('/storeSerie', 'storeSerie')->name('storeserie');
    Route::get('/search', 'searchTitle')->name('search');
});

//Routes qui font appel à la BDD
Route::controller(SavedTitlesController::class)->group(function() {
    Route::get('/', 'getHomeDatas')->name('home');
    Route::get('/movie/{id}', 'movieDetail')->name('moviedetail');
    Route::post('/markAsWatched', 'markAsWatched')->name('watched');
    Route::post('/markEpisodeAsWatched', 'markEpisodeAsWatched')->name('watchepisode');
    Route::delete('/deleteTitle', 'deleteTitle')->name('deletetitle');
});

//Routes d'authentification
Route::controller(UserController::class)->group(function() {
    Route::get('/login', 'loginPage')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/register', 'registerPage')->name('register');
    Route::post('/registerInfo', 'registerInfo')->name('registerinfo');
    Route::get('/logout', 'logout')->name('logout');
});