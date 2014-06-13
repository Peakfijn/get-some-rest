<?php namespace Peakfijn\GetSomeRest\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnsupportedResponseFormatException extends HttpException {

    /**
     * This exception is thrown when an unsupported response format was requested.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(406, $message, $previous, array(), $code);
    }

}

