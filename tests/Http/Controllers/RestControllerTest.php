<?php namespace Peakfijn\GetSomeRest\Tests\Http\Controllers;

use Mockery;
use Peakfijn\GetSomeRest\Http\Controllers\RestController;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

class RestControllerTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Http\Controllers\RestController
     */
    protected function getInstance()
    {
        return new RestController();
    }

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedInstance()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Http\Controllers\RestController')
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}
