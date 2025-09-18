@extends('bases.base')
@section('content')
    <div class="container" style="position: relative">
        <h1>Titre de la page</h1>
        <div class="watchlistHeader">
            <h2>Votre <span class="highlight">Watchlist</span></h2>
            @isset($selectedGenre) 
                <span>Filtrée par genre : {{ $selectedGenre }}</span> 
            @endisset
        </div>
        <form action="{{ route('home') }}" method="get">
            @csrf
            <label for="genre">Filtrer par genre :</label>
            <select name="genre" id="genre">
                @foreach ($allGenres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }} ({{ $genre->movies_count }})</option>
                @endforeach
            </select>
            <button type="submit"><i class='bxr  bx-filter'></i></button>
        </form>
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

                    <a href="{{ route('moviedetail', $movie->id) }}">Voir les détails du film</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
