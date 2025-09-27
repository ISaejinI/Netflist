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

            <!-- Movies Ranking Section -->
            <section class="rated-movies-section">
                <h2>Les <span class="highlight">films</span> les mieux notés</h2>
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
                <h2>Les <span class="highlight">séries</span> les mieux notés</h2>
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
            </section>
        </div>
    </div>
@endsection