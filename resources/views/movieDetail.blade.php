@extends('bases.base')
@section('content')
    <div class="container">
        <h1>{{ $movie->title }}</h1>
        <div class="movieDetailBox">
            <p>{{ $movie->description }}</p>
            <p>Date de sortie : {{ $movie->release_date }}</p>
            <p>Note : {{ $movie->rating }}/10</p>
            <p>Pays d'origine : {{ $movie->origin_country }}</p>
            
            <img src="{{ Storage::url($movie->poster_path) }}" alt="{{ $movie->title }}" style="width: 100px">
            @foreach ($movie->genres as $genre)
                <span>{{ $genre->name }}</span>
            @endforeach
            <h3>Acteurs :</h3>
            <ul>
                @foreach ($movie->actors as $actor)
                    <li>
                        <img src="{{ Storage::url($actor->avatar_path) }}" alt="{{ $actor->name }}" style="width: 50px">
                        {{ $actor->name }} - Rôle : {{ $actor->pivot->character }}
                    </li>
                @endforeach
            </ul>

            <h3>Réalisateurs :</h3>
            <ul>
                @foreach ($movie->directors as $director)
                    <li>
                        <img src="{{ Storage::url($director->photo_path) }}" alt="{{ $director->name }}" style="width: 50px">
                        {{ $director->name }}
                    </li>
                @endforeach
            </ul>

            <form action="{{ route('watched') }}" method="post">
                @csrf
                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                <input type="submit" value="Marquer le film comme vu">
            </form>
    </div>
@endsection
