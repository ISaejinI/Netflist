<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinglemovieController extends Controller
{
    public function movieDetail($id)
    {
        $movie = Title::with('genres', 'actors', 'directors', 'episodes')->findOrFail($id);
        $user = Auth::user();
        $userEpisodes = $user->episodes->where('title_id', $id);

        return view('moviedetail', ['movie' => $movie, 'userEpisodes' => $userEpisodes]);
    }

    public function markEpisodeAsWatched(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('episode_id') && $request->input('episode_id') > 0) {
                $episode = Auth::user()->episodes()->where('episodes.id', $request->input('episode_id'))->first();

                $episode->pivot->watched = true;
                $episode->pivot->save();
                
                $serieEpisodes = Auth::user()->episodes->where('title_id', $episode->title_id);
                $is_finished = true;
                foreach ($serieEpisodes as $serieEpisode) {
                    if ($serieEpisode->pivot->watched == false) {
                        $is_finished = false;
                        break;
                    }
                }
                if ($is_finished == true) {
                    $serie = Auth::user()->titles->where('id', $episode->title_id)->first();
                    $serie->pivot->watched = true;
                    $serie->pivot->save();
                }
                return back()->with('success', 'Épisode marqué comme vu');
            }
        } else {
            return back()->with('error', 'Connectez-vous pour marquer vos épisodes');
        }
    }
}
