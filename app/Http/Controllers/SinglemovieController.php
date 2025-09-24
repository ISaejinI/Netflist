<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;

class SinglemovieController extends Controller
{
    public function movieDetail($id)
    {
        $movie = Title::with('genres', 'actors', 'directors')->findOrFail($id);
        // dd($movie);
        return view('moviedetail', ['movie' => $movie]);
    }
}
