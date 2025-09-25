<div class="movie-card">
    <div class="movie-poster">
        <img src="{{ Storage::url($poster) }}" alt="{{ $title }}" loading="lazy">
        
        @php
            // dd($episodes);
        @endphp

        <div class="movie-overlay">
            <div class="movie-actions">
                @if ($type == 'home')
                    <a href="{{ route('moviedetail', $id) }}" class="action-btn primary" title="Voir le film">
                        <i class='bx bx-play'></i>
                    </a>
                    <form action="{{ route('watched') }}" method="post" class="action-form">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $id }}">
                        <button type="submit" class="action-btn secondary" title="Marquer comme vu">
                            <i class='bx bx-check'></i>
                        </button>
                    </form>
                @elseif ($type == 'popular')
                    @if ($isMovie === true)
                        <form action="{{ route('storemovie') }}" method="POST" class="action-form">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $id }}">
                            <button type="submit" class="action-btn secondary" title="Ajouter à ma liste">
                                <i class='bx bx-plus'></i>
                            </button>
                        </form>
                    @elseif($isMovie === false)
                        <form action="{{ route('storeserie') }}" method="POST" class="action-form">
                            @csrf
                            <input type="hidden" name="serie_id" value="{{ $id }}">
                            <button type="submit" class="action-btn secondary" title="Ajouter à ma liste">
                                <i class='bx bx-plus'></i>
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="movie-info">
        <h3 class="movie-title-home">{{ $title }}</h3>
        @if ($genres != '')
            <div class="movie-genres">
                @foreach ($genres as $genre)
                    <span class="genre-tag-home">{{ $genre->name }}</span>
                @endforeach
            </div>
        @endif
        <div class="popular-movie-meta">
            <span class="popular-release-date">
                <i class='bx bx-calendar'></i>
                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </span>
            <div class="popular-movie-rating">
                <i class='bxr bxs-heart'></i>
                @php
                    $note = $rating * 10;
                @endphp
                <span>{{ $note }}%</span>
            </div>
        </div>
        <p class="popular-movie-overview">{{ $overview }}</p>
    </div>
</div>
