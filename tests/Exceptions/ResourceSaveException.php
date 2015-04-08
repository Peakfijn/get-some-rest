<?php namespace Peakfijn\GetSomeRest\Tests\Exceptions;

use Peakfijn\GetSomeRest\Exceptions\ResourceSaveException;

class ResourceSaveExceptionTest extends RestExceptionTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Exceptions\ResourceSaveException
     */
    protected function getInstance()
    {
        return new ResourceSaveException();
    }

    public function testResponseStatusCodeMatchesExpectedStatus()
    {
        $instance = $this->getInstance();

        $this->assertEquals(422, $instance->getStatusCode());
    }
}
