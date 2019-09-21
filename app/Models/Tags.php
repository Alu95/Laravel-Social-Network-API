<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;


class Tags extends Model
{
    protected $table = 'tags';

    public function post()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}
