<?php namespace Peakfijn\GetSomeRest\Tests\Factories;

use Mockery;
use RuntimeException;
use StdClass;
use Peakfijn\GetSomeRest\Contracts\Factories\Factory as FactoryContract;
use Peakfijn\GetSomeRest\Factories\MutatorFactory;

class MutatorFactoryTest extends FactoryTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Factories\MutatorFactory
     */
    protected function getInstance()
    {
        return new MutatorFactory();
    }

    /**
     * Get a mocked mutator for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedMutator()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Mutators\Mutator');
    }

    /**
     * Register some instances to the provided factory.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\Factory $factory
     * @return \Peakfijn\GetSomeRest\Contracts\Factories\Factory
     */
    protected function registerInstances(FactoryContract $factory)
    {
        $factory->register('somename', $this->getMockedMutator());
        $factory->register('SomeOther', $this->getMockedMutator());
        $factory->register('OOPS-ALLCAPS', $this->getMockedMutator());

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

    public function testRegisterOnlyAllowsMutators()
    {
        $factory = $this->getInstance();

        try {
            $factory->register('not-a-mutator', new StdClass());
        } catch (RuntimeException $e) {
            return;
        }

        $this->fail('Factory allowed registering of a non-mutator.');
    }

    public function testMakeFromRequestReturnsRequestedMutator()
    {
        $factory = $this->getMockedInstance();
        $mutator = $this->getMockedMutator();
        $request = $this->getMockedRequest([
            'application/vnd.api.v1.array+json',
            'application/vnd.api.v1.plain+xml',
            'application/vnd.api.v1.array+yml',
        ]);

        $this->setProtectedProperty($factory, 'instances', ['array' => $mutator]);

        $factory->shouldReceive('contains')
            ->with('array')
            ->once()
            ->andReturn(true);

        $factory->shouldReceive('contains')
            ->with('plain')
            ->never();

        $instance = $factory->makeFromRequest($request);
        $this->assertEquals($mutator, $instance);
    }

    public function testMakeFromRequestReturnsDefaultWhenNothingWasFound()
    {
        $factory = $this->getMockedInstance();
        $request = $this->getMockedRequest([
            'application/vnd.api.v1.array+json',
            'application/vnd.api.v1.plain+xml',
            'application/vnd.api.v1.array+yml',
        ]);

        $this->setProtectedProperty($factory, 'defaults', 'default-value');

        $factory->shouldReceive('contains')
            ->with('array')
            ->twice()
            ->andReturn(false);

        $factory->shouldReceive('contains')
            ->with('plain')
            ->once()
            ->andReturn(false);

        $instance = $factory->makeFromRequest($request);
        $this->assertEquals('default-value', $instance);
    }
}
