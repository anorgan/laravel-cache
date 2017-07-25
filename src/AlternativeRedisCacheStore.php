<?php

namespace Anorgan\LaravelCache;

use \AlternativeLaravelCache\Store\AlternativeRedisCacheStore as BaseAlternativeRedisCacheStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AlternativeRedisCacheStore extends BaseAlternativeRedisCacheStore
{
    /**
     * @param $key
     * @param $minutes
     * @param Closure $callback
     * @param array $tags
     */
    public function rememberWithTags($key, $minutes, \Closure $callback, array $tags = [])
    {
        // If the item exists in the cache we will just return this immediately
        // otherwise we will execute the given Closure and cache the result
        // of that execution for the given number of minutes in storage.
        if (! is_null($value = $this->get($key))) {
            return $value;
        }

        $value = $callback();

        if ($value instanceof Collection || $value instanceof Model) {
            $tags  = array_merge(app(TagFinder::class)->find($value), $tags);
        }

        $this
            ->tags($tags)
            ->put($key, $value, $minutes);

        return $value;
    }
}
