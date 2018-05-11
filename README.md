# Laravel-Kuainiu

Laravel-Kuainiu incorporates Kuainiu Connect and the Kuainiu API  into your [Laravel](https://laravel.com/)

## Requirements

* [Laravel Socialite](https://github.com/laravel/socialite) (if you intend on using Mollie Connect)

## Installation

Add Laravel-Kuainiu to your composer file via the `composer require` command:

```bash
$ composer require kuainiu/laravel-kuainiu
```

Or add it to `composer.json` manually:

```json
"require": {
    "kuainiu/laravel-kuainiu": "~1.0"
}
```

## Configuration

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="Kuainiu\KuainiuServiceProvider"
```

This will create a `config/kuainiu.php` file in your app that you can modify to set your configuration.

### Kuainiu PAssport Connect with Laravel Socialite

If you intend on using Mollie Connect, update `config/services.php` by adding this to the array:

```php
'kuainiu' => [
    'client_id' => env('KUAINIU_CLIENT_ID', 'app_xxx'),
    'client_secret' => env('KUAINIU_CLIENT_SECRET'),
    'redirect' => env('KUAINIU_REDIRECT_URI'),
],
```

To make sure Laravel Socialite can actually find the Mollie driver, use the following code snippet and paste it in the `boot()` method from your `AppServiceProvider.php`.

```php
Socialite::extend('mollie', function ($app) {
    $config = $app['config']['services.kuainiu'];

    return Socialite::buildProvider('Kuainiu\KuainiuConnectProvider', $config);
});
```

## Usage

Here you can see an example of just how simple this package is to use.

### Kuainiu API

```php
$user = Kuainiu::api()->user()->create([
    "name"      => 'user_name_',
    "mobile" => "13800138000"
]);

$user = Kuainiu::api()->user()->get($user->id);

if ($user->isCreated())
{
    echo "User Created.";
}
```

### Kuainiu Passport Connect with Laravel Socialite

```php
Route::get('login', function () {
    return Socialite::with('kuainiu')
        ->scopes(['profiles.read']) // Additional permission: profiles.read
        ->redirect();
});

Route::get('login_callback', function () {
    $user = Socialite::with('kuainiu')->user();

    Kuainiu::api()->setAccessToken($user->token);

    return Kuainiu::api()->users()->all(); // Retrieve all payment profiles available on the obtained Mollie account
});
```

## License

Copyright (c) 2018, Kuainiu Group