## Laravel 5 Config Manager

With this package you can edit your Laravel config files in an easy way.

![Laravel 5 Config Manager](http://i.imgur.com/AVr7Jjl.png)


### Usage Instructions

Install through composer:
```php
composer require "infinety-es/configmanager"
```

Add this to `app/config/app.php` under the 'providers' key:

```php
'Infinety\Config\ConfigManagerServiceProvider',
```

Publish package files:
```php
php artisan vendor:publish --provider="Infinety\ConfigManager\ConfigManagerServiceProvider"
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


Hope you like it!
