<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    protected $fillable = [
        'id_title_tmdb', 
        'is_movie', 
        'name',
        'tagline',
        'poster_path',
        'overview',
        'release_date',
        'rating',
        'origin_country',
        'duration',
    ];

    public function genres(): BelongsToMany {
        return $this->belongsToMany(Genre::class);
    }
    
    public function actors(): BelongsToMany {
        return $this->belongsToMany(Actor::class)->withPivot('character');
    }

    public function directors(): BelongsToMany {
        return $this->belongsToMany(Director::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('watched', 'liked');
    }

    public function episodes(): HasMany {
        return $this->hasMany(Episode::class);
    }
}
