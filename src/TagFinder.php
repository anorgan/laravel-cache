<?php

namespace Anorgan\LaravelCache;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;

/**
 * Class TagFinder
 * @package Anorgan\LaravelCache
 */
class TagFinder
{
    /**
     * @param Collection|Model $models
     * @return array
     */
    public function find($models)
    {
        if ($models instanceof Model) {

            // Ignore Pivot models
            if ($models instanceof Pivot) {
                return [];
            }

            $models = collect([$models]);
        }

        return array_values(array_unique($this->getTagsFromCollection($models)));
    }

    /**
     * @param Collection $collection
     * @return array
     */
    private function getTagsFromCollection(Collection $collection)
    {
        $tags = [];

        foreach ($collection as $model) {
            $tags = array_merge($tags, $this->getTags($model));
        }

        return $tags;
    }

    /**
     * @param Model $model
     * @return array
     */
    private function getTags(Model $model)
    {
        if ($model instanceof Pivot) {
            // Ignore Pivot models
            return [];
        }

        $tags = [
            $this->createTag($model),
        ];

        foreach ($model->getRelations() as $relation) {
            if ($relation instanceof Collection) {
                $tags = array_merge($tags, $this->getTagsFromCollection($relation));
            } elseif ($relation instanceof Pivot) {
                // Ignore Pivot models
                $tags = [];
            } elseif ($relation instanceof Model) {
                $tags = array_merge($tags, $this->getTags($relation));
            }
        }

        return $tags;
    }

    /**
     * @param Model $model
     * @return string
     */
    private function createTag(Model $model)
    {
        return sprintf('%s_%s', str_singular($model->getTable()), $model->getKey());
    }
}
