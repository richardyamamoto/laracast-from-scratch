# Laravel 6.0 From Scratch

This step by step documentation, will serve as consulting material for further studying.

[Laravel](https://laravel.com/) require [Composer](https://getcomposer.org/) and [Xampp](https://www.apachefriends.org/pt_br/index.html) 

---

## Index

- [Basic Routes and Views](#basic-routes-and-views)
- [Pass Request Data to Views](#pass-request-data-to-views)

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