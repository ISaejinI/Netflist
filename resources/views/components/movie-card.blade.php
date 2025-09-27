<div class="movie-card">
    <div class="movie-poster">
        @if ($type == 'home')
            <img src="{{ Storage::url($poster) }}" alt="{{ $title }}" loading="lazy" class="{{ $watched==1?'seen':'' }}">
        @elseif ($type == 'popular')
            <img src="{{ 'https://media.themoviedb.org/t/p/w500' . $poster }}" alt="{{ $title }}">
        @endif
        @if ($watched == 1)
            <span class="seenBox"><i class='bxr bx-eye-alt'></i></span>
        @endif
        <div class="movie-overlay">
            <div class="movie-actions">
                @if ($type == 'home')
                    <a href="{{ route('moviedetail', $id) }}" class="action-btn primary" title="Voir le film">
                        <i class='bx bx-play'></i>
                    </a>
                    <form action="{{ route('watched') }}" method="post" class="action-form">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $id }}">
                        <button type="submit" class="action-btn secondary" title="{{ $watched==0?'Marquer comme vu':'Marquer comme non vu' }}" >
                            <i class='{{ $watched==0?"bx bx-check":"bxr bx-eye-slash" }}'></i>
                        </button>
                    </form>
                    <form action="{{ route('deletetitle') }}" method="post" class="action-form">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="title_id" value="{{ $id }}">
                        <button type="submit" class="action-btn secondary" title="Supprimer de la liste">
                            <i class='bx bx-trash'></i>
                        </button>
                    </form>
                @elseif ($type == 'popular')
                    @php
                        if ($isMovie===true) {
                            $action = route('storemovie');
                        } else {
                            $action = route('storeserie');
                        }
                    @endphp
                    <form action="{{ $action }}" method="POST" class="action-form">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $id }}">
                        <button type="submit" class="action-btn secondary" title="Ajouter à ma liste">
                            <i class='bx bx-plus'></i>
                        </button>
                    </form>
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
