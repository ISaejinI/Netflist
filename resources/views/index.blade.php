@extends('bases.base')
@section('content')
    <div class="index-page">
        <div class="container">
            <!-- Hero Section -->
            <section class="index-hero">
                <div class="index-hero-content">
                    <h1 class="index-hero-title">Tous les <span class="highlight">films</span> sauvegardés</h1>
                    <p class="index-hero-subtitle">Découvrez et gérez votre collection personnelle de films</p>
                </div>
            </section>

            <!-- Films Grid -->
            @if($savedMovies->count() > 0)
                <section class="index-movies-section">
                    <div class="index-movies-grid">
                        @foreach ($savedMovies as $movie)
                            <div class="index-movie-card">
                                <div class="index-movie-poster">
                                    <img src="{{ Storage::url($movie->poster_path) }}" alt="{{ $movie->title }}" class="index-poster-img">
                                    <div class="index-movie-overlay">
                                        <div class="index-movie-actions">
                                            <a href="{{ route('moviedetail', $movie->id) }}" class="index-action-btn index-primary">
                                                <i class='bx bx-play'></i>
                                            </a>
                                            <form action="{{ route('deletemovie') }}" method="post" class="index-action-form">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                                <button type="submit" class="index-action-btn index-danger" title="Supprimer de la liste">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="index-movie-info">
                                    <h3 class="index-movie-title">{{ $movie->title }}</h3>
                                    <div class="index-movie-genres">
                                        @foreach ($movie->genres as $genre)
                                            <span class="index-genre-tag">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>
                                    <div class="index-movie-rating">
                                        <i class='bx bx-star'></i>
                                        <span>{{ $movie->rating }}/10</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @else
                <div class="index-empty-state">
                    <div class="index-empty-icon">
                        <i class='bx bx-movie'></i>
                    </div>
                    <h3>Aucun film sauvegardé</h3>
                    <p>Commencez à ajouter des films à votre collection pour les retrouver ici</p>
                    <a href="{{ route('home') }}" class="index-btn-primary">
                        <i class='bx bx-plus'></i>
                        Retour à l'accueil
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
