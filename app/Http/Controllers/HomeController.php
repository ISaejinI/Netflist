<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getHomeDatas(Request $request)
    {
        if (Auth::check()) {
            $savedMovies = $request->user()->movies()->wherePivot('watched', false);

            if ($request->has('genre')) {
                $savedMovies = $savedMovies->whereHas('genres', function ($query) use ($request) {
                    $query->where('id', $request->input('genre'));
                });
                $selectedGenre = Genre::find($request->input('genre'));
                $selectedGenre = $selectedGenre->name;
            }

            $savedMovies = $savedMovies->with('genres')->get();

            $allUserGenres = Genre::whereHas('movies', function ($query) use ($request) {
                $query->whereHas('users', function ($q) use ($request) {
                    $q->where('users.id', $request->user()->id)
                        ->where('movie_user.watched', false); // <-- direct table pivot
                });
            })
            ->withCount(['movies as movies_count' => function ($query) use ($request) {
                $query->whereHas('users', function ($q) use ($request) {
                    $q->where('users.id', $request->user()->id)
                        ->where('movie_user.watched', false); // <-- pareil ici
                });
            }])->get();

            return view('home', ['savedMovies' => $savedMovies, 'allGenres' => $allUserGenres, isset($selectedGenre) ? "'selectedGenre' => $selectedGenre" : '']);
        } else {
            return view('home');
        }
    }
}
