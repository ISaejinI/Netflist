<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $fillable = [
        'id_actor_tmdb', 
        'name', 
        'actor_profile_path'
    ];

    public function titles()
    {
        return $this->belongsToMany(Title::class)->withPivot('character');
    }
}
