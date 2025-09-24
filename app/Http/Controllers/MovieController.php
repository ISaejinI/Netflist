<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Director;
use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function getPopularMovies(Request $request)
    {
        $currentPage = $request->route('page', 1);
        if (is_numeric($currentPage) && $currentPage > 0) {
            $popularMovies = $this->getCurlDatas("movie/popular?language=fr-FR&include_adult=false&page=" . $currentPage);
            if (!isset($popularMovies->results) || count($popularMovies->results) === 0) {
                return redirect()->route('popularmovies', ['page' => 1])->with('error', 'Page invalide');
            }
            return view('movies.popular', ['movies' => $popularMovies, 'title' => 'Les films <span class="highlight">populaires</span>', 'type' => 'popular']);
        } else {
            return redirect()->route('popularmovies', ['page' => 1])->with('error', 'Page invalide');
        }
    }

    public function getCurlDatas($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/" . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzYjZiOTA0ODUwOTAwMmI0OGFhNjE3OGFmOTg3OTdmOCIsIm5iZiI6MTUyNjg5MjY4Mi4xMTksInN1YiI6IjViMDI4ODhhMGUwYTI2MjNlMzAxM2NiNiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.U__GCj6NGxqJW_3jGpP29dEbdjeLh0eJ7a5CCmAJzlk",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public function storeMovie(Request $request)
    {
        if ($request->user()) {
            if ($request->has('movie_id') && $request->input('movie_id') > 0) {
                $movie = $this->getCurlDatas('movie/' . $request->input('movie_id') . '?language=fr-FR');
                $cast = $this->getCurlDatas('movie/' . $request->input('movie_id') . '/credits?language=fr-FR');
                // dd($movie);
                $posterUrl = 'https://image.tmdb.org/t/p/w500' . $movie->poster_path;
                $contents = file_get_contents($posterUrl);
                $name = $request->input('movie_id') . 'poster.jpg';
                Storage::disk('public')->put('posters/movies/' . $name, $contents);
                $path = 'posters/movies/' . $name;

                $newMovie = Title::firstOrCreate(
                    [
                        'id_title_tmdb' => $request->input('movie_id'), 
                        'is_movie' => true,
                    ],
                    [
                        'name' => $movie->title,
                        'tagline' => $movie->tagline,
                        'poster_path' => $path,
                        'overview' => $movie->overview,
                        'release_date' => $movie->release_date,
                        'rating' => $movie->vote_average,
                        'origin_country' => isset($movie->origin_country[0]) ? $movie->origin_country[0] : null,
                        'duration' => $movie->runtime,
                    ]
                );

                if (isset($movie->genres)) {
                    foreach ($movie->genres as $genre) {
                        $newGenre = Genre::firstOrCreate(
                            ['id_genre_tmdb' => $genre->id],
                            ['name' => $genre->name]
                        );
                        $newMovie->genres()->attach($newGenre->id);
                    }
                }

                $actors = $cast->cast;
                if (isset($actors)) {
                    $actorSliced = array_slice($actors, 0, 5);
                    foreach ($actorSliced as $actor) {
                        $actorProfileUrl = 'https://image.tmdb.org/t/p/w500' . $actor->profile_path;
                        $contents = file_get_contents($actorProfileUrl);
                        $actorProfileName = $actor->id . 'profile.jpg';
                        Storage::disk('public')->put('actors/' . $actorProfileName, $contents);
                        $actor_profile_path = 'actors/' . $actorProfileName;
                        $newActor = Actor::firstOrCreate(
                            ['id_actor_tmdb' => $actor->id],
                            [
                                'name' => $actor->name, 
                                'actor_profile_path' => $actor_profile_path
                            ]
                        );
                        $newMovie->actors()->attach($newActor->id, ['character' => $actor->character]);
                    }
                }

                $directors = $cast->crew;
                if (isset($directors)) {
                    foreach ($directors as $director) {
                        if ($director->known_for_department == 'Directing') {
                            $directorProfileUrl = 'https://image.tmdb.org/t/p/w185' . $director->profile_path;
                            $contents = file_get_contents($directorProfileUrl);
                            $directorProfileName = $director->id . 'profile.jpg';
                            Storage::disk('public')->put('directors/' . $directorProfileName, $contents);
                            $director_profile_path = 'directors/' . $directorProfileName;
                            $newDirector = Director::firstOrCreate(
                                ['id_director_tmdb' => $director->id],
                                [
                                    'name' => $director->name, 
                                    'director_profile_path' => $director_profile_path
                                ]
                            );
                            $newMovie->directors()->attach($newDirector->id);
                            break;
                        }
                    }
                }

                $request->user()->titles()->attach($newMovie->id, ['watched' => false, 'liked' => false]);

                return back()->with('success', 'Film ajouté');
            } else {
                return back()->with('error', 'Film invalide');
            }
        }
        return redirect()->route('login')->with('error', 'Il faut être connecté pour ajouter un film');
    }

    public function markAsWatched(Request $request)
    {
        if ($request->user()) {
            if ($request->has('movie_id') && $request->input('movie_id') > 0) {
                $movie = $request->user()->titles()->where('titles.id', $request->input('movie_id'))->first();
                if ($movie) {
                    $movie->pivot->watched = true;
                    $movie->pivot->save();
                    return back()->with('success', 'Film marqué comme vu');
                } else {
                    return back()->with('error', 'Film non trouvé');
                }
            }
            return back()->with('error', 'Film invalide');
        }
        return redirect()->route('login')->with('error', 'Il faut être connecté pour marquer un film comme vu');
    }

    public function deleteMovie(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('movie_id') && $request->input('movie_id') > 0) {
                $user = Auth::user();
                $movie = $user->titles()->where('titles.id', $request->input('movie_id'))->first();
                if ($movie) {
                    $user->titles()->detach($movie->id);

                    $usersCount = $movie->users()->count();
                    if ($usersCount === 0) {
                        if ($movie->poster_path && Storage::disk('public')->exists($movie->poster_path)) {
                            Storage::disk('public')->delete($movie->poster_path);
                        }

                        foreach ($movie->genres as $genre) {
                            $movie->genres()->detach($genre->id);
                            if ($genre->movies()->count() === 0) {
                                $genre->delete();
                            }
                        }

                        $movie->actors()->detach();
                        $movie->directors()->detach();

                        $movie->delete();
                    }
                    return back()->with('success', 'Film supprimé');
                } else {
                    return back()->with('error', 'Film non trouvé');
                }
            } else {
                return back()->with('error', 'Film non trouvé');
            }
        } else {
            return back()->with('error', 'Il faut être connecté pour supprimer un film');
        }
    }

    public function index()
    {
        $savedMovies = Title::all()->load('genres');
        return view('index', ['savedMovies' => $savedMovies]);
    }

    public function searchMovie(Request $request)
    {
        if ($request->has('search') && $request->input('search') !== '') {
            $searchName = $request->input('search');
            $searchResults = $this->getCurlDatas('search/movie?query=' . $searchName . '&include_adult=false&language=fr-FR&page=1');
            // dd($searchResults);
            return view('movies.popular', ['movies' => $searchResults, 'title' => 'Résultats de la recherche pour : <span class="highlight">' . $searchName . '</span>', 'type' => 'search']);
        } else {
            return back()->with('error', 'Recherche invalide');
        }
    }

    public function getBestRated()
    {
        $bestRatedMovies = [];
        for ($i = 1; $i <= 5; $i++) {
            $bestRatedMoviesPage = $this->getCurlDatas('movie/top_rated?language=fr-FR&page=' . $i);
            if (isset($bestRatedMoviesPage->results)) {
                $bestRatedMovies = array_merge($bestRatedMovies, $bestRatedMoviesPage->results);
            }
        }
        $bestRatedMovies = (object) ['results' => $bestRatedMovies];
        return view('movies.bestRated', ['movies' => $bestRatedMovies, 'title' => 'Les films les mieux notés', 'type' => 'bestRated']);
    }

    public function getPopularTV(Request $request)
    {
        $currentPage = $request->route('page', 1);
        if (is_numeric($currentPage) && $currentPage > 0) {
            $popularSeries = $this->getCurlDatas("tv/popular?language=fr-FR&include_adult=false&page=" . $currentPage);
            if (!isset($popularSeries->results) || count($popularSeries->results) === 0) {
                return redirect()->route('popularmovies', ['page' => 1])->with('error', 'Page invalide');
            }
            return view('movies.popular', ['movies' => $popularSeries, 'title' => 'Les séries <span class="highlight">populaires</span>', 'type' => 'populartv']);
        } else {
            return redirect()->route('popularmovies', ['page' => 1])->with('error', 'Page invalide');
        }
    }

    public function storeSerie(Request $request)
    {
        // if (Auth::check()) {
        //     if ($request->has('serie_id') && $request->input('serie_id') > 0) {
        //         $movie = $this->getCurlDatas('movie/' . $request->input('serie_id') . '?language=fr-FR');
        //         $cast = $this->getCurlDatas('movie/' . $request->input('serie_id') . '/credits?language=fr-FR');
        //         // dd($cast);
        //         $posterUrl = 'https://image.tmdb.org/t/p/w500' . $movie->poster_path;
        //         $contents = file_get_contents($posterUrl);
        //         $name = $request->input('serie_id') . 'poster.jpg';
        //         Storage::disk('public')->put('posters/' . $name, $contents);
        //         $path = 'posters/' . $name;

        //         $newMovie = Title::firstOrCreate(
        //             ['serie_id' => $request->input('serie_id')],
        //             [
        //                 'title' => $movie->title,
        //                 'poster_path' => $path,
        //                 'description' => $movie->overview,
        //                 'release_date' => $movie->release_date,
        //                 'rating' => $movie->vote_average,
        //                 'origin_country' => isset($movie->origin_country[0]) ? $movie->origin_country[0] : null
        //             ]
        //         );

        //         if (isset($movie->genres)) {
        //             foreach ($movie->genres as $genre) {
        //                 $newGenre = Genre::firstOrCreate(
        //                     ['id_genre_tmdb' => $genre->id],
        //                     ['name' => $genre->name]
        //                 );
        //                 $newMovie->genres()->attach($newGenre->id);
        //             }
        //         }

        //         $actors = $cast->cast;
        //         if (isset($actors)) {
        //             $actorSliced = array_slice($actors, 0, 5);
        //             foreach ($actorSliced as $actor) {
        //                 $actorProfileUrl = 'https://image.tmdb.org/t/p/w500' . $actor->profile_path;
        //                 $contents = file_get_contents($actorProfileUrl);
        //                 $actorProfileName = $actor->id . 'profile.jpg';
        //                 Storage::disk('public')->put('actors/' . $actorProfileName, $contents);
        //                 $actor_profile_path = 'actors/' . $actorProfileName;
        //                 $newActor = Actor::firstOrCreate(
        //                     ['tmdb_actor_id' => $actor->id],
        //                     ['name' => $actor->name, 'avatar_path' => $actor_profile_path]
        //                 );
        //                 $newMovie->actors()->attach($newActor->id, ['character' => $actor->character]);
        //             }
        //         }

        //         $directors = $cast->crew;
        //         if (isset($directors)) {
        //             foreach ($directors as $director) {
        //                 if ($director->known_for_department == 'Directing') {
        //                     $directorProfileUrl = 'https://image.tmdb.org/t/p/w185' . $director->profile_path;
        //                     $contents = file_get_contents($directorProfileUrl);
        //                     $directorProfileName = $director->id . 'profile.jpg';
        //                     Storage::disk('public')->put('directors/' . $directorProfileName, $contents);
        //                     $director_profile_path = 'directors/' . $directorProfileName;
        //                     $newDirector = Director::firstOrCreate(
        //                         ['tmdb_director_id' => $director->id],
        //                         ['name' => $director->name, 'photo_path' => $director_profile_path]
        //                     );
        //                     $newMovie->directors()->attach($newDirector->id);
        //                     break;
        //                 }
        //             }
        //         }

        //         $request->user()->movies()->attach($newMovie->id, ['watched' => false, 'liked' => false]);

        //         return back()->with('success', 'Film ajouté');
        //     } else {
        //         return back()->with('error', 'Film invalide');
        //     }
        // }
        // return redirect()->route('login')->with('error', 'Il faut être connecté pour ajouter un film');
    }
}
