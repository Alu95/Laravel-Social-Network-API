<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostLists;
use App\Parsers\TwitterHandleParser;
use Illuminate\Support\Facades\App;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Converter;
use League\CommonMark\Inline\Parser;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Block\Element\AbstractStringContainerBlock;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Node\Node;


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
            'id' => PostLists::orderBy('id', 'desc')
                        ->where('message', 'like', '%#'. $request->input('id').' %')
                        ->get()
        ], 200);
    }
    
    public function test(Request $request){
        return $this->converter->convertToHtml('foo');
    }


    
}
