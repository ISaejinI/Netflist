@extends('bases.base')
@section('content')
    <div class="container">
        <h1>{{ $movie->title }}</h1>
        <div class="movieDetailBox">
            
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
@endsection
