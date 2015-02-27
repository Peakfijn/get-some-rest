<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Response as IlluminateResponse;

class Response extends IlluminateResponse {
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
     * Create a new response from an existing response.
     *
     * @param $response
     * @return Peakfijn\GetSomeRest\Http\Response
     */
    public static function makeFromResponse($response)
    {
        if ($response instanceof Response) {
            return $response;
        }

        if ($response instanceof IlluminateResponse) {
            return self::makeFromIlluminateResponse($response);
        }

        return self::makeFromMiscResponse($response);
    }

    /**
     * @param IlluminateResponse $response
     * @return static
     */
    protected static function makeFromIlluminateResponse(IlluminateResponse $response)
    {
        return new static(
            $response->getOriginalContent(),
            $response->getStatusCode(),
            $response->headers->all()
        );
    }

    /**
     * @param $response
     * @return static
     */
    protected static function makeFromMiscResponse($response)
    {
        return new static($response);
    }
}