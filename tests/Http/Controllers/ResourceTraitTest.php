<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Mockery;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

abstract class ResourceTraitTest extends AbstractUnitTest
{
    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedInstance()
    {
        $instance = $this->getInstance();

        return Mockery::mock(get_class($instance))
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }

    /**
     * Get a mocked anatomy for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedAnatomy()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Rest\Anatomy');
    }

    /**
     * Get a mocked resource factory for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedResourceFactory()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory');
    }

    /**
     * Get a mocked request for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedRequest()
    {
        return Mockery::mock('\Illuminate\Http\Request');
    }

    /**
     * Get a mocked eloquent model for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedEloquent()
    {
        return Mockery::mock('\Illuminate\Database\Eloquent\Model');
    }

    /**
     * Get a mocked selector for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedSelector()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Rest\Selector');
    }

    /**
     * Get a mocked operator for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedOperator()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Rest\Operator');
    }
}
