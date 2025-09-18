@extends('base')
@section('content')
    <div class="container">
        <h1>{!! $title !!}</h1>
    
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

        @if ($type === 'popular' && $movies->total_pages > 1)
            <div class="pagination">
                <a href="{{ route('popularmovies', ['page' => 1]) }}"><i class='bxr  bx-chevrons-left'></i></a>
                @php
                    $currentPage = request()->route('page', 1);
                @endphp
                @if ($currentPage < 3)
                    @for ($i = 1; $i <= 10; $i++)
                        <a href="{{ route('popularmovies', ['page' => $i]) }}">{{ $i }}</a>
                    @endfor
                @else
                    @for ($i = max(1, $currentPage - 5); $i <= min($movies->total_pages, $currentPage + 5); $i++)
                        <a href="{{ route('popularmovies', ['page' => $i]) }}">{{ $i }}</a>
                    @endfor
                @endif
                <a href="{{ route('popularmovies', ['page' => $movies->total_pages - 500]) }}"><i class='bxr  bx-chevrons-right'></i></a>
            </div>
        @endif
    </div>
@endsection