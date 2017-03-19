<?php

namespace Anorgan\LaravelCache\Test;

use Anorgan\LaravelCache\TagFinder;
use Anorgan\LaravelCache\Test\Model\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class TagFinderTest
 * @package Anorgan\LaravelCache\Test
 */
class TagFinderTest extends TestCase
{
    public function testFindingTagsWhenPassingCollectionOfModelsReturnsUniqueTags()
    {
        $tagFinder = new TagFinder();

        $collection = collect();

        $model      = new Product();
        $model->id  = 123;
        $collection->push($model);

        // This one is a duplicate
        $model      = new Product();
        $model->id  = 123;
        $collection->push($model);

        $model      = new Product();
        $model->id  = 456;
        $collection->push($model);

        $tags      = $tagFinder->find($collection);

        $this->assertCount(2, $tags);
        $this->assertEquals('product_123', $tags[0]);
        $this->assertEquals('product_456', $tags[1]);
    }

    public function testFindingTagsWhenPassingModelReturnsTags()
    {
        $tagFinder = new TagFinder();

        $model     = new Product();
        $model->id = 123;

        $tags      = $tagFinder->find($model);

        $this->assertCount(1, $tags);
        $this->assertEquals('product_123', $tags[0]);
    }

    public function testFindingTagsWhenPassingModelWithRelationsReturnsAllTags()
    {
        $tagFinder = new TagFinder();

        $collection = collect();

        $model      = new Product();
        $model->id  = 123;

        // Single relation
        $relation     = new Product();
        $relation->id = 456;

        $relationsRelation = new Product();
        $relationsRelation->id = 159;
        $relation->setRelation('relationRelations', collect([$relationsRelation]));
        $model->setRelation('product', $relation);

        // Collection relation
        $relation     = new Product();
        $relation->id = 789;
        $model->setRelation('products', collect([$relation]));

        $collection->push($model);

        $tags      = $tagFinder->find($collection);

        $this->assertCount(4, $tags);
        $this->assertContains('product_123', $tags);
        $this->assertContains('product_456', $tags);
        $this->assertContains('product_789', $tags);
        $this->assertContains('product_159', $tags);
    }
}
