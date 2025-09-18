<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function getPopular(Request $request)
    {
        $currentPage = $request->route('page', 1);
        if (is_numeric($currentPage) && $currentPage > 0) {
            $popularMovies = $this->getCurlDatas("movie/popular?language=fr-FR&include_adult=false&page=".$currentPage);
            if (!isset($popularMovies->results) || count($popularMovies->results) === 0) {
                return redirect()->route('popularmovies', ['page' => 1])->with('error', 'Page invalide');
            }
            return view('movies.popular', ['movies' => $popularMovies, 'title' => 'Les films populaires <span class="highlight">populaires</span>', 'type' => 'popular']);
        }
        else {
            return redirect()->route('popularmovies', ['page' => 1])->with('error', 'Page invalide');
        }
    }

    public function getCurlDatas($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/".$url,
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

    public function storeMovie(Request $request) {
        if ($request->has('movie_id') && $request->input('movie_id') > 0) {
            $movie = $this->getCurlDatas('movie/'.$request->input('movie_id').'?language=fr-FR');
            $cast = $this->getCurlDatas('movie/'.$request->input('movie_id').'/credits?language=fr-FR');
            // dd($cast);
            $posterUrl = 'https://image.tmdb.org/t/p/w500'.$movie->poster_path;
            $contents = file_get_contents($posterUrl);
            $name = $request->input('movie_id').'poster.jpg';
            Storage::disk('public')->put('posters/'.$name, $contents);
            $path = 'posters/'.$name;

            $newMovie = Movie::firstOrCreate(
                ['movie_id' => $request->input('movie_id')], 
                [
                    'title' => $movie->title, 
                    'poster_path' => $path,
                    'description' => $movie->overview,
                    'release_date' => $movie->release_date,
                    'rating' => $movie->vote_average,
                    'origin_country' => isset($movie->origin_country[0]) ? $movie->origin_country[0] : null
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
                foreach (array_slice($actors, 0, 8) as $actor) {
                    $actorProfileUrl = 'https://image.tmdb.org/t/p/w500'.$actor->profile_path;
                    $contents = file_get_contents($actorProfileUrl);
                    $actorProfileName = $actor->id.'profile.jpg';
                    Storage::disk('public')->put('actors/'.$actorProfileName, $contents);
                    $actor_profile_path = 'actors/'.$actorProfileName;
                    $newActor = Actor::firstOrCreate(
                        ['tmdb_actor_id' => $actor->id],
                        ['name' => $actor->name, 'avatar_path' => $actor_profile_path]
                    );
                    $newMovie->actors()->attach($newActor->id, ['character' => $actor->character]);
                }
            }

        }
        return back()->with('success', 'Film ajouté');
    }

    public function markAsWatched(Request $request) {
        if ($request->has('movie_id') && $request->input('movie_id') > 0) {
            $movie = Movie::find($request->input('movie_id'));
            if ($movie) {
                $movie->watched = true;
                $movie->save();
                return redirect()->route('home')->with('success', 'Film marqué comme vu');
            } else {
                return redirect()->route('home')->with('error', 'Film non trouvé');
            }
        }
    }

    public function deleteMovie (Request $request) {
        if ($request->has('movie_id') && $request->input('movie_id') > 0) {
            $movie = Movie::find($request->input('movie_id'));
            if ($movie) {
                // Supprimer l'affiche du stockage
                if ($movie->poster_path && Storage::disk('public')->exists($movie->poster_path)) {
                    Storage::disk('public')->delete($movie->poster_path);
                }
                $movie->delete();
                return redirect()->route('savedmovies')->with('success', 'Film supprimé');
            } else {
                return redirect()->route('savedmovies')->with('error', 'Film non trouvé');
            }
        }
    }

    public function index() {
        $savedMovies = Movie::all()->load('genres');
        return view('index', ['savedMovies' => $savedMovies]);
    }

    public function searchMovie(Request $request) {
        if ($request->has('search') && $request->input('search') !== '') {
            $searchName = $request->input('search');
            $searchResults = $this->getCurlDatas('search/movie?query='.$searchName.'&include_adult=false&language=fr-FR&page=1');
            // dd($searchResults);
            return view('movies.popular', ['movies' => $searchResults, 'title' => 'Résultats de la recherche pour : <span class="highlight">'.$searchName.'</span>', 'type' => 'search']);
        }
        else {
            return redirect()->route('home')->with('error', 'Recherche invalide');
        }
    }

    public function getBestRated() {
        $bestRatedMovies = [];
        for ($i = 1; $i <= 5; $i++) {
            $bestRatedMoviesPage = $this->getCurlDatas('movie/top_rated?language=fr-FR&page='.$i);
            if (isset($bestRatedMoviesPage->results)) {
                $bestRatedMovies = array_merge($bestRatedMovies, $bestRatedMoviesPage->results);
            }
        }
        $bestRatedMovies = (object) ['results' => $bestRatedMovies];
        return view('movies.bestRated', ['movies' => $bestRatedMovies, 'title' => 'Les films les mieux notés', 'type' => 'bestRated']);
    }
}
