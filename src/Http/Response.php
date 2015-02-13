<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Response as IlluminateResponse;

class Response extends IlluminateResponse
{
    /**
     * Mutate the response.
     *
     * @return $this
     */
    public function mutate()
    {
        return $this;
    }

    /**
     * Encode the response.
     *
     * @return $this
     */
    public function encode()
    {
        return $this;
    }

    /**
     * Create a new response from an existing Illuminate response.
     *
     * @param IlluminateResponse $response
     * @return Peakfijn\GetSomeRest\Http\Response
     */
    public static function makeFromIlluminateResponse(IlluminateResponse $response)
    {
        if( $response instanceof Response )
        {
            return $response;
        }

        $new = new static(
            ($response instanceof IlluminateResponse) ? $response->getOriginalContent() : $response->getContent(),
            $response->getStatusCode(),
            $response->headers->all()
        );

        return $new;
    }
}