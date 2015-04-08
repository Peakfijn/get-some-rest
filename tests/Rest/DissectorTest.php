<?php namespace Peakfijn\GetSomeRest\Tests\Rest;

use Mockery;
use Peakfijn\GetSomeRest\Rest\Dissector;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

class DissectorTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources (default: null)
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $anatomy (default: null)
     * @return \Peakfijn\GetSomeRest\Rest\Dissector
     */
    protected function getInstance($resources = null, $anatomy = null)
    {
        if (empty($resources)) {
            $resources = $this->getMockedResourceFactory();
        }

        if (empty($anatomy)) {
            $anatomy = $this->getMockedAnatomy();
        }

        return new Dissector($resources, $anatomy);
    }

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources (default: null)
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $anatomy (default: null)
     * @return \Mockery\Mock
     */
    protected function getMockedInstance($resources = null, $anatomy = null)
    {
        if (empty($resources)) {
            $resources = $this->getMockedResourceFactory();
        }

        if (empty($anatomy)) {
            $anatomy = $this->getMockedAnatomy();
        }

        return parent::getMockedInstance($resources, $anatomy);
    }

    /**
     * Get a mocked resource factory for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedResourceFactory()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\ResourceFactory');
    }

    /**
     * Get a mocked anatomy for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedAnatomy()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Anatomy');
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

    public function testIsValidResourceLooksInContainedFactoryAndPossibleResolving()
    {
        $resources = $this->getMockedResourceFactory();
        $dissector = $this->getInstance($resources);

        $resources->shouldReceive('contains')
            ->with('contained')
            ->andReturn(true);

        $resources->shouldReceive('contains')
            ->with('resolved')
            ->andReturn(false);

        $resources->shouldReceive('contains')
            ->with('none')
            ->andReturn(false);

        $resources->shouldReceive('resolve')
            ->with('contained')
            ->andReturn(false);

        $resources->shouldReceive('resolve')
            ->with('resolved')
            ->andReturn(true);

        $resources->shouldReceive('resolve')
            ->with('none')
            ->andReturn(false);

        $this->assertTrue($this->callProtectedMethod($dissector, 'isValidResource', ['contained']));
        $this->assertTrue($this->callProtectedMethod($dissector, 'isValidResource', ['resolved']));
        $this->assertFalse($this->callProtectedMethod($dissector, 'isValidResource', ['none']));
    }

    public function testGetMethodNameLooksInContainedFactory()
    {
        $resources = $this->getMockedResourceFactory();
        $dissector = $this->getInstance($resources);

        $resources->shouldReceive('getMethodName')
            ->with('testing-it')
            ->once()
            ->andReturn('testingIt');

        $result = $this->callProtectedMethod($dissector, 'getMethodName', ['testing-it']);
        $this->assertEquals('testingIt', $result);
    }

    public function testAnatomyIsReturnedWhenNothingWasFound()
    {
        $request = $this->getMockedRequest();
        $anatomy = $this->getMockedAnatomy();
        $dissector = $this->getMockedInstance(null, $anatomy);

        $request->shouldReceive('segments')
            ->andReturn([]);

        $this->assertEquals($anatomy, $dissector->anatomy($request));
    }

    public function testAnatomyOnlyUsesLastFourSegments()
    {
        $request = $this->getMockedRequest();
        $dissector = $this->getMockedInstance()
            ->shouldAllowMockingProtectedMethods();

        $request->shouldReceive('segments')
            ->andReturn(['not-used', 'resource', 'id', 'relation', 'subid']);

        $dissector->shouldReceive('isValidResource')
            ->with('not-used')
            ->never();

        $dissector->shouldReceive('isValidResource')
            ->with('resource')
            ->once()
            ->andReturn(false);

        $dissector->shouldReceive('isValidResource')
            ->with('id')
            ->once()
            ->andReturn(false);

        $dissector->shouldReceive('isValidResource')
            ->with('relation')
            ->once()
            ->andReturn(false);

        $dissector->shouldReceive('isValidResource')
            ->with('subid')
            ->once()
            ->andReturn(false);

        $dissector->anatomy($request);
    }

    public function testAnatomyOnlyGetsResourceName()
    {
        $request = $this->getMockedRequest();
        $anatomy = $this->getMockedAnatomy();
        $dissector = $this->getMockedInstance(null, $anatomy)
            ->shouldAllowMockingProtectedMethods();

        $request->shouldReceive('segments')
            ->andReturn(['invalid', 'resource']);

        $anatomy->shouldReceive('withResourceName')
            ->with('resource')
            ->once()
            ->andReturn($anatomy);

        $dissector->shouldReceive('isValidResource')
            ->with('invalid')
            ->once()
            ->andReturn(false);

        $dissector->shouldReceive('isValidResource')
            ->with('resource')
            ->once()
            ->andReturn(true);

        $this->assertInstanceOf(
            '\Peakfijn\GetSomeRest\Contracts\Anatomy',
            $dissector->anatomy($request)
        );
    }

    public function testAnatomyGetsResourceNameAndId()
    {
        $request = $this->getMockedRequest();
        $anatomy = $this->getMockedAnatomy();
        $dissector = $this->getMockedInstance(null, $anatomy)
            ->shouldAllowMockingProtectedMethods();

        $request->shouldReceive('segments')
            ->andReturn(['resource', '123']);

        $anatomy->shouldReceive('withResourceName')
            ->with('resource')
            ->once()
            ->andReturn($anatomy);

        $anatomy->shouldReceive('withResourceId')
            ->with('123')
            ->once()
            ->andReturn($anatomy);

        $dissector->shouldReceive('isValidResource')
            ->with('resource')
            ->once()
            ->andReturn(true);

        $this->assertInstanceOf(
            '\Peakfijn\GetSomeRest\Contracts\Anatomy',
            $dissector->anatomy($request)
        );
    }

    public function testAnatomyOnlyGetsResourceNameAndIdAndRelationName()
    {
        $request = $this->getMockedRequest();
        $anatomy = $this->getMockedAnatomy();
        $dissector = $this->getMockedInstance(null, $anatomy)
            ->shouldAllowMockingProtectedMethods();

        $request->shouldReceive('segments')
            ->andReturn(['resource', '123', 'relation']);

        $anatomy->shouldReceive('withResourceName')
            ->with('resource')
            ->once()
            ->andReturn($anatomy);

        $anatomy->shouldReceive('withResourceId')
            ->with('123')
            ->once()
            ->andReturn($anatomy);

        $anatomy->shouldReceive('withRelationName')
            ->with('relation')
            ->once()
            ->andReturn($anatomy);

        $dissector->shouldReceive('isValidResource')
            ->with('resource')
            ->once()
            ->andReturn(true);

        $dissector->shouldReceive('getMethodName')
            ->with('relation')
            ->once()
            ->andReturn('relation');

        $this->assertInstanceOf(
            '\Peakfijn\GetSomeRest\Contracts\Anatomy',
            $dissector->anatomy($request)
        );
    }

    public function testAnatomyOnlyGetsResourceNameAndIdAndRelationNameAndId()
    {
        $request = $this->getMockedRequest();
        $anatomy = $this->getMockedAnatomy();
        $dissector = $this->getMockedInstance(null, $anatomy)
            ->shouldAllowMockingProtectedMethods();

        $request->shouldReceive('segments')
            ->andReturn(['resource', '123', 'relation', 'abc']);

        $anatomy->shouldReceive('withResourceName')
            ->with('resource')
            ->once()
            ->andReturn($anatomy);

        $anatomy->shouldReceive('withResourceId')
            ->with('123')
            ->once()
            ->andReturn($anatomy);

        $anatomy->shouldReceive('withRelationName')
            ->with('relation')
            ->once()
            ->andReturn($anatomy);

        $anatomy->shouldReceive('withRelationId')
            ->with('abc')
            ->once()
            ->andReturn($anatomy);

        $dissector->shouldReceive('isValidResource')
            ->with('resource')
            ->once()
            ->andReturn(true);

        $dissector->shouldReceive('getMethodName')
            ->with('relation')
            ->once()
            ->andReturn('relation');

        $this->assertInstanceOf(
            '\Peakfijn\GetSomeRest\Contracts\Anatomy',
            $dissector->anatomy($request)
        );
    }
}
