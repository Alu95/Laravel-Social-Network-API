<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\ConverterInterface;
use Illuminate\Database\Eloquent\Collection;
//models
use App\Models\Tag_post_relationship;
use App\Models\Tags;
use App\Models\Post;
use App\Models\Post_tags;
use Illuminate\Support\Arr;

class HashtagController extends Controller
{
    protected $converter;

    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        return response()->json([
            'id' => Post::orderBy('id', 'desc')
                        ->where('message', 'like', '%#'. $request->input('id').' %')
                        ->get()
        ], 200);
    }
    
    public function topTags(Request $request){
        
        $tags = [];
        $hashtags = Post_tags::all();

        foreach($hashtags as $tag){
            array_push($tags, $tag->tags()->first());
        }

        $test5 = array_count_values(array_column($tags, 'name'));
        arsort($test5);

        return response()->json([
            'test5' =>  $test5
        ]);
    }
    

    public function registerTags(Request $request)
    {
        $tags = Tags::whereIn('name', ['mGUP', 'testTag2'])->get();

        foreach($tags as $tag){
            $tag->post()->attach($request->input('id'));
        }

        return response()->json([
            'post' => $tags
        ], 200); 
    }
    
    protected function testTags(string $post_id, array $tags = []){
        return [
            'post' => $post_id,
            'tags' => $tags
        ];
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'tag' => ['required', 'string', 'email', 'max:255', 'unique:PostLists'],
        ]);
    }

    
}
