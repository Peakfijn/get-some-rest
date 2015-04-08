<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\StoreController;
use Peakfijn\GetSomeRest\Exceptions\ResourceSaveException;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;

class ResourceStoreTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return mixed
     */
    protected function getInstance()
    {
        return new StoreController();
    }

    public function testStoreCallsAndReturnsStoreResourceMethod()
    {
        $instance = $this->getMockedInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $request = $this->getMockedRequest();

        $request->shouldReceive('input')
            ->once()
            ->andReturn(['some' => 'input']);

        $instance->shouldReceive('storeResource')
            ->with($anatomy, $resources, ['some' => 'input'])
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->store($anatomy, $resources, $request));
    }

    public function testStoreResourceThrowsExceptionWhenResourceWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andThrow(new ResourceUnknownException());

        try {
            $this->callProtectedMethod($instance, 'storeResource', [$anatomy, $resources]);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('Resource store trait did not throw an exception when unknown resource was requested.');
    }

    public function testStoreResourceThrowsExceptionWhenResourceWasUnableToSave()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $model = $this->getMockedEloquent();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andReturn($model);

        $model->shouldReceive('fill')
            ->with(['some' => 'input'])
            ->once();

        $model->shouldReceive('save')
            ->once()
            ->andReturn(false);

        try {
            $this->callProtectedMethod($instance, 'storeResource', [$anatomy, $resources, ['some' => 'input']]);
        } catch (ResourceSaveException $e) {
            return;
        }

        $this->fail('Resource store trait did not throw an exception when unable to save.');
    }

    public function testStoreResourceReturnsResourceWhenSucceeded()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $model = $this->getMockedEloquent();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andReturn($model);

        $model->shouldReceive('fill')
            ->with(['some' => 'input'])
            ->once();

        $model->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $result = $this->callProtectedMethod($instance, 'storeResource', [$anatomy, $resources, ['some' => 'input']]);
        $this->assertEquals($model, $result);
    }
}
