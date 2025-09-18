@extends('bases.base')
@section('content')
    <div class="rated-page">
        <div class="container">
            <!-- Hero Section -->
            <section class="rated-hero">
                <div class="rated-hero-content">
                    <h1 class="rated-hero-title">Les mieux <span class="highlight">notés</span></h1>
                    <p class="rated-hero-subtitle">Découvrez les films les mieux évalués par la communauté</p>
                </div>
            </section>

            <!-- Movies Ranking Section -->
            <section class="rated-movies-section">
                <div class="rated-movies-container">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($movies->results as $movie)
                        @php
                            $i++;
                        @endphp
                        <div class="rated-movie-card">
                            <div class="rated-rank-badge">
                                <span class="rated-rank-number">{{ $i }}</span>
                            </div>
                            
                            <div class="rated-movie-content">
                                <div class="rated-movie-poster">
                                    <img src="{{ 'https://media.themoviedb.org/t/p/w500'.$movie->poster_path }}" alt="{{ $movie->title }}" class="rated-poster-img">
                                    <div class="rated-movie-overlay">
                                        <div class="rated-movie-actions">
                                            <a href="#" class="rated-action-btn rated-primary">
                                                <i class='bx bx-play'></i>
                                            </a>
                                            <form action="{{ route('storemovie') }}" method="POST" class="rated-action-form">
                                                @csrf
                                                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                                <button type="submit" class="rated-action-btn rated-secondary" title="Ajouter à ma liste">
                                                    <i class='bx bx-plus'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="rated-movie-info">
                                    <h2 class="rated-movie-title">{{ $movie->title }}</h2>
                                    
                                    <div class="rated-movie-meta">
                                        <span class="rated-release-date">
                                            <i class='bx bx-calendar'></i>
                                            {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                        </span>
                                        <div class="rated-movie-rating">
                                            <i class='bx bxs-star'></i>
                                            <span>{{ $movie->vote_average }}/10</span>
                                        </div>
                                    </div>
                                    
                                    <p class="rated-movie-overview">{{ $movie->overview }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection