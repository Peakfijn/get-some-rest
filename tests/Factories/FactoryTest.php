<?php namespace Peakfijn\GetSomeRest\Tests\Factories;

use StdClass;
use Peakfijn\GetSomeRest\Contracts\Factory as FactoryContract;
use Peakfijn\GetSomeRest\Factories\Factory;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

class FactoryTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Factories\Factory
     */
    protected function getInstance()
    {
        return new Factory();
    }

    /**
     * Register some instances to the provided factory.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Factory $factory
     * @return \Peakfijn\GetSomeRest\Contracts\Factory
     */
    protected function registerInstances(FactoryContract $factory)
    {
        $factory->register('somename', new StdClass());
        $factory->register('SomeOther', new StdClass());
        $factory->register('OOPS-ALLCAPS', new StdClass());

        return $factory;
    }

    public function testRegisterStoresValueByName()
    {
        $factory = $this->getInstance();
        $factory = $this->registerInstances($factory);

        $storage = $this->getProtectedProperty($factory, 'instances');

        $this->assertNotNull($storage['somename']);
        $this->assertNotNull($storage['someother']);
        $this->assertNotNull($storage['oops-allcaps']);
    }

    public function testContainsReadsInstances()
    {
        $factory = $this->getInstance();
        $factory = $this->registerInstances($factory);

        $this->assertTrue($factory->contains('somename'));
        $this->assertTrue($factory->contains('someOther'));
        $this->assertTrue($factory->contains('OOPS-ALLCAPS'));
        $this->assertFalse($factory->contains('non-existent'));
    }

    public function testDefaultsSetsDefaultInstanceFromStorage()
    {
        $factory = $this->getInstance();
        $factory = $this->registerInstances($factory);

        $this->assertNotNull($factory->defaults('somename'));
        $this->assertNotNull($factory->defaults('someOther'));
        $this->assertNull($factory->defaults('non-existent'));
    }

    public function testMakeReturnsRegisteredValueByName()
    {
        $factory = $this->getInstance();
        $factory = $this->registerInstances($factory);

        $this->assertNotNull($factory->make('somename'));
        $this->assertNotNull($factory->make('someOther'));
        $this->assertNotNull($factory->make('OOPS-ALLCAPS'));
    }

    public function testMakeReturnsDefaultWhenNothingWasFound()
    {
        $factory = $this->getInstance();
        $factory = $this->registerInstances($factory);

        $this->setProtectedProperty($factory, 'defaults', 'default-value');

        $this->assertEquals('default-value', $factory->make('non-existent'));
    }
}
