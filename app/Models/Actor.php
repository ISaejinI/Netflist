<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $fillable = ['tmdb_id', 'name', 'avatar_path'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
