@extends('bases.base')
@section('content')
    <div class="movie-detail-page container">
        <div class="movie-detail-layout">
            <div class="movie-poster-section">
                <div class="poster-container">
                    <img src="{{ Storage::url($movie->poster_path) }}" alt="{{ $movie->title }}" class="movie-poster-img">
                    <div class="poster-overlay">
                        <div class="rating-badge">
                            <i class='bxr  bxs-heart'></i> 
                            @php
                                $note = $movie->rating * 10;
                            @endphp
                            <span>{{ $note }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="movie-info-section">
                <div class="movie-header">
                    <div class="title-row">
                        <h1 class="movie-title">{{ $movie->name }}</h1>
                        <p>{{ $movie->tagline }}</p>
                        <span class="release-year">{{ date('Y', strtotime($movie->release_date)) }}</span>
                    </div>

                    <div class="genre-tags">
                        @foreach ($movie->genres as $genre)
                            <span class="genre-tag">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    <div class="rating-section">
                        <div class="stars">
                            @php
                                $rating = intval($movie->rating)/2;
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class='bx {{ $i <= $rating ? 'bxs-star' : 'bx-star' }}'></i>
                            @endfor
                        </div>
                        <span class="rating-score">{{ $movie->rating }}/10</span>
                    </div>
                </div>

                <!-- Section Summary -->
                <div class="summary-section">
                    <h2 class="section-title">Synopsis</h2>
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
                    <h2 class="section-title">Acteurs</h2>
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
                    <h2 class="section-title">Réalisateur</h2>
                    <div class="directors-grid">
                        @foreach ($movie->directors as $director)
                            <div class="director-member">
                                <div class="director-avatar">
                                    <img src="https://image.tmdb.org/t/p/w185{{ $director->director_profile_path }}" alt="{{ $director->name }}">
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

        <!-- Affichage des épisodes par saison -->
        @if ($movie->is_movie == false && $movie->episodes->count() > 0)
            <div class="episodes-section">
                <h2 class="section-title">Épisodes</h2>
                <div class="seasons-container">
                    @php
                        $seasons = $movie->episodes->groupBy('season')->sortKeys();
                    @endphp
                    
                    @foreach ($seasons as $seasonNumber => $seasonEpisodes)
                        <div class="season-dropdown" data-season="{{ $seasonNumber }}">
                            <div class="season-header" onclick="toggleSeason({{ $seasonNumber }})">
                                <div class="season-title">
                                    <div class="season-number">{{ $seasonNumber }}</div>
                                    <div class="season-info">
                                        <h3 class="season-name">Saison {{ $seasonNumber }}</h3>
                                        <span class="season-episodes-count">{{ $seasonEpisodes->count() }} épisode{{ $seasonEpisodes->count() > 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                                <div class="season-toggle">
                                    <i class='bx bx-chevron-down'></i>
                                </div>
                            </div>
                            
                            <div class="episodes-list">
                                @foreach ($seasonEpisodes->sortBy('episode_number') as $episode)
                                    <div class="episode-item">
                                        <div class="episode-number">{{ $episode->episode_number }}</div>
                                        <div class="episode-content">
                                            <h4 class="episode-title">{{ $episode->episode_name }}</h4>
                                            @if($episode->episode_overview)
                                                <p class="episode-overview">{{ $episode->episode_overview }}</p>
                                            @endif
                                            <div class="episode-meta">
                                                @if($episode->episode_duration)
                                                    <div class="episode-duration">
                                                        <i class='bx bx-time'></i>
                                                        <span>{{ $episode->episode_duration }} min</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="episode-actions">
                                            <button class="episode-action-btn" title="Marquer comme vu">
                                                <i class='bx bx-check'></i>
                                            </button>
                                            <button class="episode-action-btn" title="Ajouter aux favoris">
                                                <i class='bx bx-heart'></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleSeason(seasonNumber) {
            const dropdown = document.querySelector(`[data-season="${seasonNumber}"]`);
            dropdown.classList.toggle('active');
        }

        // Optionnel : Ouvrir la première saison par défaut
        document.addEventListener('DOMContentLoaded', function() {
            const firstSeason = document.querySelector('.season-dropdown');
            if (firstSeason) {
                firstSeason.classList.add('active');
            }
        });
    </script>
@endsection
