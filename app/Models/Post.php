<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tags;


class Post extends Model
{
    protected $table = 'posts';
    
    public function tags()
    {
        return $this->belongsToMany(Tags::class)->withTimestamps();
    }

    public function votes()
    {
        return $this->hasMany(Votes::class, 'id_post', 'id_r');
    }
}
