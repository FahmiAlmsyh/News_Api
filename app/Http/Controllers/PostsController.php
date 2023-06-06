<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsDetailResource;
use App\Http\Resources\PostsResource;
use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

class PostsController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:sanctum'])->only('store', 'update');
        $this->middleware(['pemilik-news'])->only('update');
    }

    public function index() 
    {
        $posts = posts::all();
        // return response()->json(['news' => $posts]);

        return PostsResource::collection($posts);
    }

    public function show($id) {
        $post = Posts::with('writer:id,username')->findOrfail($id);
        // return response()->json(['data' => $post]);
        return new PostsDetailResource($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'Required',
            'news_content' => 'Required',
        ]);

        $post = posts::create([
            'title'         => $request->input('title'),
            'news_content'  => $request->input('news_content'),
            'author'        => Auth::user()->id,
        ]);

        return new PostsDetailResource($post->loadMissing('writer'));
    }

    public function update(Request $request, $id)
    {
       dd('ini bener postinganmu');
    }
}
