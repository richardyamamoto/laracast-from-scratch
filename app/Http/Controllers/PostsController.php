<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function show($post_id)
    {
        $posts = [
            'post1' => 'This is the first post',
            'post2' => 'The second post',
        ];
        if (!array_key_exists($post_id, $posts)) {
            abort(404, 'Key was not found');
        }
        return view('posts', [
            'post' => $posts[$post_id],
        ]);
    }
}
