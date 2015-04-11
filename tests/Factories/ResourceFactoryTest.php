<?php namespace Peakfijn\GetSomeRest\Tests\Factories;

use Mockery;
use ReflectionException;
use RuntimeException;
use Peakfijn\GetSomeRest\Contracts\Factories\Factory as FactoryContract;
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
            ->with('resource')
            ->andReturn('resource');

        $mock->shouldReceive('camel')
            ->with('somename')
            ->andReturn('somename');

        $mock->shouldReceive('camel')
            ->with('some-other')
            ->andReturn('someOther');

        $mock->shouldReceive('camel')
            ->with('oops-allcap')
            ->andReturn('oopsAllcap');

        $mock->shouldReceive('camel')
            ->with('non-existent')
            ->andReturn('nonExistent');

        $mock->shouldReceive('singular')
            ->with('resources')
            ->andReturn('resource');

        $mock->shouldReceive('singular')
            ->with('Resource')
            ->andReturn('resource');

        $mock->shouldReceive('singular')
            ->with('somename')
            ->andReturn('somename');

        $mock->shouldReceive('singular')
            ->with('SomeOther')
            ->andReturn('SomeOther');

        $mock->shouldReceive('singular')
            ->with('someOther')
            ->andReturn('someOther');

        $mock->shouldReceive('singular')
            ->with('someother')
            ->andReturn('someother');

        $mock->shouldReceive('singular')
            ->with('OOPS-ALLCAPS')
            ->andReturn('OOPS-ALLCAP');

        $mock->shouldReceive('singular')
            ->with('non-existent')
            ->andReturn('non-existent');

        $mock->shouldReceive('snake')
            ->with('Resource')
            ->andReturn('resource');

        $mock->shouldReceive('snake')
            ->with('somename')
            ->andReturn('somename');

        $mock->shouldReceive('snake')
            ->with('SomeOther')
            ->andReturn('Some-Other');

        $mock->shouldReceive('snake')
            ->with('someOther')
            ->andReturn('some-other');

        $mock->shouldReceive('snake')
            ->with('OOPS-ALLCAP')
            ->andReturn('OOPS-ALLCAP');

        $mock->shouldReceive('snake')
            ->with('non-existent')
            ->andReturn('non-Existent');

        return $mock;
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
     * Register some instances to the provided factory.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\Factory $factory
     * @return \Peakfijn\GetSomeRest\Contracts\Factories\Factory
     */
    protected function registerInstances(FactoryContract $factory)
    {
        $factory->register('somename', 'Peakfijn\GetSomeRest\Tests\Stubs\Resource');
        $factory->register('SomeOther', 'Peakfijn\GetSomeRest\Tests\Stubs\OtherResource');
        $factory->register('OOPS-ALLCAPS','Peakfijn\GetSomeRest\Tests\Stubs\SomeResource');

        return $factory;
    }

    public function testDefaultsSetsDefaultInstanceFromStorage()
    {
        // different behaviour
    }

    public function testMakeReturnsDefaultWhenNothingWasFound()
    {
        // different behaviour
    }

    public function testRegisterStoresValueByName()
    {
        $factory = $this->getMockedInstance();

        $factory->shouldReceive('getClassName')
            ->with('somename')
            ->once()
            ->andReturn('Somename');

        $factory->shouldReceive('getClassName')
            ->with('SomeOther')
            ->once()
            ->andReturn('SomeOther');

        $factory->shouldReceive('getClassName')
            ->with('OOPS-ALLCAPS')
            ->once()
            ->andReturn('OopsAllcap');

        $factory = $this->registerInstances($factory);
        $storage = $this->getProtectedProperty($factory, 'resources');

        $this->assertNotNull($storage['Somename']);
        $this->assertNotNull($storage['SomeOther']);
        $this->assertNotNull($storage['OopsAllcap']);
    }

    public function testMakeThrowsResourceUnknownExceptionWhenNothingWasFound()
    {
        $factory = $this->getMockedInstance();
        $anatomy = Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Rest\Anatomy')
                    ->shouldReceive('getResourceName')->andReturnNull();

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

        try {
            $factory->make($anatomy);
        } catch (ResourceUnknownException $e) {
            return;
        }

        $this->fail('The resource factory didn\'t throw an error when nothing was found.');
    }

    public function testMakeAcceptsAnatomyAndUsesResourceName()
    {
        $anatomy = $this->getMockedAnatomy();
        $container = $this->getMockedContainer();
        $factory = $this->getMockedInstance(null, $container);

        $anatomy->shouldReceive('getResourceName')
            ->once()
            ->andReturn('resource');

        $factory->shouldReceive('contains')
            ->with('resource')
            ->once()
            ->andReturn(false);

        $factory->shouldReceive('resolve')
            ->with('resource')
            ->once()
            ->andReturn(true);

        $factory->shouldReceive('getClassName')
            ->with('resource')
            ->once()
            ->andReturn('Resource');

        $this->setProtectedProperty($factory, 'resources', ['Resource' => 'Resource']);

        $container->shouldReceive('make')
            ->with('Resource')
            ->once();

        $factory->make($anatomy);
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
        $container = $this->getMockedContainer();
        $factory = $this->getMockedInstance();

        $factory->shouldReceive('getClassName')
            ->with('resources')
            ->once()
            ->andReturn('Resource');

        $factory->shouldReceive('getClassName')
            ->with('OOPS-ALLCAPS')
            ->once()
            ->andReturn('OopsAllcap');

        $factory->shouldReceive('register')
            ->with('Resource', Mockery::type('object'))
            ->once();

        $factory->shouldReceive('register')
            ->with('OopsAllcap', Mockery::type('object'))
            ->never();

        $shouldBeTrue = $this->callProtectedMethod($factory, 'resolve', ['resources']);
        $shouldBeFalse = $this->callProtectedMethod($factory, 'resolve', ['OOPS-ALLCAPS']);

        $this->assertTrue($shouldBeTrue);
        $this->assertFalse($shouldBeFalse);
    }

    public function testGetClassNameReturnsValidClassName()
    {
        $factory = $this->getMockedInstance()
            ->shouldAllowMockingProtectedMethods();

        $factory->shouldReceive('getSingular')
            ->with('test-items')
            ->once()
            ->andReturn('test-item');

        $factory->shouldReceive('getCamelCase')
            ->with('test-item')
            ->once()
            ->andReturn('TestItem');

        $this->assertEquals('TestItem', $factory->getClassName('test-items'));
    }

    public function testGetMethodNameReturnsValidMethodName()
    {
        $factory = $this->getMockedInstance()
            ->shouldAllowMockingProtectedMethods();

        $factory->shouldReceive('getPlural')
            ->with('test-item')
            ->once()
            ->andReturn('test-items');

        $factory->shouldReceive('getCamelCase')
            ->with('test-items')
            ->once()
            ->andReturn('testItems');

        $this->assertEquals('testItems', $factory->getMethodName('test-item'));
    }

    public function testGetSingularReturnsSingularEquivalent()
    {
        $str = $this->getMockedStr();
        $factory = $this->getInstance(null, null, $str);

        $str->shouldReceive('singular')
            ->with('tests')
            ->once()
            ->andReturn('test');

        $this->assertEquals('test', $this->callProtectedMethod($factory, 'getSingular', ['tests']));
    }

    public function testGetPluralReturnsPluralEquivalent()
    {
        $str = $this->getMockedStr();
        $factory = $this->getInstance(null, null, $str);

        $str->shouldReceive('plural')
            ->with('test')
            ->once()
            ->andReturn('tests');

        $this->assertEquals('tests', $this->callProtectedMethod($factory, 'getPlural', ['test']));
    }
}
