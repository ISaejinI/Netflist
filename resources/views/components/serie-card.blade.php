<div class="serie-card-horizontal">
    <div class="serie-poster-horizontal">
        <img src="{{ Storage::url($poster) }}" alt="{{ $title }}" loading="lazy"
            class="{{ $watched == 1 ? 'seen' : '' }}">
        @if ($watched == 1)
            <span class="seenBox"><i class='bxr bx-eye-alt'></i></span>
        @endif

        <div class="serie-overlay-horizontal">
            <div class="serie-actions-horizontal">
                <a href="{{ route('moviedetail', $id) }}" class="action-btn primary" title="Voir la série">
                    <i class='bx bx-play'></i>
                </a>
                <form action="{{ route('deletetitle') }}" method="post" class="action-form">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="title_id" value="{{ $id }}">
                    <button type="submit" class="action-btn secondary" title="Supprimer de la liste">
                        <i class='bx bx-trash'></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="serie-info-horizontal">
        <div class="serie-header-horizontal">
            <h3 class="serie-title-horizontal">{{ $title }}</h3>

            @if ($genres != '')
                <div class="serie-genres-horizontal">
                    @foreach ($genres as $genre)
                        <span class="serie-genre-tag-horizontal">{{ $genre->name }}</span>
                    @endforeach
                </div>
            @endif

            <div class="serie-meta-horizontal">
                <span class="serie-release-date-horizontal">
                    <i class='bx bx-calendar'></i>
                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </span>
                <div class="serie-rating-horizontal">
                    <i class='bxr bxs-heart'></i>
                    @php
                        $note = $rating * 10;
                    @endphp
                    <span>{{ $note }}%</span>
                </div>
            </div>

            <p class="serie-overview-horizontal">{{ $overview }}</p>
        </div>

        {{-- Section des épisodes --}}
        <div class="serie-episode-section">
            @if (isset($nextEpisode))
                <div class="next-episode-horizontal">
                    <div class="episode-info-horizontal">
                        <div class="episode-title-horizontal">
                            S{{ $nextEpisode->season }} Ep{{ $nextEpisode->episode_number }}:
                            {{ $nextEpisode->episode_name }}
                        </div>
                    </div>
                    <div class="episode-actions-horizontal">
                        @if ($isFirst != 1)
                            <form action="{{ route('watchepisode') }}" method="POST" class="action-form">
                                @csrf
                                <input type="hidden" name="episode_id" value="{{ $previousEpisode->id }}">
                                <button type="submit" class="episode-action-btn-horizontal" title="Épisode précédent">
                                    <i class='bx bx-minus'></i>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('watchepisode') }}" method="POST" class="action-form">
                            @csrf
                            <input type="hidden" name="episode_id" value="{{ $nextEpisode->id }}">
                            <button type="submit" class="episode-action-btn-horizontal" title="Épisode suivant">
                                <i class='bx bx-plus'></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="completion-message">
                    <i class='bx bx-check-circle'></i>
                    <span>Série terminée</span>
                </div>
            @endif
        </div>
    </div>
</div>
