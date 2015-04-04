<?php namespace Peakfijn\GetSomeRest\Tests\Factories;

use Mockery;
use ReflectionException;
use RuntimeException;
use Peakfijn\GetSomeRest\Contracts\Factory as FactoryContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;
use Peakfijn\GetSomeRest\Factories\ResourceFactory;

class ResourceFactoryTest extends FactoryTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @param  string                                    $namespace (default: null)
     * @param  \Illuminate\Contracts\Container\Container $container (default: null)
     * @param  \Illuminate\Support\Str                   $str       (default: null)
     * @return \Peakfijn\GetSomeRest\Factories\ResourceFactory
     */
    protected function getInstance(
        $namespace = null,
        $container = null,
        $str = null
    ) {
        if ($namespace == null) {
            $namespace = 'Peakfijn\GetSomeRest\Tests\Stubs';
        }

        if ($container == null) {
            $container = $this->getMockedContainer();
        }

        if ($str == null) {
            $str = $this->getMockedStr();
        }

        return new ResourceFactory($container, $str, $namespace);
    }

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @param  string                                    $namespace (default: null)
     * @param  \Illuminate\Contracts\Container\Container $container (default: null)
     * @param  \Illuminate\Support\Str                   $str       (default: null)
     * @return \Mockery\Mock
     */
    protected function getMockedInstance(
        $namespace = null,
        $container = null,
        $str = null
    ) {
        if ($namespace == null) {
            $namespace = 'Peakfijn\GetSomeRest\Tests\Stubs';
        }

        if ($container == null) {
            $container = $this->getMockedContainer();
        }

        if ($str == null) {
            $str = $this->getMockedStr();
        }

        return parent::getMockedInstance($container, $str, $namespace);
    }

    /**
     * Get a mocked container for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedContainer()
    {
        $mock = Mockery::mock('\Illuminate\Contracts\Container\Container');

        $mock->shouldReceive('make')
            ->with('Peakfijn\GetSomeRest\Tests\Stubs\Resource')
            ->andReturn(new \Peakfijn\GetSomeRest\Tests\Stubs\Resource);

        $mock->shouldReceive('make')
            ->with('Peakfijn\GetSomeRest\Tests\Stubs\OtherResource')
            ->andReturn(new \Peakfijn\GetSomeRest\Tests\Stubs\OtherResource);

        $mock->shouldReceive('make')
            ->with('Peakfijn\GetSomeRest\Tests\Stubs\SomeResource')
            ->andReturn(new \Peakfijn\GetSomeRest\Tests\Stubs\SomeResource);

        $mock->shouldReceive('make')
            ->with('Peakfijn\GetSomeRest\Tests\Stubs\OopsAllcap')
            ->andThrow(new ReflectionException());

        return $mock;
    }

    /**
     * Get a mocked str helper for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedStr()
    {
        $mock = Mockery::mock('\Illuminate\Support\Str');

        $mock->shouldReceive('camel')
            ->with('resources')
            ->andReturn('resources');

        $mock->shouldReceive('camel')
            ->with('resource')
            ->andReturn('resource');

        $mock->shouldReceive('singular')
            ->with('resources')
            ->andReturn('resource');

        $mock->shouldReceive('singular')
            ->with('resource')
            ->andReturn('resource');

        $mock->shouldReceive('camel')
            ->with('somename')
            ->andReturn('somename');

        $mock->shouldReceive('singular')
            ->with('somename')
            ->andReturn('somename');

        $mock->shouldReceive('camel')
            ->with('someother')
            ->andReturn('someother');

        $mock->shouldReceive('singular')
            ->with('someother')
            ->andReturn('someother');

        $mock->shouldReceive('camel')
            ->with('oops-allcaps')
            ->andReturn('oopsAllcaps');

        $mock->shouldReceive('singular')
            ->with('oopsAllcaps')
            ->andReturn('OopsAllcap');

        $mock->shouldReceive('camel')
            ->with('non-existent')
            ->andReturn('nonExistent');

        $mock->shouldReceive('singular')
            ->with('nonExistent')
            ->andReturn('nonExistent');

        return $mock;
    }

    /**
     * Register some instances to the provided factory.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Factory $factory
     * @return \Peakfijn\GetSomeRest\Contracts\Factory
     */
    protected function registerInstances(FactoryContract $factory)
    {
        $factory->register('somename', 'Peakfijn\GetSomeRest\Tests\Stubs\Resource');
        $factory->register('SomeOther', 'Peakfijn\GetSomeRest\Tests\Stubs\OtherResource');
        $factory->register('OOPS-ALLCAPS','Peakfijn\GetSomeRest\Tests\Stubs\SomeResource');

        return $factory;
    }

    public function testRegisterStoresValueByName()
    {
        $factory = $this->getInstance();
        $factory = $this->registerInstances($factory);

        $storage = $this->getProtectedProperty($factory, 'resources');

        $this->assertNotNull($storage['Somename']);
        $this->assertNotNull($storage['Someother']);
        $this->assertNotNull($storage['OopsAllcap']);
    }

    public function testDefaultsSetsDefaultInstanceFromStorage()
    {
        // different behaviour
    }

    public function testMakeReturnsDefaultWhenNothingWasFound()
    {
        // different behaviour
    }

    public function testMakeThrowsResourceUnknownExceptionWhenNothingWasFound()
    {
        $factory = $this->getMockedInstance()
            ->shouldAllowMockingProtectedMethods();

        $factory->shouldReceive('contains')
            ->with('test')
            ->andReturn(false);

        $factory->shouldReceive('resolve')
            ->with('test')
            ->andReturn(false);

        try {
            $factory->make('test');
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('The resource factory didn\'t throw an error when nothing was found.');
    }

    public function testDefaultsThrowsException()
    {
        $factory = $this->getInstance();

        try {
            $factory->defaults('should-work');
        } catch (RuntimeException $e) {
            return;
        }

        $this->fail('The resource factory allowed the use of defaults.');
    }

    public function testResolveRegistersClassWhenFound()
    {
        $factory = $this->getInstance();

        $shouldBeTrue = $this->callProtectedMethod($factory, 'resolve', ['resources']);
        $shouldBeFalse = $this->callProtectedMethod($factory, 'resolve', ['OOPS-ALLCAPS']);

        $resources = $this->getProtectedProperty($factory, 'resources');

        $this->assertArrayHasKey('Resource', $resources);
        $this->assertTrue($shouldBeTrue);
        $this->assertFalse($shouldBeFalse);
    }

    public function testGetClassNameReturnsValidClassName()
    {
        $str = $this->getMockedStr();

        $str->shouldReceive('camel')
            ->with('totally-not-valid')
            ->once()
            ->andReturn('totallyNotValid');

        $str->shouldReceive('singular')
            ->with('totallyNotValid')
            ->once()
            ->andReturn('totallyNotValid');

        $factory = $this->getInstance(null, null, $str);
        $class = $this->callProtectedMethod(
            $factory,
            'getClassName',
            ['Totally-Not-VALID']
        );

        $this->assertEquals('TotallyNotValid', $class);
    }
}
