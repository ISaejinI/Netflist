@extends('base')
@section('content')
    <div class="container" style="position: relative">
        <h1>Tous les films sauvegardés</h1>

        <div>
            @foreach ($savedMovies as $movie)
                <div class="homeMovieBox">
                    {{ $movie->title }}
                    <img src="{{ Storage::url($movie->poster_path) }}" alt="{{ $movie->title }}" style="width: 100px">
                    @foreach ($movie->genres as $genre)
                        <span>{{ $genre->name }}</span>
                    @endforeach

                    <form action="{{ route('deletemovie') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <input type="submit" value="Supprimer le film de la liste">
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
