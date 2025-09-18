@extends('bases.base')
@section('content')
    <div class="container" style="position: relative">
        <h1>Titre de la page</h1>

        @session('success')
            <span class="alert"><i class='bxr  bx-check-circle'></i>{{ session('success') }}</span>
        @endsession
        @session('error')
            <span class="alert"><i class='bxr  bx-check-circle'></i>{{ session('error') }}</span>
        @endsession

        <h2>Votre <span class="highlight">Watchlist</span></h2>
        <div>
            @foreach ($savedMovies as $movie)
                <div class="homeMovieBox">
                    {{ $movie->title }}
                    <img src="{{ Storage::url($movie->poster_path) }}" alt="{{ $movie->title }}" style="width: 100px">
                    @foreach ($movie->genres as $genre)
                        <span>{{ $genre->name }}</span>
                    @endforeach

                    <form action="{{ route('watched') }}" method="post">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <input type="submit" value="Marquer le film comme vu">
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
