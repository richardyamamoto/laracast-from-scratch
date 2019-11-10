# Laravel 6.0 From Scratch

This step by step documentation, will serve as consulting material for further studying.

[Laravel](https://laravel.com/) require [Composer](https://getcomposer.org/) and [Xampp](https://www.apachefriends.org/pt_br/index.html) 

---

## Index

- [Basic Routes and Views](#basic-routes-and-views)
- [Pass Request Data to Views](#pass-request-data-to-views)
- [Route Wildcards](#route-wildcards)
- [Setup Database Connection](#setup-database-connection)
- [Eloquent model](#eloquent-model)

---

## Basic Routes and Views

To start the application we use the following command at the project folder
```bash
php artisan serve
```

The **views** files will be placed at [resources/views](/resources/views/welcome.blade.php) using the [Blade](https://laravel.com/docs/6.x/blade) template engine.

Go to [routes/web.php](routes/web.php), notice that we have
```php
Routes::get('/', function() {
  return view('welcome');
})
```
Method `get(url_adress : string, callback : function)`, the callback function will return the method `view(view_name : string, properties : array : any)`.

Let's change the return from callback function and the url adress.
```php
Route::get('/welcome', function() {
  return "<h1>Hello World</h1>";
})
```
>When user visits localhost:8000/welcome, the "Hello World" will be shown.

We can return arrays too, and the browser should convert it to an object JSON.
```php
Route::get('/welcome2', function() {
  return ["foo" => "array/json"];
})
```
Ok, now create a new view to test the routes. At [resources/views/test.blade.php](resources/views/test.blade.php), create a html structure and put a `<h1>Test view</h1>` just to identify the page when answering the request.

Back to [routes/web.php](routes/web.php), create 
```php
Route::get('/test', function() {
  return view('test');
});
```
Back to [Index](#index)

---

## Pass Request Data to Views

We can pass request data via query parameters.
```url
localhost:8000/test?name=Richard
```

To retrieve the data we use method `request(query_name : string)`

We can make it directly from the routes, just for visualization
```php
Route::get('/test', function() {
  $name = request('name');
  return $name;
})
```
But if I want to show this data directly from the view and not from routes?

The method `view()`, waits as second parameter an object with properties.

```php
Route::get('/test', function() {
  $name = request('name');
  return view('test', [
    "name" => $name,
  ])
});
```
Refactoring the code above
```php
Route::get('/test', function() {
  return view('test', [
    "name" => request('name'),
  ])
})
```
To receive this data, there is some ways, first go to [resources/views/test.blade.php](resources/views/test.blade.php) and use the tag
```php
<?= $name ?>
```
This tag is a shorthand of
```php
<?php
  echo $name;
?>
```
Now come back to the browser and refresh. The result should be the same.

But this way, the user are able to insert whatever he want's without any validation. Try this at the url adress

```url
localhost:8000/test?name=<script>alert("Hello");</script>
```
To solve this problem, we can use the `htmlspecialchars(variable, ENT_QUOTES)`. Go to test view
```php
<?= htmlspecialchars($name, ENT_QUOTES); ?>
```
Or maybe you want to do not skip the query parameter, for this you can use the double brackets with two pair of exlamation marks.
```php
<p>{{!! $name !!}}</p>
```
Back to [Index](#index)

---

## Route Wildcards

We can pass data on the url as parameter like:
```url
localhost:8000/posts/123
```
This is called wildcards, this url will match the `posts` view then match the `123` parameter.

Go to [routes/web.php](routes/web.php), then create the file [posts.blade.php](resources/views.posts.blade.php)

Back to [routes/web.php](routes/web.php), create a new route to test the parameter passed by url.
```php
Route::get('/posts/{post_id}', function($post_id) {
  $posts = [
    'post1' => 'This is the first post',
    'post2' => 'The second post',
  ];
  // Verify if the object key exists
  if(! array_key_exists($post_id, $posts)) {
    abort(404, "Key was not found");
  }

  return view('posts', [
    'post' => $posts[$post_id],
  ]);
});
```
>arr_key_exists(parameter : variable, array) : This method verifies if the key exists in the array.

Back to [Index](#index)

---

## Routing to Controllers

The way we are doing till now is just for learning. But the right way is routing to Controllers.

At [routes/web.php](routes/web.php), just declare the route to controller
```php
Route::get('/posts/{post_id}', 'PostsController@show');
```
There is an automated way to create the controller
```bash
php artisan make:controller <controller_name>
```
We'll create the `PostsController`
```bash
php artisan make:controller PostsController
```
Then inside the brand new controller [app/Http/Controllers/PostsController.php](app/Http/Controllers/PostsController.php) create the method show and put the block of code we just created on last section.
```php
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
```
Back to [Index](#index)

---

## Setup Database Connection

At [.env](.env) we have the Database environment variables
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<database_name>
DB_USERNAME=<user_name>
DB_PASSWORD=<database_password>
```
Change the `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD`

Now we have to create the database

First run the terminal
>We assume that MySQL is installed and running.
```bash
mysql -u <user_name> -p
```
Then type your password

Ok, now we use MySQL cli to create our Data Base.
### SQL commands

[List of commands]( https://www.w3schools.com/sql/)

>SQL commands are not case sensitive

Create the `laravel6` database
```bash
CREATE DATABASE laravel6;
```
Then select the database
```bash
USE laravel6;
```
Create the table posts
```bash
CREATE TABLE posts(
  id int auto_increment not null,
  slug text not null,
  body text not null,
  primary key(id)
);
```
Check the details of the table
```bash
DESC posts;
```
Insert one row to posts table
```bash
INSERT INTO posts(slug, body)
VALUES("post1", "This is the first post");
```
Show table data
```bash
SELECT * FROM posts;
```
Now back to our [PostController](app/Http/Controllers/PostsController.php)
```php
public function show($slug) {
  $post = \DB::table('posts')->where('slug', $slug)->first();
  // dd($post); // dump and die -> shows data and stop execution, justo to check the data is coming

  return view('posts',[
    'post' => $post,
  ]);
}
```

This way we are receiving an object, so at [posts.blade.php](resources/views/posts.blade.php) put
```php
{{ $post->body }}
```
Back to [Index](#index)

---

## Eloquent model

The model will allocate the business rules of database.

To create it we use
```bash
php artisan make: model Post
```

At the [PostsController](app/Http/Controllers/PostsController.php), import
```php
App\Post;
```
And use the model to query data
```php
public function show($slug) {
  $post = Post::where('slug',$slug)->firstOrFail();
  return view('posts',[
    'post' => $post,
  ])
}
```
>firstOrFail() -> Will return the first, if it fails, aborts

Back to [Index](#index)

---
