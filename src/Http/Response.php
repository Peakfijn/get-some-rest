<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Response as IlluminateResponse;
use Peakfijn\GetSomeRest\Contracts\Encoder as EncoderContract;
use Peakfijn\GetSomeRest\Contracts\Mutator as MutatorContract;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Response extends SymfonyResponse
{
    /**
     * The original content of the response.
     *
     * @var mixed
     */
    protected $original;

    /**
     * The encoder to use when finalizing the response.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Encoder
     */
    protected $encoder;

    /**
     * The mutator to use when finalizing the response.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Mutator
     */
    protected $mutator;

    /**
     * Create a new response, and set the content as original content.
     *
     * @param string  $content
     * @param integer $status
     * @param array   $headers
     */
    public function __construct(
        $content = '',
        $status = 200,
        $headers = array()
    ) {
        $this->setOriginalContent($content);

        return parent::__construct('', $status, $headers);
    }

    /**
     * Set the original content of the response.
     * This will be stored, until the response must be prepared.
     * It will then be mutated and encoded.
     *
     * @param  string $content
     * @return \Peakfijn\GetSomeRest\Http\Response
     */
    public function setOriginalContent($content)
    {
        $this->original = $content;

        return $this;
    }

    /**
     * Get the original content of the response.
     *
     * @return mixed
     */
    public function getOriginalContent()
    {
        return $this->original;
    }

    /**
     * Set the encoder to the response.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Encoder $encoder
     * @return \Peakfijn\GetSomeRest\Http\Response
     */
    public function setEncoder(EncoderContract $encoder)
    {
        $this->encoder = $encoder;

        return $this;
    }

    /**
     * Get the encoder for this response.
     *
     * @return \Peakfijn\GetSomeRest\Contracts\Encoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Set the mutator to the response.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Mutator $mutator
     * @return \Peakfijn\GetSomeRest\Http\Response
     */
    public function setMutator(MutatorContract $mutator)
    {
        $this->mutator = $mutator;

        return $this;
    }

    /**
     * Get the mutator for this response.
     *
     * @return \Peakfijn\GetSomeRest\Contracts\Mutator
     */
    public function getMutator()
    {
        return $this->mutator;
    }

    /**
     * Prepares the Response before it is sent to the client.
     *
     * This method tweaks the Response to ensure that it is
     * compliant with RFC 2616. Most of the changes are based on
     * the Request that is "associated" with this Response.
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Peakfijn\GetSomeRest\Http\Response
     */
    public function prepare(SymfonyRequest $request)
    {
        $mutator = $this->getMutator();
        $encoder = $this->getEncoder();

        $content = $this->getOriginalContent();
        $content = $mutator->mutate($request, $this->getStatusCode(), $content);
        $content = $encoder->encode($request, $content);

        $this->setContent($content);
        $this->headers->set('Content-Type', $encoder->getContentType());

        return parent::prepare($request);
    }

    /**
     * Make a new peakfijn response from existing symfony response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @return \Peakfijn\GetSomeRest\Http\Response
     */
    public static function makeFromExisting(SymfonyResponse $response)
    {
        if ($response instanceof self) {
            return $response;
        }

        $content = $response->getContent();

        if (method_exists($response, 'getOriginalContent')) {
            $content = $response->getOriginalContent();
        }

        return new self(
            $content,
            $response->getStatusCode(),
            $response->headers->all()
        );
    }
}
