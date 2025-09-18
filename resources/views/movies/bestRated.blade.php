@extends('bases.base')
@section('content')
    <div class="container">
        <h1>Les mieux <span class="highlight">notés</span></h1>
    
        <div class="moviesContainer">
            @php
                $i = 0;
            @endphp
            @foreach ($movies->results as $movie)
                @php
                    $i++;
                @endphp
                <div class="rankCard">
                    <span class="movieRank">{{ $i }}</span>
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
                </div>
            @endforeach
        </div>
    </div>
@endsection