<?php namespace Peakfijn\GetSomeRest\Exceptions;

use Peakfijn\GetSomeRest\Contracts\Exceptions\RestException as RestExceptionContract;
use Peakfijn\GetSomeRest\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RestException extends HttpException implements RestExceptionContract
{
    /**
     * Returns if the exception should be caught.
     *
     * @return boolean
     */
    public function shouldBeCaught()
    {
        return true;
    }

    /**
     * Get a response, from this exception.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return new Response(
            $this->getMessage(),
            $this->getStatusCode()
        );
    }
}
