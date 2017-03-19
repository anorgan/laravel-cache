# Cache models by tagging

[![Latest Version](https://img.shields.io/github/release/anorgan/laravel-cache.svg?style=flat-square)](https://github.com/anorgan/laravel-cache/releases)
[![Quality Score](https://img.shields.io/scrutinizer/g/anorgan/laravel-cache.svg?style=flat-square)](https://scrutinizer-ci.com/g/anorgan/laravel-cache/?branch=master)
[![Build Status](https://img.shields.io/travis/anorgan/laravel-cache.svg?style=flat-square)](https://travis-ci.org/anorgan/laravel-cache)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/anorgan/laravel-cache/master/LICENSE)

This package aims to help with caching of models by tagging them and invalidating caches on model change.

## Installation

Install this package via composer by running:

```bash
composer require anorgan/laravel-cache:^1.0
```

Add to providers:

```php
// config/app.php
'providers' => [
    ...
    Anorgan\LaravelCache\LaravelCacheServiceProvider::class
];
```

To publish the config, run:

```bash
php artisan vendor:publish --provider="Anorgan\LaravelCache\LaravelCacheServiceProvider" --tag="config"
```

Config looks like this:

```php
<?php

return [
    /*
     * Add keys per model which should be invalidated alongside default model key and tags,
     * e.g. for Product::class, you would like to invalidate cache with key "product_listing"
     */
    'invalidate' => [
        \App\Product::class => [
            'product_listing'
        ]
    ],
];

```

## Testing

You can run the tests with:

```bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
