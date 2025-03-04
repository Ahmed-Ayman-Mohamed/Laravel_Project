<?php

namespace App\Http\Controllers\PostFolder;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('post');
    }
    public function insert(Request $request)
    {
        // $post = new Post();
        // $post->title = $request->title;
        // $post->body = $request->body;
        // $post->save();

        Post::create($request->all());

        return redirect()->back()->with(['success' => 'inserting success']);
    }
    public function showPosts()
    {
        $posts = DB::table('posts')->get();
        // return view('index', compact('posts'));
        // return view('index', [
        //     'posts' => $posts,
        // ]);

        return response()->json($posts);
    }
    public function edit($id)
    {
        return $id;
    }

    public function getAllPosts()
    {
        $posts = Post::all();
        return response()->json(['posts' => $posts]);
    }
}
