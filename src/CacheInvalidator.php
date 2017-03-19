<?php

namespace Anorgan\LaravelCache;

use AlternativeLaravelCache\Core\AlternativeCacheStore;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CacheInvalidator
 * @package Anorgan\LaravelCache
 */
class CacheInvalidator
{
    /**
     * @var AlternativeCacheStore
     */
    private $cacheStore;

    /**
     * @var TagFinder
     */
    protected $tagFinder;

    /**
     * CacheInvalidator constructor.
     * @param AlternativeCacheStore $cacheStore
     * @param TagFinder $tagFinder
     */
    public function __construct(AlternativeCacheStore $cacheStore, TagFinder $tagFinder)
    {
        $this->tagFinder = $tagFinder;
        $this->cacheStore = $cacheStore;
    }

    /**
     * @param Model $model
     */
    public function invalidateByModel(Model $model)
    {
        $tags = $this->tagFinder->find($model);
        $this->invalidateByTags($tags);
    }

    /**
     * @param array $tags
     */
    public function invalidateByTags(array $tags)
    {
        $this->cacheStore->tags($tags)->flush();
    }

    /**
     * @param array $keys
     */
    public function invalidateByKeys(array $keys)
    {
        foreach ($keys as $key) {
            $this->cacheStore->forget($key);
        }
    }
}
