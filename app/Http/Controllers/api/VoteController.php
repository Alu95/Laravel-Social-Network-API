<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Post_tags;
use App\Models\Votes;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function votesPerPost()
    {
        $voteInPost = [];
        $posts= Post::all();
        //$test1 = Post::all()->votes()->all();

        foreach ($posts as $vote) {
            array_push($voteInPost, array(
                'vote' => countVotes($vote->votes()
                        ->select('vote_type')
                        ->where('id_post', $vote->id_r)
                        ->get()),
                'id' => $vote->id_r
            ));
        }

        //$test5 = array_column($voteInPost, 'vote_type', 'id_post');
        ksort($voteInPost);

        return response()->json([
            //'test1' => $test5,
            'test5' =>  $voteInPost
        ]);
    }
    
    protected function countVotes(array $votes){
        $votes = [
            'like' => 0,
            'dislike' => 0
        ];

        foreach($votes as $vote){
            $vote->vote_type === 1 ? null : null;
        }

        return null;
    }
}
