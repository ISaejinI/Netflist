<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $fillable = [
        'id_director_tmdb', 
        'name', 
        'director_profile_path'
    ];

    public function titles()
    {
        return $this->belongsToMany(Title::class);
    }
}
