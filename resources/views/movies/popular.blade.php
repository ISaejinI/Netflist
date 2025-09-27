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

            <!-- Content Toggle Buttons -->
            <div class="content-toggle-container">
                <div class="toggle-buttons">
                    <button class="toggle-btn active" onclick="toggleContent('movies')">
                        <i class='bx bx-movie-play'></i>
                        Films
                    </button>
                    <button class="toggle-btn" onclick="toggleContent('series')">
                        <i class='bx bx-tv'></i>
                        Séries
                    </button>
                </div>
            </div>

            <!-- Movies Section -->
            <section class="popular-movies-section">
                <div class="content-section active" id="movies-section">
                    <div class="section-header">
                        <h2 class="section-title">Les <span class="highlight">films</span>
                            {{ $type == 'popular' ? 'populaires' : '' }}</h2>
                        <p class="section-subtitle">Découvrez les films
                            {{ $type == 'popular' ? 'les plus populaires' : 'correspondant à votre recherche' }}</p>
                    </div>
                    <div class="movies-grid">
                        @php
                            $displayMovies = $type != 'search' ? $movies->results : $movies;
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
                </div>

                <!-- Series Section -->
                <div class="content-section" id="series-section">
                    <div class="section-header">
                        <h2 class="section-title">Les <span class="highlight">séries</span>
                            {{ $type == 'popular' ? 'populaires' : '' }}</h2>
                        <p class="section-subtitle">Découvrez les séries
                            {{ $type == 'popular' ? 'les plus populaires' : 'correspondant à votre recherche' }}</p>
                    </div>
                    <div class="movies-grid">
                        @php
                            $displaySeries = $type != 'search' ? $series->results : $series;
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
                        <a href="{{ route('populartitles', ['page' => $movies->total_pages - 500]) }}"
                            class="popular-pagination-btn">
                            <i class='bx bx-chevrons-right'></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleContent(type) {
            // Désactiver tous les boutons
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Masquer toutes les sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            // Activer le bouton cliqué
            event.target.closest('.toggle-btn').classList.add('active');

            // Afficher la section correspondante
            document.getElementById(type + '-section').classList.add('active');
        }
    </script>
@endsection
