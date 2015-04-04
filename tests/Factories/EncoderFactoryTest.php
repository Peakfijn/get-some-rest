<?php namespace Peakfijn\GetSomeRest\Tests\Factories;

use Mockery;
use RuntimeException;
use StdClass;
use Peakfijn\GetSomeRest\Contracts\Factory as FactoryContract;
use Peakfijn\GetSomeRest\Factories\EncoderFactory;

class EncoderFactoryTest extends FactoryTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Factories\EncoderFactory
     */
    protected function getInstance()
    {
        return new EncoderFactory();
    }

    /**
     * Get a mocked encoder for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedEncoder()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Encoder');
    }

    /**
     * Register some instances to the provided factory.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Factory $factory
     * @return \Peakfijn\GetSomeRest\Contracts\Factory
     */
    protected function registerInstances(FactoryContract $factory)
    {
        $factory->register('somename', $this->getMockedEncoder());
        $factory->register('SomeOther', $this->getMockedEncoder());
        $factory->register('OOPS-ALLCAPS', $this->getMockedEncoder());

        return $factory;
    }

    /**
     * Get a mocked instance of the http request for testing.
     *
     * @param  array $acceptHeaders (default: [])
     * @return \Mockery\Mock
     */
    protected function getMockedRequest(array $acceptHeaders = array())
    {
        $mock = Mockery::mock('\Illuminate\Http\Request');

        $mock->shouldReceive('header')
            ->with('accept')
            ->andReturn(implode(', ', $acceptHeaders));

        return $mock;
    }

    public function testRegisterOnlyAllowsEncoders()
    {
        $factory = $this->getInstance();

        try {
            $factory->register('not-an-encoder', new StdClass());
        } catch (RuntimeException $e) {
            return;
        }

        $this->fail('Factory allowed registering of a non-encoder.');
    }

    public function testMakeFromRequestReturnsRequestedEncoder()
    {
        $factory = $this->getMockedInstance();
        $encoder = $this->getMockedEncoder();
        $request = $this->getMockedRequest([
            'application/json',
            'application/vnd.api+yaml',
            'application/vnd.api.v1+xml',
        ]);

        $this->setProtectedProperty($factory, 'instances', ['yaml' => $encoder]);

        $factory->shouldReceive('contains')
            ->with('json')
            ->once()
            ->andReturn(false);

        $factory->shouldReceive('contains')
            ->with('yaml')
            ->once()
            ->andReturn(true);

        $factory->shouldReceive('contains')
            ->with('xml')
            ->never();

        $instance = $factory->makeFromRequest($request);
        $this->assertEquals($encoder, $instance);
    }

    public function testMakeFromRequestReturnsDefaultWhenNothingWasFound()
    {
        $factory = $this->getMockedInstance();
        $request = $this->getMockedRequest([
            'application/xml',
            'application/vnd.api+json',
            'application/vnd.api.v1+yaml',
        ]);

        $this->setProtectedProperty($factory, 'defaults', 'default-value');

        $factory->shouldReceive('contains')
            ->with('json')
            ->once()
            ->andReturn(false);

        $factory->shouldReceive('contains')
            ->with('yaml')
            ->once()
            ->andReturn(false);

        $factory->shouldReceive('contains')
            ->with('xml')
            ->once()
            ->andReturn(false);

        $instance = $factory->makeFromRequest($request);
        $this->assertEquals('default-value', $instance);
    }
}
