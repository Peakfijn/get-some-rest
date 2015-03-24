<?php namespace Peakfijn\GetSomeRest\Tests\Http\Url\Resolvers;

use Mockery;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;
use Peakfijn\GetSomeRest\Http\Url\Url;

//
// CONTENTS
//
// testResourceClassReadsAndReturnsProperty
// testResourceIdReadsAndReturnsProperty
// testParseExtractsAndSavesResource
// testParseExtractsResourceAndIdentifier
//

class UrlTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @param  array                                                     $segments
     * @oaram  \Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver $resourceResolver
     * @return \Peakfijn\GetSomeRest\Http\Url\Url
     */
    protected function getInstance(array $segments = null, $resourceResolver = null)
    {
        if (!$segments) {
            $segments = ['api', 'v1', 'resources', '9'];
        }

        if (!$resourceResolver) {
            $resourceResolver = $this->getMockedResourceResolverInstance();
        }

        return new Url($segments, $resourceResolver);
    }

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @param  array                                                     $segments
     * @oaram  \Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver $resourceResolver
     * @return \Mockery\Mock
     */
    protected function getMockedInstance(array $segments = null, $resourceResolver = null)
    {
        if (!$segments) {
            $segments = ['api', 'v1', 'resources', '9'];
        }

        if (!$resourceResolver) {
            $resourceResolver = $this->getMockedResourceResolverInstance();
        }

        return parent::getMockedInstance($segments, $resourceResolver);
    }

    /**
     * Get a mocked resource resolver instance.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedResourceResolverInstance()
    {
        $mock = Mockery::mock(
            'Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver',
            [ 'Peakfijn\GetSomeRest\Tests\Stubs' ]
        );

        return $mock->makePartial();
    }

    public function testResourceClassReadsAndReturnsProperty()
    {
        $instance = $this->getInstance();

        $this->setInternalProperty(
            $instance,
            'resourceClass',
            'Peakfijn\GetSomeRest\Tests\Stubs\Resource'
        );

        $this->assertEquals(
            'Peakfijn\GetSomeRest\Tests\Stubs\Resource',
            $instance->resourceClass()
        );
    }

    public function testResourceIdReadsAndReturnsProperty()
    {
        $instance = $this->getInstance();
        $this->setInternalProperty($instance, 'resourceId', '1337');
        $this->assertEquals('1337', $instance->resourceId());
    }

    public function testParseExtractsAndSavesResource()
    {
        $resolver = $this->getMockedResourceResolverInstance();

        $resolver->shouldReceive('resolve')
            ->with('api')
            ->once()
            ->andReturn(null);

        $resolver->shouldReceive('resolve')
            ->with('v1')
            ->once()
            ->andReturn(null);

        $resolver->shouldReceive('resolve')
            ->with('resources')
            ->atLeast()->once()
            ->andReturn('Peakfijn\GetSomeRest\Tests\Stubs\Resource');

        $instance = $this->getInstance(['api', 'v1', 'resources'], $resolver);
        $resource = $this->getInternalProperty($instance, 'resourceClass');

        $this->assertEquals('Peakfijn\GetSomeRest\Tests\Stubs\Resource', $resource);
    }

    public function testParseExtractsResourceAndIdentifier()
    {
        $resolver = $this->getMockedResourceResolverInstance();

        $resolver->shouldReceive('resolve')
            ->with('api')
            ->once()
            ->andReturn(null);

        $resolver->shouldReceive('resolve')
            ->with('v1')
            ->once()
            ->andReturn(null);

        $resolver->shouldReceive('resolve')
            ->with('resources')
            ->atLeast()->once()
            ->andReturn('Peakfijn\GetSomeRest\Tests\Stubs\Resource');

        $resolver->shouldReceive('resolve')
            ->with('9')
            ->never();

        $instance = $this->getInstance(['api', 'v1', 'resources', '9'], $resolver);
        $resource = $this->getInternalProperty($instance, 'resourceClass');
        $id       = $this->getInternalProperty($instance, 'resourceId');

        $this->assertEquals('Peakfijn\GetSomeRest\Tests\Stubs\Resource', $resource);
        $this->assertEquals('9', $id);
    }
}
