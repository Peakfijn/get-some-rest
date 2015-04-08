<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\IndexController;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;

class ResourceIndexTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return mixed
     */
    protected function getInstance()
    {
        return new IndexController();
    }

    public function testIndexCallsAndReturnsIndexResourceMethod()
    {
        $instance = $this->getMockedInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();

        $instance->shouldReceive('indexResource')
            ->with($anatomy, $resources)
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->index($anatomy, $resources));
    }

    public function testIndexResourceThrowsExceptionWhenResourceWasNotFound()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andThrow(new ResourceUnknownException());

        try {
            $this->callProtectedMethod($instance, 'indexResource', [$anatomy, $resources]);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('Resource index trait did not throw an exception when unknown resource was requested.');
    }

    public function testIndexResourceReturnsResourceWhenSucceeded()
    {
        $instance = $this->getInstance();
        $anatomy = $this->getMockedAnatomy();
        $resources = $this->getMockedResourceFactory();
        $model = $this->getMockedEloquent();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andReturn($model);

        $model->shouldReceive('get')
            ->once()
            ->andReturn($model);

        $result = $this->callProtectedMethod($instance, 'indexResource', [$anatomy, $resources]);
        $this->assertEquals($model, $result);
    }
}
