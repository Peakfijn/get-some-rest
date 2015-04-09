<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\RelationShowController;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;
use Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException;

class ResourceRelationShowTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Tests\Stubs\Controllers\RelationShowController
     */
    protected function getInstance()
    {
        return new RelationShowController();
    }

    public function testRelationShowCallsAndReturnsRelationIndexResourceMethod()
    {
        $instance = $this->getMockedInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $operator = $this->getMockedOperator();

        $instance->shouldReceive('relationShowResource')
            ->with($anatomy, $resources, $operator)
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->relationShow($anatomy, $resources, $operator));
    }

    public function testRelationShowResourceThrowsExceptionWhenResourceWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $operator = $this->getMockedOperator();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andThrow(new ResourceUnknownException());

        try {
            $this->callProtectedMethod($instance, 'relationShowResource', [$anatomy, $resources, $operator]);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('Resource relation show trait did not throw an exception when unknown resource was requested.');
    }

    public function testRelationShowResourceThrowsExceptionWhenRelationMethodWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $operator = $this->getMockedOperator();
        $model = $this->getMockedEloquent();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andReturn($model);

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
            $this->callProtectedMethod($instance, 'relationShowResource', [$anatomy, $resources, $operator]);
        } catch (ResourceRelationUnknownException $e) {
            return;
        }

        $this->fail('Resource relation show trait did not throw an exception when unknown relation was requested.');
    }

    public function testRelationShowResourceThrowsExceptionWhenResourceWithIdWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
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
            $this->callProtectedMethod($instance, 'relationShowResource', [$anatomy, $resources, $operator]);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('Resource relation show trait did not throw an exception when unable to find resource by id.');
    }

    public function testRelationShowResourceTrhowsExceptionWhenRelationResourceWithIdWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
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

        $anatomy->shouldReceive('getRelationId')
            ->once()
            ->andReturn('fails');

        $model->shouldReceive('findOrFail')
            ->with(123)
            ->once()
            ->andReturn($realModel);

        $operator->shouldReceive('execute')
            ->with($realModel)
            ->once()
            ->andReturn($realModel);

        try {
            $this->callProtectedMethod($instance, 'relationShowResource', [$anatomy, $resources, $operator]);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('Resource relation show trait did not throw an exception when unable to find the related resource by id.');
    }

    public function testRelationShowResourceReturnsResourceWhenSucceeded()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
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

        $anatomy->shouldReceive('getRelationId')
            ->once()
            ->andReturn('abc');

        $model->shouldReceive('findOrFail')
            ->with(123)
            ->once()
            ->andReturn($realModel);

        $operator->shouldReceive('execute')
            ->with($realModel)
            ->once()
            ->andReturn($realModel);

        $result = $this->callProtectedMethod($instance, 'relationShowResource', [$anatomy, $resources, $operator]);
        $this->assertEquals($realModel, $result);
    }
}
