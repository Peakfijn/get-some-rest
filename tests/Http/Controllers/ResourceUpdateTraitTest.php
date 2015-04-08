<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\UpdateController;
use Peakfijn\GetSomeRest\Exceptions\ResourceSaveException;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;

class ResourceUpdateTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return mixed
     */
    protected function getInstance()
    {
        return new UpdateController();
    }

    public function testUpdateCallsAndReturnsUpdateResourceMethod()
    {
        $instance = $this->getMockedInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $request = $this->getMockedRequest();

        $request->shouldReceive('input')
            ->once()
            ->andReturn(['some' => 'input']);

        $instance->shouldReceive('updateResource')
            ->with($anatomy, $resources, ['some' => 'input'])
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->update($anatomy, $resources, $request));
    }

    public function testUpdateResourceThrowsExceptionWhenResourceWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andThrow(new ResourceUnknownException());

        try {
            $this->callProtectedMethod($instance, 'updateResource', [$anatomy, $resources, ['some' => 'input']]);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('Resource update trait did not throw an exception when unknown resource was requested.');
    }

    public function testUpdateResourceThrowsExceptionWhenResourceWithIdWasNotFound()
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
            $this->callProtectedMethod($instance, 'updateResource', [$anatomy, $resources]);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('Resource update trait did not throw an exception when unable to find resource by id.');
    }

    public function testUpdateResourceThrowsExceptionWhenUnableToDelete()
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

        $model->shouldReceive('fill')
            ->with(['some' => 'input'])
            ->once();

        $model->shouldReceive('save')
            ->once()
            ->andReturn(false);

        try {
            $this->callProtectedMethod($instance, 'updateResource', [$anatomy, $resources, ['some' => 'input']]);
        } catch (ResourceSaveException $e) {
            return;
        }

        $this->fail('Resource update trait did not throw an exception when unable to save.');
    }

    public function testUpdateResourceReturnsResourceWhenSucceeded()
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

        $model->shouldReceive('fill')
            ->with(['some' => 'input'])
            ->once();

        $model->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $result = $this->callProtectedMethod($instance, 'updateResource', [$anatomy, $resources, ['some' => 'input']]);
        $this->assertEquals($model, $result);
    }
}
