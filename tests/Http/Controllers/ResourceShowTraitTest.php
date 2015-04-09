<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\ShowController;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;

class ResourceShowTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Tests\Stubs\Controllers\ShowController
     */
    protected function getInstance()
    {
        return new ShowController();
    }

    public function testShowCallsAndReturnsShowResourceMethod()
    {
        $instance = $this->getMockedInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $operator = $this->getMockedOperator();

        $instance->shouldReceive('showResource')
            ->with($anatomy, $resources, $operator)
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->show($anatomy, $resources, $operator));
    }

    public function testShowResourceThrowsExceptionWhenResourceWasNotFound()
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
            $this->callProtectedMethod($instance, 'showResource', [$anatomy, $resources, $operator]);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('Resource show trait did not throw an exception when unknown resource was requested.');
    }

    public function testShowResourceThrowsExceptionWhenResourceWithIdWasNotFound()
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

        $operator->shouldReceive('execute')
            ->with($model)
            ->once()
            ->andReturn($model);

        $model->shouldReceive('findOrFail')
            ->with(123)
            ->once()
            ->andThrow(new ModelNotFoundException());

        try {
            $this->callProtectedMethod($instance, 'showResource', [$anatomy, $resources, $operator]);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('Resource show trait did not throw an exception when unable to find resource by id.');
    }

    public function testShowResourceReturnsResourceWhenSucceeded()
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
            ->andReturn($model);

        $operator->shouldReceive('execute')
            ->with($model)
            ->once()
            ->andReturn($model);

        $result = $this->callProtectedMethod($instance, 'showResource', [$anatomy, $resources, $operator]);
        $this->assertEquals($model, $result);
    }
}
