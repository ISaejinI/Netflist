@extends('bases.base')
@section('content')
    <div class="movie-detail-page container">
        <div class="movie-detail-layout">
            <div class="movie-poster-section">
                <div class="poster-container">
                    <img src="{{ Storage::url($movie->poster_path) }}" alt="{{ $movie->title }}" class="movie-poster-img">
                    <div class="poster-overlay">
                        <div class="rating-badge">
                            <i class='bx bx-star'></i>
                            <span>{{ $movie->rating }}/10</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite - Informations du film -->
            <div class="movie-info-section">
                <!-- En-tête avec titre et métadonnées -->
                <div class="movie-header">
                    <div class="title-row">
                        <h1 class="movie-title">{{ $movie->name }}</h1>
                        <span class="release-year">{{ date('Y', strtotime($movie->release_date)) }}</span>
                    </div>

                    <div class="genre-tags">
                        @foreach ($movie->genres as $genre)
                            <span class="genre-tag">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    <div class="rating-section">
                        <div class="stars">
                            @for($i = 1; $i <= 1; $i++)
                                <i class='bx bx-star {{ $i <= ($movie->rating / 2) ? 'style="fill:var(--blanc)"' : '' }}'></i>
                            @endfor
                        </div>
                        <span class="rating-score">{{ $movie->rating }}/10</span>
                    </div>
                </div>

                <!-- Section Summary -->
                <div class="summary-section">
                    <h2 class="section-title">SUMMARY</h2>
                    <p class="movie-description">{{ $movie->overview }}</p>
                    <div class="additional-info">
                        <div class="info-item">
                            <strong>Pays d'origine :</strong> {{ $movie->origin_country }}
                        </div>
                        <div class="info-item">
                            <strong>Date de sortie :</strong> {{ date('d/m/Y', strtotime($movie->release_date)) }}
                        </div>
                    </div>
                </div>

                <!-- Section Cast -->
                @if($movie->actors->count() > 0)
                <div class="cast-section">
                    <h2 class="section-title">CAST</h2>
                    <div class="cast-grid">
                        @foreach ($movie->actors->take(4) as $actor)
                            <div class="cast-member">
                                <div class="cast-avatar">
                                    <img src="{{ Storage::url($actor->actor_profile_path) }}" alt="{{ $actor->name }}">
                                </div>
                                <span class="cast-name">{{ $actor->name }}</span>
                                @if($actor->pivot->character)
                                    <span class="cast-character">{{ $actor->pivot->character }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section Directors -->
                @if($movie->directors->count() > 0)
                <div class="directors-section">
                    <h2 class="section-title">DIRECTORS</h2>
                    <div class="directors-grid">
                        @foreach ($movie->directors as $director)
                            <div class="director-member">
                                <div class="director-avatar">
                                    <img src="{{ Storage::url($director->director_profile_path) }}" alt="{{ $director->name }}">
                                </div>
                                <span class="director-name">{{ $director->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Boutons d'action -->
                <div class="action-buttons">
                    <form action="{{ route('watched') }}" method="post" class="action-form">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <button type="submit" class="btn-watch-trailer">
                            <i class='bx bx-play'></i>
                            Marquer comme vu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
