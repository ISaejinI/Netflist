<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Director;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
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

    public function getPopularTitles(Request $request)
    {
        $currentPage = $request->route('page', 1);
        if (is_numeric($currentPage) && $currentPage > 0) {
            $popularMovies = $this->getCurlDatas("movie/popular?language=fr-FR&include_adult=false&page=" . $currentPage);
            $popularSeries = $this->getCurlDatas("tv/popular?language=fr-FR&include_adult=false&page=" . $currentPage);
            if ((!isset($popularMovies->results) || count($popularMovies->results) === 0) && (!isset($popularSeries->results) || count($popularSeries->results) === 0)) {
                return back()->with('error', 'Page invalide');
            }
            return view('movies.popular', ['movies' => $popularMovies, 'series' => $popularSeries, 'title' => 'Les films et séries <span class="highlight">populaires</span>', 'subtitle'=> 'les plus populaires du moment', 'type' => 'popular', 'currentPage' => $currentPage]);
        } else {
            return back()->with('error', 'Page invalide');
        }
    }

    public function searchTitle(Request $request)
    {
        if ($request->has('search') && $request->input('search') !== '') {
            $searchName = urlencode($request->input('search'));
            $searchResults = $this->getCurlDatas('search/multi?query=' . $searchName . '&include_adult=false&language=fr-FR&page=1');
            $movies = array_filter($searchResults->results, function ($item) {
                return isset($item->media_type) && ($item->media_type === 'movie');
            });
            
            $series = array_filter($searchResults->results, function ($item) {
                return isset($item->media_type) && ($item->media_type === 'tv');
            });

            return view('movies.popular', ['movies' => $movies, 'series' => $series, 'title' => 'Résultats de la recherche pour : <span class="highlight">' . $searchName . '</span>', 'subtitle'=> 'correspondants à la recherche', 'type' => 'search']);
        } else {
            return back()->with('error', 'Recherche invalide');
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
                        if ($actor->profile_path != "") {
                            $actorProfileUrl = 'https://image.tmdb.org/t/p/w185' . $actor->profile_path;
                            $contents = file_get_contents($actorProfileUrl);
                            $actorProfileName = $actor->id . 'profile.jpg';
                            Storage::disk('public')->put('actors/' . $actorProfileName, $contents);
                        } else {
                            $actorProfileName = 'placeholder.jpg';
                        }
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
                            $newDirector = Director::firstOrCreate(
                                ['id_director_tmdb' => $director->id],
                                [
                                    'name' => $director->name,
                                    'director_profile_path' => $director->profile_path
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
        if (Auth::user()) {
            if ($request->has('movie_id') && $request->input('movie_id') > 0) {
                $movie = $request->user()->titles()->where('titles.id', $request->input('movie_id'))->first();
                if ($movie) {
                    $movie->pivot->watched = !$movie->pivot->watched;
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
                // dd($movie);
                if ($movie) {
                    $user->titles()->detach($movie->id);

                    $usersCount = $movie->users()->count();
                    if ($usersCount === 0) {
                        if ($movie->poster_path && Storage::disk('public')->exists($movie->poster_path)) {
                            Storage::disk('public')->delete($movie->poster_path);
                        }

                        foreach ($movie->genres as $genre) {
                            $movie->genres()->detach($genre->id);
                            if ($genre->titles()->count() === 0) {
                                $genre->delete();
                            }
                        }

                        $movie->actors()->detach();
                        $movie->directors()->detach();

                        if ($movie->is_movie == false) {
                            $movie->episodes()->delete();
                        }

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

    public function getBestRated()
    {
        $bestRatedMovies = [];
        $bestRatedSeries = [];
        for ($i = 1; $i <= 5; $i++) {
            $bestRatedMoviesPage = $this->getCurlDatas('movie/top_rated?language=fr-FR&page=' . $i);
            $bestRatedSeriesPage = $this->getCurlDatas('tv/top_rated?language=fr-FR&page=' . $i);
            if (isset($bestRatedMoviesPage->results)) {
                $bestRatedMovies = array_merge($bestRatedMovies, $bestRatedMoviesPage->results);
            }
            if (isset($bestRatedSeriesPage->results)) {
                $bestRatedSeries = array_merge($bestRatedSeries, $bestRatedSeriesPage->results);
            }
        }
        $bestRatedMovies = (object) ['results' => $bestRatedMovies];
        $bestRatedSeries = (object) ['results' => $bestRatedSeries];
        return view('movies.bestRated', ['movies' => $bestRatedMovies, 'series' => $bestRatedSeries, 'title' => 'Les films les mieux notés', 'type' => 'bestRated']);
    }

    public function storeSerie(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('serie_id') && $request->input('serie_id') > 0) {
                $serie = $this->getCurlDatas('tv/' . $request->input('serie_id') . '?language=fr-FR');
                $cast = $this->getCurlDatas('tv/' . $request->input('serie_id') . '/credits?language=fr-FR');

                $posterUrl = 'https://image.tmdb.org/t/p/w500' . $serie->poster_path;
                $contents = file_get_contents($posterUrl);
                $name = $request->input('serie_id') . 'poster.jpg';
                Storage::disk('public')->put('posters/series/' . $name, $contents);
                $path = 'posters/series/' . $name;

                $newSerie = Title::firstOrCreate(
                    [
                        'id_title_tmdb' => $request->input('serie_id'),
                        'is_movie' => false,
                    ],
                    [
                        'name' => $serie->name,
                        'tagline' => $serie->tagline,
                        'poster_path' => $path,
                        'overview' => $serie->overview,
                        'release_date' => $serie->first_air_date,
                        'rating' => $serie->vote_average,
                        'origin_country' => isset($serie->origin_country[0]) ? $serie->origin_country[0] : null,
                    ]
                );

                if (isset($serie->genres)) {
                    foreach ($serie->genres as $genre) {
                        $newGenre = Genre::firstOrCreate(
                            ['id_genre_tmdb' => $genre->id],
                            ['name' => $genre->name]
                        );
                        $newSerie->genres()->attach($newGenre->id);
                    }
                }

                $actors = $cast->cast;
                if (isset($actors)) {
                    $actorSliced = array_slice($actors, 0, 5);
                    foreach ($actorSliced as $actor) {
                        if ($actor->profile_path != "") {
                            $actorProfileUrl = 'https://image.tmdb.org/t/p/w185' . $actor->profile_path;
                            $contents = file_get_contents($actorProfileUrl);
                            $actorProfileName = $actor->id . 'profile.jpg';
                            Storage::disk('public')->put('actors/' . $actorProfileName, $contents);
                        } else {
                            $actorProfileName = 'placeholder.jpg';
                        }
                        $actor_profile_path = 'actors/' . $actorProfileName;
                        $newActor = Actor::firstOrCreate(
                            ['id_actor_tmdb' => $actor->id],
                            [
                                'name' => $actor->name,
                                'actor_profile_path' => $actor_profile_path
                            ]
                        );
                        $newSerie->actors()->attach($newActor->id, ['character' => $actor->character]);
                    }
                }

                $directors = $cast->crew;
                if (isset($directors)) {
                    foreach ($directors as $director) {
                        if ($director->known_for_department == 'Directing') {
                            $newDirector = Director::firstOrCreate(
                                ['id_director_tmdb' => $director->id],
                                [
                                    'name' => $director->name,
                                    'director_profile_path' => $director->profile_path
                                ]
                            );
                            $newSerie->directors()->attach($newDirector->id);
                            break;
                        }
                    }
                }

                Auth::user()->titles()->attach($newSerie->id, ['watched' => false, 'liked' => false]);

                $seasons = $serie->seasons;
                foreach ($seasons as $season) {
                    $seasonNumber = $season->season_number;
                    $seasonInformations = $this->getCurlDatas('tv/' . $request->input('serie_id') . '/season/' . $seasonNumber . '?language=fr-FR');
                    $seasonEpisodes = $seasonInformations->episodes;
                    // dd($seasonEpisodes);
                    foreach ($seasonEpisodes as $episode) {
                        $newEpisode = Episode::firstOrCreate(
                            ['id_episode_tmdb' => $episode->id],
                            [
                                'title_id' => $newSerie->id,
                                'season' => $seasonNumber,
                                'episode_number' => $episode->episode_number,
                                'episode_name' => $episode->name,
                                'episode_overview' => $episode->overview,
                                'episode_duration' => $episode->runtime,
                            ]
                        );
                        Auth::user()->episodes()->attach($newEpisode->id, ['watched' => false]);
                    }
                }

                return back()->with('success', 'Série ajoutée');
            } else {
                return back()->with('error', 'Série invalide');
            }
        }
        return redirect()->route('login')->with('error', 'Il faut être connecté pour ajouter un film');
    }
}
