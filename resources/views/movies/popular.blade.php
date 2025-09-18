@extends('base')
@section('content')
    <div class="container" style="position: relative">
        <h1>Les films du moment</h1>

        @session('success')
            <span class="alert"><i class='bxr  bx-check-circle'></i>{{ session('success') }}</span>
        @endsession
        
        <div class="moviesContainer">
            @foreach ($movies->results as $movie)
            <div class="cardContainer">
                <a href="#">
                    <img src="{{ 'https://media.themoviedb.org/t/p/w500'.$movie->poster_path }}" alt="{{ $movie->title }}">
                    <div class="movieInfo">
                        <h2 class="poster__title">{{ $movie->title }}</h2>
                        <div>
                            <span class="poster__date">{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</span>
                            <span class="poster__rating"><i class='bx  bxs-star' style='color:#d8e344'></i>  {{ $movie->vote_average }}/10</span>
                        </div>
                        <p class="poster__text">{{ $movie->overview }}</p>
                    </div>
                </a>
                <form action="{{ route('storemovie') }}" method="POST" id="saveMovie">
                    @csrf
                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                    <input type="submit" value="+">
                </form>
            </div>
            @endforeach
        </div>
    </div>
@endsection