<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getHomeDatas(Request $request)
    {
        $savedMovies = $request->user()->movies()->wherePivot('watched', false);

        if ($request->has('genre')) {
            $savedMovies = $savedMovies->whereHas('genres', function ($query) use ($request) {
                $query->where('id', $request->input('genre'));
            });
            $selectedGenre = Genre::find($request->input('genre'));
            $selectedGenre = $selectedGenre->name;
        }

        $savedMovies = $savedMovies->with('genres')->get();
        $allGenres = Genre::withCount('movies')->get();

        return view('home', ['savedMovies' => $savedMovies, 'allGenres' => $allGenres, isset($selectedGenre) ? "'selectedGenre' => $selectedGenre" : '']);
    }
}
