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
                    @foreach ($movies->results as $movie)
                        <div class="popular-movie-card">
                            <div class="popular-movie-poster">
                                <img src="{{ 'https://media.themoviedb.org/t/p/w500'.$movie->poster_path }}" alt="{{ $movie->title }}" class="popular-poster-img">
                                <div class="popular-movie-overlay">
                                    <div class="popular-movie-actions">
                                        <a href="#" class="popular-action-btn popular-primary">
                                            <i class='bx bx-play'></i>
                                        </a>
                                        <form action="{{ route('storemovie') }}" method="POST" class="popular-action-form">
                                            @csrf
                                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                            <button type="submit" class="popular-action-btn popular-secondary" title="Ajouter à ma liste">
                                                <i class='bx bx-plus'></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="popular-movie-info">
                                <h2 class="popular-movie-title">{{ $movie->title }}</h2>
                                
                                <div class="popular-movie-meta">
                                    <span class="popular-release-date">
                                        <i class='bx bx-calendar'></i>
                                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                    </span>
                                    <div class="popular-movie-rating">
                                        <i class='bx bxs-star'></i>
                                        <span>{{ $movie->vote_average }}/10</span>
                                    </div>
                                </div>
                                
                                <p class="popular-movie-overview">{{ $movie->overview }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            
            {{-- Pagination --}}
            @if ($type === 'popular' && $movies->total_pages > 1)
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