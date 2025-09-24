<div class="movie-card">
    <div class="movie-poster">
        <img src="{{ Storage::url($poster) }}" alt="{{ $title }}" loading="lazy">
        <div class="movie-overlay">
            <div class="movie-actions">
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
            </div>
        </div>
    </div>
    <div class="movie-info">
        <h3 class="movie-title-home">{{ $title }}</h3>
        <div class="movie-genres">
            @foreach ($genres as $genre)
                <span class="genre-tag-home">{{ $genre->name }}</span>
            @endforeach
        </div>
    </div>
</div>
