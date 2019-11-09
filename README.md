# Laravel 6.0 From Scratch

This step by step documentation, will serve as consulting material for further studying.

[Laravel](https://laravel.com/) require [Composer](https://getcomposer.org/) and [Xampp](https://www.apachefriends.org/pt_br/index.html) 

---

## Index

- [Routing](#routing)

---

## Routing

To start the application we use the following command at the project folder
```bash
php artisan serve
```

The **views** files will be placed at [resources/views](/resources/views/welcome.blade.php), the [Blade](https://laravel.com/docs/6.x/blade) extension template.

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