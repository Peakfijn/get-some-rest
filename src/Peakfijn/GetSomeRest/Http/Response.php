<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Response as IlluminateResponse;
use Peakfijn\GetSomeRest\Contracts\Encoder;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

class Response extends IlluminateResponse {

	/**
	 * Finalize the response by encoding the "original" content.
	 *
	 * @param  \Peakfijn\GetSomeRest\Contracts\Encoder $encoder
	 * @param  \Symfony\Component\HttpFoundation\Request $request
	 * @return \Peakfijn\GetSomeRest\Http\Request
	 */
	public function finalize( Encoder $encoder, Request $request )
	{
		$this->setContent($encoder->getContent($this->getOriginalContent(), $request));
		$this->headers->set('content-type', $encoder->getContentType());

		return $this;
	}

	/**
	 * Create a new response from an existing Illuminate response.
	 * 
	 * @param  Illuminate\Http\Response $response
	 * @return \Peakfijn\GetSomeRest\Http\Response
	 */
	public static function makeFromExisting( IlluminateResponse $response )
	{
		return new static(
			$response->getOriginalContent(),
			$response->getStatusCode(),
			$response->headers->all()
		);
	}

	/**
	 * Create a new response from an exception.
	 * 
	 * @param  Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception
	 * @return \Peakfijn\GetSomeRest\Http\Response
	 */
	public static function makeFromException( HttpExceptionInterface $exception )
	{
		return new static(
			$exception->getMessage(),
			$exception->getStatusCode(),
			$exception->getHeaders()
		);
	}

}