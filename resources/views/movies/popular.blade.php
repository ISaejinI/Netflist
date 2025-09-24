@extends('bases.base')
@section('content')
    <div class="popular-page">
        <div class="container">
            <!-- Hero Section -->
            <section class="popular-hero">
                <div class="popular-hero-content">
                    <h1 class="popular-hero-title">{!! $title !!}</h1>
                    <p class="popular-hero-subtitle">Découvrez les films les plus populaires du moment</p>
                </div>
            </section>

            <!-- Movies Grid Section -->
            <section class="popular-movies-section">
                <div class="popular-movies-grid">
                    @if ($type==='search')
                        @foreach ($movies->results as $movie)
                            @php
                                $title = $movie->media_type==='tv'?'name':'title';
                                $date = $movie->media_type==='tv'?'first_air_date':'release_date';
                                $isMovie = $movie->media_type==='tv'?false:true;
                            @endphp
                            <x-movie-card 
                                type="popular"
                                poster="{{ $movie->poster_path }}" 
                                title="{{ $movie->$title }}"
                                id="{{ $movie->id }}"
                                date="{{ $movie->$date }}"
                                rating="{{ $movie->vote_average }}"
                                overview="{{ $movie->overview }}"
                                genres=""
                                isMovie="{{ $isMovie }}"
                            />
                        @endforeach
                    
                    @else 
                        @php
                            $title = $type==='popularSerie'?'name':'title';
                            $date = $type==='popularSerie'?'first_air_date':'release_date';
                            $isMovie = $type==='popularSerie'?false:true;
                        @endphp

                        @foreach ($movies->results as $movie)
                            <x-movie-card 
                                type="popular"
                                poster="{{ $movie->poster_path }}" 
                                title="{{ $movie->$title }}"
                                id="{{ $movie->id }}"
                                date="{{ $movie->$date }}"
                                rating="{{ $movie->vote_average }}"
                                overview="{{ $movie->overview }}"
                                genres=""
                                isMovie="{{ $isMovie }}"
                            />
                        @endforeach
                    @endif
                </div>
            </section>
            
            {{-- Pagination --}}
            @if ($type === 'popularMovie' && $movies->total_pages > 1)
                <div class="popular-pagination">
                    <div class="popular-pagination-container">
                        <a href="{{ route('popularmovies', ['page' => 1]) }}" class="popular-pagination-btn">
                            <i class='bx bx-chevrons-left'></i>
                        </a>
                        @php
                            $currentPage = request()->route('page', 1);
                        @endphp
                        @if ($currentPage < 3)
                            @for ($i = 1; $i <= 10; $i++)
                                <a href="{{ route('popularmovies', ['page' => $i]) }}" 
                                   class="popular-pagination-btn {{ $i == $currentPage ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        @else
                            @for ($i = max(1, $currentPage - 5); $i <= min($movies->total_pages, $currentPage + 5); $i++)
                                <a href="{{ route('popularmovies', ['page' => $i]) }}" 
                                   class="popular-pagination-btn {{ $i == $currentPage ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        @endif
                        <a href="{{ route('popularmovies', ['page' => $movies->total_pages - 500]) }}" class="popular-pagination-btn">
                            <i class='bx bx-chevrons-right'></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection