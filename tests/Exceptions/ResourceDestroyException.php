<?php namespace Peakfijn\GetSomeRest\Tests\Exceptions;

use Peakfijn\GetSomeRest\Exceptions\ResourceDestroyException;

class ResourceDestroyExceptionTest extends RestExceptionTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Exceptions\ResourceDestroyException
     */
    protected function getInstance()
    {
        return new ResourceDestroyException();
    }

    public function testResponseStatusCodeMatchesExpectedStatus()
    {
        $instance = $this->getInstance();

        $this->assertEquals(422, $instance->getStatusCode());
    }
}
