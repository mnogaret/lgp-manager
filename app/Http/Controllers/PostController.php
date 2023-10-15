<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function show($id) {
        $post = Post::find($id);
        if (!$post) {
            return "Post not found!";
        }
        return "Title: " . $post->title . " | Body: " . $post->body;
    }
}
