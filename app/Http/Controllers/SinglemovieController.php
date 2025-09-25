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
                // Récupérer l'id de l'épisode 
                // Vérifier si la ligne existe
                // Lier la ligne pivot à un user et à un épisode
                // Marquer watched comme true
            }
        }
        else {
            return back()->with('error', 'Connectez-vous pour marquer vos épisodes');
        }
    }
}
