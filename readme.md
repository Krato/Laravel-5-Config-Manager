## Laravel 5 Config Manager

[![Latest Stable Version](https://poser.pugx.org/infinety-es/config-manager/v/stable?format=flat-square)](https://packagist.org/packages/infinety-es/config-manager)
[![Total Downloads](https://poser.pugx.org/infinety-es/config-manager/downloads?format=flat-square)](https://packagist.org/packages/infinety-es/config-manager)
[![StyleCI](https://styleci.io/repos/79101838/shield)](https://styleci.io/repos/79101838)
[![License](https://img.shields.io/packagist/l/infinety-es/config-manager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/config-manager)

With this package you can edit your Laravel config files in an easy way.

![Laravel 5 Config Manager](http://i.imgur.com/AVr7Jjl.png)



### Usage Instructions

Install through composer:
```php
composer require infinety-es/config-manager
```

Add this to `app/config/app.php` under the *providers* key:

```php
Infinety\ConfigManager\ConfigManagerServiceProvider::class,
```

Publish package files:
```php
php artisan vendor:publish --provider="Infinety\ConfigManager\ConfigManagerServiceProvider"
```

Create default layout with `php artisan make:auth` and edit `layouts/app.blade.php` and add a yield called *script* after *app.js*. Your **app.blade.php** should look like:

```
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
```

Edit `config/configmanager.php` config file to set the route and middleware, default to:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the translations routes.
    |
    */
    'route' => [
        'prefix' => 'dashboard/config',
        'middleware' => [
            'web',
            'auth',
            'role:admin',
        ],
    ],
];

```

And now go to your route. Yo will see all config files in the select. Choose one and the page will refresh with the config keys and values.

## Important notice: 

Currently this package is not saving no defined arrays. Take a look this example:

```php
<?php

return [
    'route' => [
        'myData' => [ //This array can not be changed in this moment
            'first', 
            'second',
            'third',
        ],
        'myObject' => [ //This array can be changed because has keys
            'demo' => false,
            'test' => true
        ]
        'custom' => true, //This can be changed
        'value' => 'my own value' //This can be changed
    ],
];

```

I will try to fix shortly. Also if you like it you can make a Pull Request,

## License

Mit

## Author

[Eric Lagarda](https://github.com/Krato)

## Contributing [![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/Krato/Laravel-5-Config-Manager/issues)


Hope you like it!
