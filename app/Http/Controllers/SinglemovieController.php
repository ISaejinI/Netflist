<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinglemovieController extends Controller
{
    public function movieDetail($id)
    {
        $movie = Title::with('genres', 'actors', 'directors')->findOrFail($id);
        return view('moviedetail', ['movie' => $movie]);
    }

    public function markEpisodeAsWatched (Request $request)
    {
        if (Auth::check()) {
            if ($request->has('episode_id') && $request->input('episode_id') > 0) {
                $episode = Auth::user()->episodes()->where('episodes.id', $request->input('episode_id'))->first();
                if ($episode) {
                    $episode->pivot->watched = true;
                    $episode->pivot->save();
                    return back()->with('success', 'Épisode marqué comme vu');
                } else {
                    return back()->with('error', 'Épisode non trouvé');
                }
            }
        }
        else {
            return back()->with('error', 'Connectez-vous pour marquer vos épisodes');
        }
    }
}
