<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag_post_relationship extends Model
{
     protected $table = 'tag_post_relationship';

     public function posts()
     {
         return $this->hasMany(PostLists::class);
     }
    public function tags()
    {
        return $this->hasMany(Tags::class);
    }
}
