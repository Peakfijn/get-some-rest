<?php namespace Peakfijn\GetSomeRest\Contracts;

interface RestExceptionContract {

    /**
     * @return mixed
     */
    public function shouldBeCaught();

    /**
     * @return mixed
     */
    public function getResponse();

}