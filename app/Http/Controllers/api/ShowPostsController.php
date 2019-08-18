<?php

namespace App\Http\Controllers\api;

use App\Models\Postlists;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => 'test'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Postlists  $postlists
     * @return \Illuminate\Http\Response
     */
    public function show(Postlists $postlists)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Postlists  $postlists
     * @return \Illuminate\Http\Response
     */
    public function edit(Postlists $postlists)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Postlists  $postlists
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Postlists $postlists)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Postlists  $postlists
     * @return \Illuminate\Http\Response
     */
    public function destroy(Postlists $postlists)
    {
        //
    }
}
