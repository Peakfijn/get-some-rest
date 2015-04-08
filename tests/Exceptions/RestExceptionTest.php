<?php namespace Peakfijn\GetSomeRest\Tests\Exceptions;

use Mockery;
use Peakfijn\GetSomeRest\Exceptions\RestException;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

class RestExceptionTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @param  integer $status (default: 500)
     * @return \Peakfijn\GetSomeRest\Exceptions\RestException
     */
    protected function getInstance($status = 500)
    {
        return new RestException(500);
    }

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @param  integer $status (default: 500)
     * @return \Mockery\Mock
     */
    protected function getMockedInstance($status = 500)
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Exceptions\RestException');
    }

    public function testShouldBeCaughtReturnsBoolean()
    {
        $exception = $this->getInstance();

        $this->assertInternalType('boolean', $exception->shouldBeCaught());
    }

    public function testGetResponseIsInstanceOfSymfonyResponse()
    {
        $exception = $this->getInstance();

        $this->assertInstanceOf(
            '\Symfony\Component\HttpFoundation\Response',
            $exception->getResponse()
        );
    }

}
