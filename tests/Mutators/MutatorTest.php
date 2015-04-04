<?php namespace Peakfijn\GetSomeRest\Tests\Mutators;

use Mockery;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

abstract class MutatorTest extends AbstractUnitTest
{
    /**
     * Get a mocked request for testing.
     *
     * @return \Mockery\Mock
     */
    public function getMockedRequest()
    {
        return Mockery::mock('\Illuminate\Http\Request');
    }

    /**
     * Get a mocked arrayable object for testing.
     *
     * @param  mixed $arrayable (default: null)
     * @return \Mockery\Mock
     */
    public function getMockedArrayable($arrayable = null)
    {
        $mock = Mockery::mock('\Illuminate\Contracts\Support\Arrayable');

        $mock->shouldReceive('toArray')
            ->andReturn($arrayable);

        return $mock;
    }

    public function testCastToArrayAlwaysReturnsAnArray()
    {
        $mutator = $this->getInstance();
        $arrayable = $this->getMockedArrayable(['test' => true]);

        $this->assertInternalType('array', $mutator->castToArray($arrayable));
        $this->assertInternalType('array', $mutator->castToArray('test'));
    }

    public function testIsErrorStatusReturnsCorrectBoolean()
    {
        $mutator = $this->getInstance();
        $statuses = [
            100 => false,
            101 => false,
            200 => false,
            204 => false,
            401 => true,
            500 => true,
        ];

        foreach ($statuses as $status => $error) {
            $this->assertEquals($error, $mutator->isErrorStatus($status));
        }
    }

    public function testMutateReturnsArray()
    {
        $mutator = $this->getMockedInstance();
        $request = $this->getMockedRequest();

        $data = 'test';
        $status = 200;

        $mutator->shouldReceive('castToArray')
            ->with($data)
            ->andReturn(['test']);

        $mutator->shouldReceive('isErrorStatus')
            ->with($status)
            ->andReturn(false);

        $this->assertEquals(
            ['test'],
            $mutator->mutate($request, $status, $data)
        );
    }

    public function testMutateReturnsErrorArrayWhenStatusIsAnError()
    {
        $mutator = $this->getMockedInstance();
        $request = $this->getMockedRequest();

        $data = 'some error';
        $status = 500;

        $mutator->shouldReceive('castToArray')
            ->with($data)
            ->andReturn(['some error']);

        $mutator->shouldReceive('isErrorStatus')
            ->with($status)
            ->andReturn(true);

        $this->assertEquals(
            ['errors' => ['some error']],
            $mutator->mutate($request, $status, $data)
        );
    }
}
