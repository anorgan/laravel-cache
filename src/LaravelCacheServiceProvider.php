<?php

namespace Anorgan\LaravelCache;

use AlternativeLaravelCache\Core\AlternativeCacheStore;
use AlternativeLaravelCache\Provider\AlternativeCacheStoresServiceProvider;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelCacheServiceProvider
 * @package Anorgan\LaravelCache
 */
class LaravelCacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-cache.php' => config_path('laravel-cache.php'),
        ], 'config');

        $this->app->bind(AlternativeCacheStore::class, function () {
            return $this->app->make(CacheManager::class)->store()->getStore();
        });

        Event::listen(['eloquent.created: *', 'eloquent.updated: *', 'eloquent.deleted: *', 'eloquent.restored: *'], function (Model $model) {
            /** @var CacheInvalidator $cacheInvalidator */
            $cacheInvalidator = $this->app->make(CacheInvalidator::class);
            $cacheInvalidator->invalidateByModel($model);

            $invalidateRules = config('laravel-cache.invalidate');
            if (isset($invalidateRules[get_class($model)])) {
                $cacheInvalidator->invalidateByKeys($invalidateRules[get_class($model)]);
            }
        });
    }

    public function register()
    {
        $this->app->register(AlternativeCacheStoresServiceProvider::class);
        $this->app->afterResolving('cache', function () {
            $cacheManager = $this->app->make('cache');
            $cacheManager->extend('redis', function ($app, array $cacheConfig) use ($cacheManager) {
                $store = new AlternativeRedisCacheStore(
                    $app['redis'],
                    array_get($cacheConfig, 'prefix') ?: config('cache.prefix'),
                    array_get($cacheConfig, 'connection', 'default') ?: 'default'
                );
                return $cacheManager->repository($store);
            });
        });
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-cache.php', 'laravel-cache');
    }
}
