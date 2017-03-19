# Cache models by tagging

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