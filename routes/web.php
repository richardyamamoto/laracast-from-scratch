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
Route::get('/test', function() {
    return view('test');
});