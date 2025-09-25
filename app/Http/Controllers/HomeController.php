<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getHomeDatas(Request $request)
    {
        if (Auth::check()) {
            $savedMovies = $request->user()->titles()->orderBy('title_user.watched')->orderByDesc('title_user.created_at');

            if ($request->has('genre') && $request->input('genre') != "") {
                $savedMovies = $savedMovies->whereHas('genres', function ($query) use ($request) {
                    $query->where('id', $request->input('genre'));
                });
                $selectedGenre = Genre::find($request->input('genre'));
                $selectedGenre = $selectedGenre->name;
            }

            $savedMovies = $savedMovies->with('genres')->get();

            $allUserGenres = Genre::whereHas('titles', function ($query) use ($request) {
                $query->whereHas('users', function ($q) use ($request) {
                    $q->where('users.id', $request->user()->id)
                        ->where('title_user.watched', false);
                });
            })
            ->withCount(['titles as movies_count' => function ($query) use ($request) {
                $query->whereHas('users', function ($q) use ($request) {
                    $q->where('users.id', $request->user()->id)
                        ->where('title_user.watched', false);
                });
            }])->get();

            $movies = $savedMovies->where('is_movie', true);
            $series = $savedMovies->where('is_movie', false);

            return view('home', ['movies' => $movies, 'series' => $series, 'allGenres' => $allUserGenres, isset($selectedGenre) ? "'selectedGenre' => $selectedGenre" : '']);
        } else {
            return view('home');
        }
    }
}
