<div class="movie-card">
    <div class="movie-poster">
        <img src="{{ Storage::url($poster) }}" alt="{{ $title }}" loading="lazy"
            class="{{ $watched == 1 ? 'seen' : '' }}">
        @if ($watched == 1)
            <span class="seenBox"><i class='bxr bx-eye-alt'></i></span>
        @endif
        @php
            // dd($episodes);
        @endphp

        <div class="movie-overlay">
            <div class="movie-actions">
                @if ($type == 'home')
                    <a href="{{ route('moviedetail', $id) }}" class="action-btn primary" title="Voir le film">
                        <i class='bx bx-play'></i>
                    </a>
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

        {{-- Affichage du prochain épisode --}}
        <div>

            @if (isset($nextEpisode))
                @if ($isFirst!=1)
                    <form action="{{ route('watchepisode') }}" method="POST" class="action-form">
                        @csrf
                        <input type="hidden" name="episode_id" value="{{ $previousEpisode->id }}">
                        <button type="submit" class="action-btn secondary" title="Épisode précédent">
                            <i class='bx bx-minus'></i>
                        </button>
                    </form>
                @endif
                <div class="next-episode">
                    {{-- <strong>Prochain épisode :</strong> --}}
                    S{{ $nextEpisode->season }} Ep{{ $nextEpisode->episode_number }} :
                    {{ $nextEpisode->episode_name }}
                </div>
                <form action="{{ route('watchepisode') }}" method="POST" class="action-form">
                    @csrf
                    <input type="hidden" name="episode_id" value="{{ $nextEpisode->id }}">
                    <button type="submit" class="action-btn secondary" title="Épisode suivant">
                        <i class='bx bx-plus'></i>
                    </button>
                </form>
            @else
                <div class="next-episode">
                    <strong>Vous avez vu tous les épisodes !</strong>
                </div>
            @endif
        </div>
    </div>
</div>
