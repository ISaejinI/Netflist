<div class="rated-movie-card">
    <div class="rated-rank-badge">
        <span class="rated-rank-number">{{ $rateNumber }}</span>
    </div>

    <div class="rated-movie-content">
        <div class="rated-movie-poster">
            <img src="{{ 'https://media.themoviedb.org/t/p/w500' . $poster }}" alt="{{ $title }}"
                class="rated-poster-img">
            <div class="rated-movie-overlay">
                <div class="rated-movie-actions">
                    @php
                        if ($isMovie==true) {
                            $action = route('storemovie');
                            $name = 'movie_id';
                        } else {
                            $action = route('storeserie');
                            $name = 'serie_id';
                        }
                    @endphp
                    <form action="{{ $action }}" method="POST" class="rated-action-form">
                        @csrf
                        <input type="hidden" name="{{ $name }}" value="{{ $id }}">
                        <button type="submit" class="rated-action-btn rated-secondary" title="Ajouter à ma liste">
                            <i class='bx bx-plus'></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="rated-movie-info">
            <h2 class="rated-movie-title">{{ $title }}</h2>

            <div class="rated-movie-meta">
                <span class="rated-release-date">
                    <i class='bx bx-calendar'></i>
                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </span>
                <div class="rated-movie-rating">
                    <i class='bx bxs-star'></i>
                    <span>{{ $rating }}/10</span>
                </div>
            </div>

            <p class="rated-movie-overview">{{ $overview }}</p>
        </div>
    </div>
</div>
