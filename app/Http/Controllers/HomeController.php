<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getHomeDatas()
    {
        $savedMovies = Movie::all()->load('genres')->where('watched', false);
        // dd($savedMovies);
        return view('home', ['savedMovies' => $savedMovies]);
    }
}
