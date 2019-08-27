<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'tags';

    public function tag_post_relationship()
    {
        return $this->belongsTo(Tag_post_relationship::class);
    }
}
