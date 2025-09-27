@extends('bases.base')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Découvrez votre prochaine <span class="highlight">obsession</span></h1>
            <p class="hero-subtitle">Parcourez une collection infinie de films et séries, personnalisée selon vos goûts</p>
            <div class="hero-buttons">
                <a href="{{ route('populartitles') }}" class="btn-primary">
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

            @guest
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class='bx bx-movie'></i>
                    </div>
                    <h3>Votre watchlist est vide</h3>
                    <p>Connectez-vous pour commencez à ajouter des films à votre liste pour les retrouver ici</p>
                    <a href="{{ route('login') }}" class="btn-primary"> <i class='bx bx-plus'></i> Se connecter </a>
                </div>
            @endguest

            @auth
                @if (!$movies->count() > 0 && !$series->count() > 0)
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class='bx bx-movie'></i>
                        </div>
                        <h3>Votre watchlist est vide</h3>
                        <p>Commencez à ajouter des films à votre liste pour les retrouver ici</p>
                        <a href="{{ route('populartitles') }}" class="btn-primary"> <i class='bx bx-plus'></i> Découvrir des films
                        </a>
                    </div>
                @else
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
                                    @isset($allGenres)
                                        @foreach ($allGenres as $genre)
                                            <option value="{{ $genre->id }}"
                                                {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                                {{ $genre->name }} ({{ $genre->movies_count }})
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                <button type="submit" class="filter-btn"> <i class='bx bx-search'></i> Filtrer </button>
                            </div>
                        </form>
                    </div>

                    <!-- Movies Grid -->
                    <div>
                        <h3>Vos <span class="highlight">Films</span></h3>
                        @if ($movies->count() > 0)
                            <div class="movies-grid">
                                @foreach ($movies as $movie)
                                    <x-movie-card 
                                        type="home"
                                        poster="{{ $movie->poster_path }}" 
                                        :title="$movie->name"
                                        id="{{ $movie->id }}" 
                                        :genres="$movie->genres"
                                        date="{{ $movie->release_date }}"
                                        rating="{{ $movie->rating }}"
                                        :overview="$movie->overview"
                                        watched="{{ $movie->pivot->watched }}"
                                    />
                                @endforeach
                            </div>
                        @else
                            <p>Il n'y a pas de films dans votre watchlist</p>
                        @endif
                    </div>
                    <div>
                        <h3>Vos <span class="highlight">Séries</span></h3>
                        @if ($series->count() > 0)
                            <div class="series-grid">
                                @foreach ($series as $serie)
                                    @php
                                        $nextEpisode = Auth::user()->episodes
                                            ->where('title_id', $serie->id)
                                            ->where('pivot.watched', false)
                                            ->sortBy(['season', 'episode_number'])
                                            ->first();
                                        
                                        // Récupère le dernier épisode vu (le plus avancé dans la série) donc il peut se trouver après le nextEpisode
                                        $previousEpisode = Auth::user()->episodes
                                            ->where('title_id', $serie->id)
                                            ->where('pivot.watched', true)
                                            ->sortByDesc(['season', 'episode_number'])
                                            ->first();

                                        $firstEpisode = Auth::user()->episodes
                                            ->where('title_id', $serie->id)
                                            ->sortBy(['season', 'episode_number'])
                                            ->first();
                                        
                                        $isFirst = $firstEpisode==$nextEpisode?true:false;
                                    @endphp
                                    <x-serie-card 
                                        type="home"
                                        poster="{{ $serie->poster_path }}" 
                                        :title="$serie->name"
                                        id="{{ $serie->id }}" 
                                        :genres="$serie->genres"
                                        date="{{ $serie->release_date }}"
                                        rating="{{ $serie->rating }}"
                                        :overview="$serie->overview"
                                        :nextEpisode="$nextEpisode"
                                        :previousEpisode="$previousEpisode"
                                        :isFirst="$isFirst"
                                        watched="{{ $serie->pivot->watched }}"
                                    />
                                @endforeach
                            </div>
                        @else
                            <p>Il n'y a pas de séries dans votre watchlist</p>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </section>
@endsection
