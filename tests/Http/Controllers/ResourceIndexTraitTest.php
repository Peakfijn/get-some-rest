<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Peakfijn\GetSomeRest\Tests\Stubs\Controllers\IndexController;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;

class ResourceIndexTraitTest extends ResourceTraitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Tests\Stubs\Controllers\IndexController
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
        $selector = $this->getMockedSelector();
        $operator = $this->getMockedOperator();

        $instance->shouldReceive('indexResource')
            ->with($anatomy, $resources, $selector, $operator)
            ->once()
            ->andReturn('result');

        $this->assertEquals('result', $instance->index($anatomy, $resources, $selector, $operator));
    }

    public function testIndexResourceThrowsExceptionWhenResourceWasNotFound()
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
            $this->callProtectedMethod($instance, 'indexResource', [$anatomy, $resources, $selector, $operator]);
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
        $selector = $this->getMockedSelector();
        $operator = $this->getMockedOperator();
        $model = $this->getMockedEloquent();

        $resources->shouldReceive('make')
            ->with($anatomy)
            ->once()
            ->andReturn($model);

        $selector->shouldReceive('filter')
            ->with($model)
            ->once()
            ->andReturn($model);

        $operator->shouldReceive('execute')
            ->with($model)
            ->once()
            ->andReturn($model);

        $model->shouldReceive('get')
            ->once()
            ->andReturn($model);

        $result = $this->callProtectedMethod($instance, 'indexResource', [$anatomy, $resources, $selector, $operator]);
        $this->assertEquals($model, $result);
    }
}
