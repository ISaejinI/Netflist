<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class SinglemovieController extends Controller
{
    public function movieDetail($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);
        return view('moviedetail', ['movie' => $movie]);
    }
}
