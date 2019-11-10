<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// ---section2-class1---
// Route::get('/welcome', function () {
//     return "<h1>Hello Internet</h1>";
// });
// Route::get('/welcome2', function () {
//     return ["foo" => "json/array"];
// });
// Route::get('/test', function() {
//     return view('test');
// });

Route::get('/test', function() {
    // $name = request('name');
    // return view('test', [
    //     "name" => $name,
    // ]);
    return view('test', [
        'name' => request('name'),
    ]);
});

Route::get('/posts/{post_id}', function($post_id) {
    $posts = [
        'post1' => 'This is the first post',
        'post2' => 'The second post',
    ];
    if(! array_key_exists($post_id, $posts)) {
        abort(404, 'Key was not found');
    }
    return view('posts', [
        'post' => $posts[$post_id],
    ]);
});
