<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    protected $fillable = [
        'movie_id', 
        'title', 
        'poster_path',
        'description',
        'release_date',
        'rating',
        'origin_country',
        'watched'
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
}
