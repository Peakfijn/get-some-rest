<?php namespace Peakfijn\GetSomeRest\Tests\Exceptions;

use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;

class ResourceUnknownExceptionTest extends RestExceptionTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException
     */
    protected function getInstance()
    {
        return new ResourceUnknownException();
    }

    public function testResponseStatusCodeMatchesExpectedStatus()
    {
        $instance = $this->getInstance();

        $this->assertEquals(404, $instance->getStatusCode());
    }
}
