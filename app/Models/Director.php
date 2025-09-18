<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $fillable = ['tmdb_director_id', 'name', 'photo_path'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
