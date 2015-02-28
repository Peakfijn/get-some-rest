<?php namespace Peakfijn\GetSomeRest\Contracts;

use Peakfijn\GetSomeRest\Http\Response;

abstract class Encoder {

    /**
     * Get the encoded content type.
     *
     * @return string
     */
    public abstract function getContentType();

    /**
     * Get the encoded content.
     *
     * @param Response $response
     * @return string
     */
    public abstract function getContent(Response $response);

}