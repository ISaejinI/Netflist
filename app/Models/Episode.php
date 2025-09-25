<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Episode extends Model
{
    protected $fillable = [
        'id_episode_tmdb',
        'title_id', 
        'season', 
        'episode_number',
        'episode_name',
        'episode_overview',
        'episode_duration',
    ];

    public function title(): BelongsTo {
        return $this->belongsTo(Title::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('watched');
    }
}
