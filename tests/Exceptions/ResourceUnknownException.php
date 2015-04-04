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

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedInstance()
    {
        return parent::getMockedInstance();
    }
}
