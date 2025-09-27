@extends('bases.base')
@section('content')
    <div class="rated-page">
        <div class="container">
            <!-- Hero Section -->
            <section class="rated-hero">
                <div class="rated-hero-content">
                    <h1 class="rated-hero-title">Les mieux <span class="highlight">notés</span></h1>
                    <p class="rated-hero-subtitle">Découvrez les films et les séries les mieux évalués par la communauté</p>
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

            <!-- Movies Ranking Section -->
            <section class="rated-movies-section">
                <!-- Movies Section -->
                <div class="content-section active" id="movies-section">
                    <div class="section-header">
                        <h2 class="section-title">Les <span class="highlight">films</span> les mieux notés</h2>
                        <p class="section-subtitle">Les films avec les meilleures évaluations par la communauté</p>
                    </div>
                    <div class="rated-movies-container">
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($movies->results as $movie)
                            @php
                                $i++;
                            @endphp
                            <x-rated-card 
                                :rateNumber="$i"
                                poster="{{ $movie->poster_path }}" 
                                :title="$movie->title"
                                id="{{ $movie->id }}"
                                date="{{ $movie->release_date }}"
                                rating="{{ $movie->vote_average }}"
                                :overview="$movie->overview"
                                :isMovie="true"
                            />
                        @endforeach
                    </div>
                </div>

                <!-- Series Section -->
                <div class="content-section" id="series-section">
                    <div class="section-header">
                        <h2 class="section-title">Les <span class="highlight">séries</span> les mieux notées</h2>
                        <p class="section-subtitle">Les séries avec les meilleures évaluations par la communauté</p>
                    </div>
                    <div class="rated-movies-container">
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($series->results as $serie)
                            @php
                                $i++;
                            @endphp
                            <x-rated-card 
                                :rateNumber="$i"
                                poster="{{ $serie->poster_path }}" 
                                :title="$serie->name"
                                id="{{ $serie->id }}"
                                date="{{ $serie->first_air_date }}"
                                rating="{{ $serie->vote_average }}"
                                :overview="$serie->overview"
                                :isMovie="false"
                            />
                        @endforeach
                    </div>
                </div>
            </section>
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