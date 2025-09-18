@extends('bases.base')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Découvrez votre prochaine <span class="highlight">obsession</span></h1>
            <p class="hero-subtitle">Parcourez une collection infinie de films et séries, personnalisée selon vos goûts</p>
            <div class="hero-buttons">
                <a href="#watchlist" class="btn-primary">
                    <i class='bx bx-play'></i>
                    Explorer maintenant
                </a>
                <a href="#watchlist" class="btn-secondary">
                    <i class='bx bx-plus'></i>
                    Ma liste
                </a>
            </div>
        </div>
    </section>

    <!-- Watchlist Section -->
    <section id="watchlist" class="watchlist-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title-home">Votre <span class="highlight">Watchlist</span></h2>
                @isset($selectedGenre) 
                    <div class="filter-indicator">
                        <i class='bx bx-filter'></i>
                        <span>Filtrée par : {{ $selectedGenre }}</span>
                    </div>
                @endisset
            </div>

            <!-- Filter Form -->
            <div class="filter-container">
                <form action="{{ route('home') }}" method="get" class="filter-form">
                    @csrf
                    <div class="filter-group">
                        <label for="genre" class="filter-label">
                            <i class='bx bx-category'></i>
                            Filtrer par genre
                        </label>
                        <select name="genre" id="genre" class="filter-select">
                            <option value="">Tous les genres</option>
                            @foreach ($allGenres as $genre)
                                <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }} ({{ $genre->movies_count }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="filter-btn">
                            <i class='bx bx-search'></i>
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Movies Grid -->
            @if($savedMovies->count() > 0)
                <div class="movies-grid">
                    @foreach ($savedMovies as $movie)
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img src="{{ Storage::url($movie->poster_path) }}" alt="{{ $movie->title }}" loading="lazy">
                                <div class="movie-overlay">
                                    <div class="movie-actions">
                                        <a href="{{ route('moviedetail', $movie->id) }}" class="action-btn primary" title="Voir le film">
                                            <i class='bx bx-play'></i>
                                        </a>
                                        <form action="{{ route('watched') }}" method="post" class="action-form">
                                            @csrf
                                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                            <button type="submit" class="action-btn secondary" title="Marquer comme vu">
                                                <i class='bx bx-check'></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="movie-info">
                                <h3 class="movie-title-home">{{ $movie->title }}</h3>
                                <div class="movie-genres">
                                    @foreach ($movie->genres as $genre)
                                        <span class="genre-tag-home">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class='bx bx-movie'></i>
                    </div>
                    <h3>Votre watchlist est vide</h3>
                    <p>Commencez à ajouter des films à votre liste pour les retrouver ici</p>
                    <a href="{{ route('popular') }}" class="btn-primary">
                        <i class='bx bx-plus'></i>
                        Découvrir des films
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
