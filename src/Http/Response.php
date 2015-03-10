<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Response as IlluminateResponse;
use Peakfijn\GetSomeRest\Contracts\Encoder;
use Peakfijn\GetSomeRest\Contracts\Mutator;
use Peakfijn\GetSomeRest\Contracts\RestExceptionContract;
use Peakfijn\GetSomeRest\Http\Exceptions\RestException;

class Response extends IlluminateResponse {

    /*
     * Array with the user's input.
     */
    protected $input = [];

    /**
     * The exception if the response is actually an exception.
     *
     * @var HttpExceptionInterface
     */
    protected $exception;

    /**
     * May contain the pagination information about the response.
     *
     * @var \Peakfijn\GetSomeRest\Http\Pagination
     */
    protected $pagination;

    /**
     * The encoder being used by the response.
     *
     * @var
     */
    protected $encoder;

    /**
     * The mutator being used by the response.
     *
     * @var
     */
    protected $mutator;

    /**
     * Set the input from the request.
     *
     * @param $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * Set the mutator for the response.
     *
     * @param Mutator $mutator
     */
    public function setMutator(Mutator $mutator)
    {
        $this->mutator = $mutator;
    }

    /**
     * Mutate the response.
     *
     * @return $this
     */
    public function mutate()
    {
        $this->setContent($this->mutator->getContent($this));

        return $this;
    }

    /**
     * Set the encoder for the response.
     *
     * @param Encoder $encoder
     */
    public function setEncoder(Encoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Encode the response.
     * We grab the original content from the response because Laravel already applies some
     * encoding by default.
     *
     * @return $this
     */
    public function encode()
    {
        $this->setContent($this->encoder->getContent($this));
        $this->headers->set('content-type', $this->encoder->getContentType());

        return $this;
    }

    /**
     * @param RestExceptionContract $exception
     */
    public function setException(RestExceptionContract $exception)
    {
        $this->exception = $exception;
        $this->setContent($exception->getMessage());
        $this->setStatusCode($exception->getStatusCode());
    }

    /**
     * Check if the response has an exception.
     *
     * @return bool
     */
    public function hasException()
    {
        return isset($this->exception);
    }

    /**
     * Returns the exception of the response.
     *
     * @return HttpExceptionInterface
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Get the status text of the response.
     *
     * @return string
     */
    public function getStatusText()
    {
        return 'status';
    }

    /**
     * Check if the response has a pagination
     *
     * @return bool
     */
    public function hasPagination()
    {
        return false;
    }

    /**
     * ===============================================================
     * Static methods
     * ===============================================================
     */

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