<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SinglemovieController extends Controller
{
    public function movieDetail($id)
    {
        $movie = Title::with('genres', 'actors', 'directors', 'episodes')->findOrFail($id);
        $user = Auth::user();
        $userEpisodes = $user->episodes->where('title_id', $id);
        $seen = $user->titles->where('id', $id)->first()->pivot->watched;

        return view('moviedetail', ['movie' => $movie, 'userEpisodes' => $userEpisodes, 'seen' => $seen]);
    }

    public function markEpisodeAsWatched(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('episode_id') && $request->input('episode_id') > 0) {
                $episode = Auth::user()->episodes()->where('episodes.id', $request->input('episode_id'))->first();

                $episode->pivot->watched = !$episode->pivot->watched;
                $episode->pivot->save();

                $serieEpisodes = Auth::user()->episodes->where('title_id', $episode->title_id);
                $is_finished = true;
                foreach ($serieEpisodes as $serieEpisode) {
                    if ($serieEpisode->pivot->watched == false) {
                        $is_finished = false;
                        break;
                    }
                }
                $serie = Auth::user()->titles->where('id', $episode->title_id)->first();
                $serie->pivot->watched = $is_finished;
                $serie->pivot->save();
                
                $returnText = !$episode->pivot->watched==1?"non vu":"vu";
                return back()->with('success', 'Épisode marqué comme '.$returnText);
            }
        } else {
            return back()->with('error', 'Connectez-vous pour marquer vos épisodes');
        }
    }

    public function deleteTitle(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('title_id') && $request->input('title_id') > 0) {
                $user = Auth::user();
                $movie = $user->titles()->where('titles.id', $request->input('title_id'))->first();
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
                    return back()->with('success', 'Titre supprimé');
                } else {
                    return back()->with('error', 'Titre non trouvé');
                }
            } else {
                return back()->with('error', 'Titre non trouvé');
            }
        } else {
            return back()->with('error', 'Il faut être connecté pour supprimer un titre');
        }
    }
}
