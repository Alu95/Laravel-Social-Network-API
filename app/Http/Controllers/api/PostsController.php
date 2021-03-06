<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\Votes;
use App\Models\Tags;
use Tymon\JWTAuth\JWTAuth as TymonJWTAuth;
use Illuminate\Support\Facades\App;
use League\CommonMark;
use App\Http\Controllers\api\HashtagController;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    protected $auth;
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function index(Request $request)
    {
        $userData = $request->user();
        return [
            'name' => $request->user()->name,
            'id' => $request->user()->id,
            'random' => Str::random(20),
            'test' => $request->all(),
            'userdata' => [
                $userData->name
            ]
        ];
    }

    public function publishPost(Request $request)
    {
        $userData = $request->user();
        $postData = $request->all();

        $validator = $this->validator($request->all());
        if(!$validator->fails()){

            if (!$this->create($postData, $userData)){
                return response()->json([
                    'success' => false,
                    'errors' => 'Failed to create Post'
                ], 422);
            }

            $post = Post::latest()->first();

            $tags = $this->createTags($post->id_r, $request->input('tags'));

            return response()->json([
                'success' => 'true',
                'post' => $post,
                //'tags_s' => $post->id_r,
                'tags' => $tags
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'message' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     */
    protected function create(array $data, object $user)
    {
        $new_post = new Post;

        $new_post->id_r = Str::random(20);
        $new_post->user_id = $user->id;
        $new_post->user_name = $user->name;
        $new_post->message = $data['message'];

        return $new_post->save();
    }
    /**
     * Genera la lista dei post
     *
     * @param  Request  $request
     * @return Response
     */

    public function show(Request $request)
    {
        if (request()->input('usertag'))
        {
            return response()->json([
                'success' => true,
                'postData' => Post::orderBy('id', 'desc')
                    ->where('user_name', request()->input('usertag'))
                    ->get(),
                'voteData' => 'none'
            ], 200);
        }
        else{
            return response()->json([
                'success' => true,
                'postData' => Post::orderBy('id', 'desc')->get(),
                'voteData' => 'none'
            ], 200);
        }
        }


    public function updatePost(Request $request)
    {
        $data = $request->all();

        if ($request->input('update') && $request->input('id')) {
            $vote_db = $this->updatePostDB($data);
            return response()->json([
                'success' => $vote_db
            ], 200);
        }

    }

    protected function updatePostDB(array $data)
    {
        return Post::where('id_r', $data['id'])
            ->update(['message' => $data['update']]);
    }

    public function vote(Request $request)
    {
        $data = $request->all();

        if (
            $data['value'] === "1"
            || $data['value'] === "0"
            && $request->input('id')
            )
        {
            $check['id_post'] = $request->input('id');
            $check['id_voter'] = $request->user()->id;
            $check['value'] = $data['value'];

            $voteChecked = $this->checkVote($check);
            if ($voteChecked['num']) {
                if ($voteChecked['val'] === $data['value']) {
                    return response()->json([
                        'success' => false,
                        'status' => $voteChecked
                    ], 200);
                } else {
                    $updatedVote = $this->updateVote($check);
                    return response()->json([
                        'success' => $updatedVote,
                        'status' => $voteChecked
                    ], 200);
                }
            } else {
                $vote_db = $this->createVote($data, $request->user());
                return response()->json([
                    'success' => $vote_db
                ], 200);
            }
        }
        return 'cope';
    }

    protected function createVote(array $data, object $user) {
        $votes = new Votes;

        $votes->id_r = Str::random(20);
        $votes->id_voter = $user->id;
        $votes->id_post = $data['id'];
        $votes->vote_type = $data['value'];

        return $votes->save();

    }

    protected function checkVote(array $data){
        $count['num'] = Votes::where('id_post', $data['id_post'])
        ->where('id_voter', $data['id_voter'])
        ->count();
        $count['val'] = Votes::where('id_post', $data['id_post'])
            ->where('id_voter', $data['id_voter'])
            ->value('vote_type');
        return $count;
    }

    protected function updateVote(array $data){
        return Votes::where('id_post', $data['id_post'])
            ->where('id_voter', $data['id_voter'])
            ->update(['vote_type' => $data['value']]);
    }

    public function countVotes(Request $request)
    {
        $upVotes = Votes::where('id_post', $request->input('id'))
            ->where('vote_type', '1')
            ->count();
        $downVotes = Votes::where('id_post', $request->input('id'))
            ->where('vote_type', '0')
            ->count();

        return response()->json([
            'success' => true,
            'up_votes' => $upVotes,
            'down_votes' => $downVotes
        ]);

    }

    public function allVotes(Request $request)
    {
        $upVotes = Votes::where('vote_type', '1')
            ->get();
        $downVotes = Votes::where('vote_type', '0')
            ->get();

        return response()->json([
            'success' => true,
            'up_votes' => $upVotes,
            'down_votes' => $downVotes
        ]);
    }

    protected function createTags(string $post_id, array $tags = [])
    {
        $validationErr = [];

        if(count($tags)){
            $validTags = 0;
            //Aggiunge nuovi hashtags
            foreach($tags as $tag){
                $validate = [
                    'name' => $tag
                ];

                !$this->tagValidator($validate)->fails() ?
                $this->tagCreate($tag) //$validTags++ 
                : array_push($validationErr, $this->tagValidator($validate)->errors());
            }
            //relaziona post e tags
            $tags = Tags::whereIn('name', $tags)->get();

            foreach ($tags as $tag) {
                $tag->post()->attach($post_id);
            }

            return [
                'post' => $post_id,
                'tags' => $validTags,
                'err' => $validationErr
            ];
        }
        else{
            return [
                'post' => $post_id,
                'tags' => 'false'
            ];
        }
        return [
            'post' => $post_id,
            'tags' => $tags
        ];
        
    }

    protected function tagCreate(string $data) {
        $new_tag = new Tags;

        $new_tag->name = $data;
        $new_tag->type = 'hashtag';

        return $new_tag->save();

    }

    protected function tagValidator(array $data)
    {
        return Validator::make($data, [
            'name'=> ['required', 'string', 'max:255', 'unique:Tags'],
        ]);
    }
}
