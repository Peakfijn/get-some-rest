<?php namespace Peakfijn\GetSomeRest\Tests\Http\Url\Resolvers;

use Mockery;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;
use Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver;


//
// CONTENTS
//
// testGetNamespaceReturnsString
// testGetAliasesReturnsArray
// testGetClassNameFromStringReturnsAValidClassName
// testIsAliasReturnsCorrectBoolean
// testGetAliasReturnsAliasClassName
// testIsClassReturnsCorrectBoolean
// testGetClassReturnsClassName
// testResolveCallsCorrectMethods

class ResourceResolverTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @param  string $namespace
     * @param  array  $aliases
     * @return \Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver
     */
    protected function getInstance($namespace = null, array $aliases = null)
    {
        if (!$namespace) {
            $namespace = 'Peakfijn\GetSomeRest\Tests\Stubs';
        }

        return new ResourceResolver($namespace, (array) $aliases);
    }

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @param  string $namespace
     * @param  array  $aliases
     * @return \Mockery\Mock
     */
    protected function getMockedInstance($namespace = null, array $aliases = null)
    {
        if (!$namespace) {
            $namespace = 'Peakfijn\GetSomeRest\Tests\Stubs';
        }

        return parent::getMockedInstance($namespace, (array) $aliases);
    }

    public function testGetNamespaceReturnsString()
    {
        $instance = $this->getInstance();
        $namespace = $instance->getNamespace();

        $this->assertNotEmpty($namespace);
        $this->assertInternalType('string', $namespace);
    }

    public function testGetAliasesReturnsArray()
    {
        $instance = $this->getInstance();

        $this->assertInternalType('array', $instance->getAliases());
    }

    public function testGetClassNameFromStringReturnsAValidClassName()
    {
        $instance = $this->getInstance();
        $classes = [
            'resources'    => 'Resource',
            'sub-resource' => 'SubResource',
            'I_Am-drunk'   => 'IAmDrunk'
        ];

        foreach ($classes as $bad => $good) {
            $result = $this->callInternalMethod($instance, 'getClassNameFromString', [$bad]);

            $this->assertEquals($good, $result);
        }
    }

    public function testIsAliasReturnsCorrectBoolean()
    {
        $aliases = [
            'hes-drunk' => '\Some\Namespace\Class',
            'nope-im_not' => '\Other\Namespace\Class'
        ];

        $instance = $this->getInstance(null, $aliases);

        $this->assertTrue($instance->isAlias('hes-drunk'));
        $this->assertTrue($instance->isAlias('nope-im_not'));
        $this->assertFalse($instance->isAlias('what?'));
    }

    public function testGetAliasReturnsAliasClassName()
    {
        $aliases = [
            'FirstClass' => 'First\Class',
            'OtherClass' => 'Other\Class'
        ];

        $instance = $this->getInstance(null, $aliases);

        foreach ($aliases as $alias => $class)
        {
            $result = $instance->getAlias($alias);

            $this->assertEquals($class, $result);
        }

        $this->assertNull($instance->getAlias('non-existent'));
    }

    public function testIsClassReturnsCorrectBoolean()
    {
        $instance = $this->getInstance();

        $this->assertTrue($instance->isClass('Resource'));
        $this->assertTrue($instance->isClass('OtherResource'));
        $this->assertFalse($instance->isClass('NopeDoesNotExists'));
    }

    public function testGetClassReturnsClassName()
    {
        $classes = [
            'Resource' => 'Peakfijn\GetSomeRest\Tests\Stubs\Resource',
            'OtherResource' => 'Peakfijn\GetSomeRest\Tests\Stubs\OtherResource'
        ];

        $instance = $this->getInstance();

        foreach ($classes as $base => $class) {
            $result = $instance->getClass($base);

            $this->assertEquals($class, $result);
        }

        $this->assertNull($instance->getClass('NopeNonExistent'));
    }

    public function testResolveCallsCorrectMethods()
    {
        $mock = $this->getMockedInstance();

        $mock->shouldReceive('getAlias')
            ->once()
            ->with('resource')
            ->andReturn(null);

        $mock->shouldReceive('getAlias')
            ->once()
            ->with('alias-resource')
            ->andReturn('Peakfijn\GetSomeRest\Tests\Stubs\OtherResource');

        $mock->shouldReceive('getAlias')
            ->once()
            ->with('non-existent')
            ->andReturn(null);

        $mock->shouldReceive('getClass')
            ->once()
            ->with('Resource')
            ->andReturn('Peakfijn\GetSomeRest\Tests\Stubs\Resource');

        $mock->shouldReceive('getClass')
            ->once()
            ->with('NonExistent')
            ->andReturn(null);

        $this->assertEquals('Peakfijn\GetSomeRest\Tests\Stubs\Resource', $mock->resolve('resource'));
        $this->assertEquals('Peakfijn\GetSomeRest\Tests\Stubs\OtherResource', $mock->resolve('alias-resource'));
        $this->assertNull($mock->resolve('non-existent'));
    }
}
