<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\DestroyController;
use Peakfijn\GetSomeRest\Exceptions\ResourceDestroyException;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;

class ResourceDestroyTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return mixed
     */
    protected function getInstance()
    {
        return new DestroyController();
    }

    public function testDestroyCallsAndReturnsDestroyResourceMethod()
    {
        $instance = $this->getMockedInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();

        $instance->shouldReceive('destroyResource')
            ->with($anatomy, $resources)
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->destroy($anatomy, $resources));
    }

    public function testDestroyResourceThrowsExceptionWhenResourceWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andThrow(new ResourceUnknownException());

        try {
            $this->callProtectedMethod($instance, 'destroyResource', [$anatomy, $resources]);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('Resource destroy trait did not throw an exception when unknown resource was requested.');
    }

    public function testDestroyResourceThrowsExceptionWhenResourceWithIdWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
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
            $this->callProtectedMethod($instance, 'destroyResource', [$anatomy, $resources]);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('Resource destroy trait did not throw an exception when unable to find resource by id.');
    }

    public function testDestroyResourceThrowsExceptionWhenUnableToDelete()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
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

        $model->shouldReceive('delete')
            ->once()
            ->andReturn(false);

        try {
            $this->callProtectedMethod($instance, 'destroyResource', [$anatomy, $resources]);
        } catch (ResourceDestroyException $e) {
            return;
        }

        $this->fail('Resource destroy trait did not throw an exception when unable to delete.');
    }

    public function testDestroyResourceReturnsResourceWhenSucceeded()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
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

        $model->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $result = $this->callProtectedMethod($instance, 'destroyResource', [$anatomy, $resources]);
        $this->assertEquals($model, $result);
    }
}
