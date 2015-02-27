<?php namespace Peakfijn\GetSomeRest\Http\Exceptions;

use Exception;
use Peakfijn\GetSomeRest\Contracts\RestExceptionContract;
use Peakfijn\GetSomeRest\Http\Response;

class RestException extends Exception implements RestExceptionContract {

    /**
     * Returns if the exception should be caught.
     *
     * @return bool
     */
    public function shouldBeCaught()
    {
        return true;
    }

    /**
     * Get the status code of the exception.
     *
     * @return int
     */
    public function getStatusCode()
    {
        $code = $this->getCode();

        return ($code === 0) ? 500 : $code;
    }

    /**
     * Get the response.
     *
     * @return Peakfijn\GetSomeRest\Http\Response
     */
    public function getResponse()
    {
        return new Response(
            $this->getMessage(),
            $this->getStatusCode()
        );
    }

    /**
     * Create an instance of RestException from an existing exception.
     *
     * @param Exception $exception
     * @return static
     */
    public static function makeFromException(Exception $exception)
    {
        return new static(
            $exception->getMessage(),
            $exception->getCode()
        );
    }
}