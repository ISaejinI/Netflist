@extends('bases.base')
@section('content')
    <div class="popular-page">
        <div class="container">
            <!-- Hero Section -->
            <section class="popular-hero">
                <div class="popular-hero-content">
                    <h1 class="popular-hero-title">{!! $title !!}</h1>
                    <p class="popular-hero-subtitle">Découvrez les films et les séries {{ $subtitle }}</p>
                </div>
            </section>

            <!-- Movies Grid Section -->
            <section class="popular-movies-section">
                <h2>Les <span class="highlight">films</span> {{$type=='popular'?'populaires':'' }}</h2>
                <div class="popular-movies-grid">
                    @php
                        $displayMovies = $type!='search'?$movies->results:$movies;
                    @endphp
                    @foreach ($displayMovies as $movie)
                        <x-movie-card 
                            type="popular"
                            poster="{{ $movie->poster_path }}" 
                            :title="$movie->title"
                            id="{{ $movie->id }}"
                            date="{{ $movie->release_date }}"
                            rating="{{ $movie->vote_average }}"
                            :overview="$movie->overview"
                            genres=""
                            :isMovie="true"
                        />
                    @endforeach
                </div>

                <h2>Les <span class="highlight">séries</span> {{$type=='popular'?'populaires':'' }}</h2>
                <div class="popular-movies-grid">
                    @php
                        $displaySeries = $type!='search'?$series->results:$series;
                    @endphp
                    @foreach ($displaySeries as $serie)
                        <x-movie-card 
                            type="popular"
                            poster="{{ $serie->poster_path }}" 
                            :title="$serie->name"
                            id="{{ $serie->id }}"
                            date="{{ $serie->first_air_date }}"
                            rating="{{ $serie->vote_average }}"
                            :overview="$serie->overview"
                            genres=""
                            :isMovie="false"
                        />
                    @endforeach
                </div>
            </section>
            
            {{-- Pagination --}}
            @if ($type === 'popular' && ($movies->total_pages > 1 || $series->total_pages > 1))
                <div class="popular-pagination">
                    <div class="popular-pagination-container">
                        <a href="{{ route('populartitles', ['page' => 1]) }}" class="popular-pagination-btn">
                            <i class='bx bx-chevrons-left'></i>
                        </a>
                        @if ($currentPage < 3)
                            @for ($i = 1; $i <= 10; $i++)
                                <a href="{{ route('populartitles', ['page' => $i]) }}" 
                                   class="popular-pagination-btn {{ $i == $currentPage ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        @else
                            @for ($i = max(1, $currentPage - 5); $i <= min($movies->total_pages, $currentPage + 5); $i++)
                                <a href="{{ route('populartitles', ['page' => $i]) }}" 
                                   class="popular-pagination-btn {{ $i == $currentPage ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        @endif
                        <a href="{{ route('populartitles', ['page' => $movies->total_pages - 500]) }}" class="popular-pagination-btn">
                            <i class='bx bx-chevrons-right'></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection