<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Tags;
use App\Models\Post;

class Post_tags extends Model
{
    //
    protected $table = 'post_tags';

    public function tags() {
        return $this->hasMany(Tags::class, 'id','tags_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_r', 'posts_id');
    }
}
