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
                        {{ $actor->name }} - Rôle : {{-- Récupérer le champ character stocké sur la table actor_movie --}}
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
