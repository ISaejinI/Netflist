<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function title(): BelongsTo{
        return $this->belongsTo(Title::class);
    }
}
