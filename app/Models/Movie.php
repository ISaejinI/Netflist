<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    protected $fillable = [
        'movie_id', 
        'title', 
        'poster_path'
    ];

    public function genres(): BelongsToMany {
        return $this->belongsToMany(Genre::class);
    }
}
