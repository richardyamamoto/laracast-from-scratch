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
- [Migrations](#migrations)
- [Generate Multiple Files in a Single Command](#generate-multiple-files-in-a-single-command)
- [Business Logic](#business-logic)
- [Layout Pages](#layout-pages)
- [Integrate a Site Template](#integrate-a-site-template)
- [Set an Active Menu Link](#set-an-active-menu-link)
- [Render Dynamic Data](#render-dynamic-data)

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

## Migrations

Usually the creation of tables are made by migrations. This way, everyone that runs the project will have the same database structure.

To create a migration 
```bash
php artisan make:migration <migration_name>
```
So create the migration for the Posts table
```bash
php artisan make:migration create_posts_table
```

The migration always going to have two methods, `up()` - make changes(moving forward) and `down()` - unmake changes(rollback).

When the migration is created, we should be able to undo the changes.

Create the following columns
```php
Schema::create('posts', function (Blueprint $table) {
  $table->bigIncrements('id');
  $table->string('slug');
  $table->text('body');
  $table->timestamps();
  $table->timestamp('published_at')->nullable();
});
```
If we need to create a new column, the right way to do that is creating another migration
```bash
php artisan make:migration add_title_to_posts_table
```
Then on method `up()`
```php
Schema::table('posts', function (Blueprint $table) {
  $table->string('title');
});
```
And following the method `down()`
```php
Schema::table('posts', function (Blueprint $table) {
  $table->dropColumn('title');
})
```
Then run the migration
```bash
php artisan migrate
```
When on production, if we rollback the migration, all data will be lost. So be careful.

To drop evething and re-run
```bash
php artisan migrate:fresh
```
Back to [Index](#index)

---

## Generate Multiple Files in a Single Command

We can generate model, controller and factory with a sigle command line.

On this example we will create a model for Project
```bash
php artisan make:model Project -mc
```
Back to [Index](#index)

---

## Business Logic

We are going to create the Assigment model and controller.
```bash
php artisan make:model Assigment -mc
```
Then on Assigment migration
```php
Schema::create('assigments', function (Blueprint $table) {
  $table->bigIncrements('id');
  $table->string('body');
  $table->boolean('completed')->default(false);
  $table->timestamp('due-date')->nullable();
  $table->timestamps();
});
```
Now using Tinker to test
```bash
php artisan tinker
```
Now we instanciate our model, but first we'll create some data to manipulate
```
$assigment = new App\Assigment;
$assigment->body = "Finish the laravel study";
```
We can show all the assigments by
```
App\Assigment::all();
```
Or query with some filters
```
App\Assigment::where('completed', false)->get();
```
Knowing that, instanciate the model getting the first result
```
$assigment = App\Assigment::first();
```
We will change the complete column with a method, so go to Assigment model and create the method `complete()`
```php
public function complete() {
  $this->complete = true;
  $this->save();
}
```
By the moment we are able to call the method from the instanciated object, reset the tinker bash and run
```
$assigment->complete();
```
Back to [Index](#index)

---

## Layout Pages

The Layout pages can be seen as a template model structure, this will facilitate by centralizing the import of scripts, styles, etc.

Create a layout and contact view

[resources/views/layout.blade.php](resources/views/layout.blade.php)
[resources/views/contact.blade.php](resources/views/layout.blade.php)

Then at [layout.blade.php](resources/views/layout.blade.php) copy the HTML structure and the specific HTML will be spit by
```
@yield('welcome')
@yield('contact')
```
At the place we put it.

Go to [contact.blade.php](resources/views/layout.blade.php)and place the blade section syntax and extends
```blade
@extends('layout')
@section('contact')
  <h1>Contact page</h1>
@endsection
```
And do the same to Welcome view.

Go to [routes/web.php](routes/web.phproutes/web.php) and create a route to Contact page
```php
Route::get('/contact', function() {
  return view('contact');
})
```

Back to [Index](#index)

---

## Integrate a Site Template

We are using https://templated.co/simplework, after dowload the files, extract files to
```bash
public
  |__css
  |   |__default.css
  |   |__fonts.css
  |__fonts
  |__images
```
Make the pertinent changes to css work again.

Open the downloaded index.html, create a [layout.blade.php](resources/views/layout.blade.php), then cut the content from body tag and put 
```blade
<body>
  @yield('content')
</body>
```

Now create [welcome.blade.php](resources/views/welcome.blade.php), extends the layout and put the section blade tag
```blade
@extends('layout')
@section('content')
  // Paste the content here
@endsection
```
Now go to [routes/web.php](routes/web.php) and create a route redirecting to welcome
```php
Route::get('', function(){
  return view('welcome');
});
```
Back to [Index](#index)

---

## Set an Active Menu Link

We can easily change the class of an HTML element. In our case, we have to put the class `current_page_item` on each item of the list if we are at its page.

First go to the [layout.blade.php](resources/views/layout.blade.php), there is a `Request` class that has the method `path()` which returns the directory. To solve the problem we take the following approach
```php
<div id="menu">
  <ul>
    <li class="{{ Request::path() === '/' ? 'current_page_item' : '' }}"><a href="/" accesskey="1" title="">Homepage</a></li>
    <li class="{{ Request::path() === 'clients' ? 'current_page_item' : '' }}"><a href="/clients" accesskey="2" title="">Our Clients</a></li>
    <li class="{{ Request::path() === 'about' ? 'current_page_item' : '' }}"><a href="/about" accesskey="3" title="">About Us</a></li>
    <li class="{{ Request::path() === 'carrers' ? 'current_page_item' : '' }}"><a href="/carrers" accesskey="4" title="">Careers</a></li>
    <li class="{{ Request::path() === 'contacts' ? 'current_page_item' : '' }}"><a href="/contact" accesskey="5" title="">Contact Us</a></li>
  </ul>
</div>
```
Back to [Index](#index)

---

## Render Dynamic Data

We are going to create some articles to render it dynamically, first of all, we create an eloquent model
```bash
php artisan make:model Article -mc
```
Go to the Article migration and put the columns
```php
public function up()
{
  Schema::create('articles', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('title');
    $table->text('excerpt');
    $table->text('body');
    $table->timestamps();
  });
}
```
Run the migration 
```bash
php artisan migrate
```
Right after, enter Tinker to add some data to Database
```tinker
$article = new App\Article
$article->title = "Something for the title"
$article->body = "Something for the body"
$article->excerpt = "Something for excerpt"
$article->save()
```
Navigate to the Routes [routes/web.php](routes/web.php), we are going to edit the Contact page route, just fetch the data from DB(for test)
```php
Route::get('/contact', function() {
  $article = App\Article::all();
  return $article;
});
```
>Method `all()` show all the data from the migration

>Method `take(number_of_items : int)` show the specific number of items 

>Method `paginate(number_of_items_per_page : int)` show the specific number of items per page

We can ordinate using method `latest(column_name : string)` providing any timestamp
>As default it will order by created_at descending order
```php
Route::get('/contact', function() {
  return view('about', [
    'articles' => $articles = App\Article::latest()->get();
  ])
});
```
Go to [about.blade.php](resources/views/about.blade.php), and find the list to recover the article object.

Using for each to render multiple items
```php
@foreach ($articles as $article) 
  <li class="first">
    <h3>{{ $article->title }}</h3>
    <p><a href="#">{{ $article->excerpt}}</a></p>
  </li>
@endforeach
```
By now, the articles must be rendering on the screen.

Back to [Index](#index)

---

