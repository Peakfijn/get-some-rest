<?php namespace Peakfijn\GetSomeRest\Http\Exceptions;

use Peakfijn\GetSomeRest\Contracts\RestException as RestExceptionContract;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RestException extends HttpException implements RestExceptionContract
{
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
     * Get a response, from this exception.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResponse()
    {
        return new Response(
            $this->getMessage(),
            $this->getStatusCode()
        );
    }
}
