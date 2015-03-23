<?php namespace Peakfijn\GetSomeRest\Tests;

use Mockery;

/**
 * The Unit test class helps retrieving instances of the targeted class.
 * It also creates partial mocks on the fly.
 *
 * @author Cedric van Putten <me@bycedric.com>
 */
abstract class AbstractUnitTest extends AbstractTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return mixed
     */
    abstract protected function getInstance();

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedInstance()
    {
        $class = get_class($this->getInstance());

        return Mockery::mock($class, func_get_args())
            ->makePartial();
    }

    /**
     * Check if the getInstance method returns an object instance.
     * Note, this is only to validate the test itself is working as expected.
     *
     * @return void
     */
    public function testClassIsInstantiable()
    {
        $this->assertInternalType('object', $this->getInstance());
    }

    /**
     * Check if the getMockedInstance method returns a mocked object instance.
     * Note, this is only to validate the test itself is working as expected.
     *
     * @return void
     */
    public function testMockedClassIsInstantiable()
    {
        $instance = $this->getInstance();
        $mock = $this->getMockedInstance();

        $this->assertInstanceOf(get_class($instance), $mock);
    }
}
