<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Votes extends Model
{
    protected $table = 'post_votes';
    
    public function post()
    {
        return $this->hasOne(Post::class);
    }
}
