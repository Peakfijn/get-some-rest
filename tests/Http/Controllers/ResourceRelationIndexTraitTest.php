<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\RelationIndexController;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;
use Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException;

class ResourceRelationIndexTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Tests\Stubs\Controllers\RelationIndexController
     */
    protected function getInstance()
    {
        return new RelationIndexController();
    }

    public function testRelationIndexCallsAndReturnsRelationIndexResourceMethod()
    {
        $instance = $this->getMockedInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $selector = $this->getMockedSelector();
        $operator = $this->getMockedOperator();

        $instance->shouldReceive('relationIndexResource')
            ->with($anatomy, $resources, $selector, $operator)
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->relationIndex($anatomy, $resources, $selector, $operator));
    }

    public function testRelationIndexResourceThrowsExceptionWhenResourceWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $selector = $this->getMockedSelector();
        $operator = $this->getMockedOperator();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andThrow(new ResourceUnknownException());

        try {
            $this->callProtectedMethod($instance, 'relationIndexResource', [$anatomy, $resources, $selector, $operator]);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('Resource relation index trait did not throw an exception when unknown resource was requested.');
    }

    public function testRelationIndexResourceThrowsExceptionWhenRelationMethodWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $selector = $this->getMockedSelector();
        $operator = $this->getMockedOperator();
        $model = $this->getMockedEloquent();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andThrow($model);

        $anatomy->shouldReceive('getResourceId')
            ->once()
            ->andReturn(123);

        $anatomy->shouldReceive('getRelationName')
            ->once()
            ->andReturn('thisShouldNeverExists');

        $model->shouldReceive('findOrFail')
            ->with(123)
            ->once()
            ->andReturn($model);

        try {
            $this->callProtectedMethod($instance, 'relationIndexResource', [$anatomy, $resources, $selector, $operator]);
        } catch (ResourceRelationUnknownException $e) {
            return;
        }

        $this->fail('Resource relation index trait did not throw an exception when unknown relation was requested.');
    }

    public function testRelationIndexResourceReturnsResourceWhenSucceeded()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $selector = $this->getMockedSelector();
        $operator = $this->getMockedOperator();
        $model = $this->getMockedEloquent();
        $realModel = new \Peakfijn\GetSomeRest\Tests\Stubs\Resource();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andReturn($model);

        $anatomy->shouldReceive('getResourceId')
            ->once()
            ->andReturn(123);

        $anatomy->shouldReceive('getRelationName')
            ->once()
            ->andReturn('someResources');

        $model->shouldReceive('findOrFail')
            ->with(123)
            ->once()
            ->andReturn($realModel);

        $selector->shouldReceive('filter')
            ->with($realModel)
            ->once()
            ->andReturn($realModel);

        $operator->shouldReceive('execute')
            ->with($realModel)
            ->once()
            ->andReturn($realModel);

        $result = $this->callProtectedMethod($instance, 'relationIndexResource', [$anatomy, $resources, $selector, $operator]);
        $this->assertEquals($realModel, $result);
    }

    public function testRelationIndexResourceThrowsExceptionWhenResourceWithIdWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $selector = $this->getMockedSelector();
        $operator = $this->getMockedOperator();
        $model = $this->getMockedEloquent();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andReturn($model);

        $anatomy->shouldReceive('getResourceId')
            ->once()
            ->andReturn(123);

        $model->shouldReceive('findOrFail')
            ->with(123)
            ->once()
            ->andThrow(new ModelNotFoundException());

        try {
            $this->callProtectedMethod($instance, 'relationIndexResource', [$anatomy, $resources, $selector, $operator]);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('Resource relation index trait did not throw an exception when unable to find resource by id.');
    }
}
