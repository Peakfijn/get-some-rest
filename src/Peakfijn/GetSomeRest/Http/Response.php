<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Response as IlluminateResponse;
use Peakfijn\GetSomeRest\Contracts\Encoder;
use Peakfijn\GetSomeRest\Contracts\Mutator;
use Peakfijn\GetSomeRest\Contracts\RestException;
use Peakfijn\GetSomeRest\Http\Pagination;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends IlluminateResponse {

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
	 * Finalize the response by mutating & encoding the "original" content.
	 *
	 * @param  \Peakfijn\GetSomeRest\Contracts\Mutator $mutator
	 * @param  \Peakfijn\GetSomeRest\Contracts\Encoder $encoder
	 * @param  \Symfony\Component\HttpFoundation\Request $request
	 * @return \Peakfijn\GetSomeRest\Http\Request
	 */
	public function finalize( Mutator $mutator, Encoder $encoder, Request $request )
	{
		try {
			$content = $mutator->getContent($this,    $request);
			$content = $encoder->getContent($content, $request);

			$this->setContent($content);
			$this->headers->set('content-type', $encoder->getContentType());
		} catch (\UnexpectedValueException $error) {
			// don't change anything if the value can't be converted to string.
		}

		return $this;
	}

	/**
	 * Get the status text.
	 * It will be generated by the status code.
	 *
	 * @return string
	 */
	public function getStatusText()
	{
		return $this->statusText;
	}

	/**
	 * Attach an exception to the response.
	 *
	 * @param HttpExceptionInterface $exception
	 */
	public function setException( HttpExceptionInterface $exception )
	{
		$this->exception = $exception;
	}

	/**
	 * Check if the response has an exception.
	 *
	 * @return boolean
	 */
	public function hasException()
	{
		return !empty($this->exception);
	}

	/**
	 * Get the exception attached to this response.
	 *
	 * @return HttpExceptionInterface
	 */
	public function getException()
	{
		return $this->exception;
	}

	/**
	 * Set the pagination object that describes the main pagination info.
	 *
	 * @param \Peakfijn\GetSomeRest\Http\Pagination $pagination
	 */
	public function setPagination( Pagination $pagination )
	{
		$this->pagination = $pagination;
	}

	/**
	 * Get the Pagination object.
	 *
	 * @return \Peakfijn\GetSomeRest\Http\Pagination|null
	 */
	public function getPagination()
	{
		return $this->pagination;
	}

	/**
	 * Check if the pagination info was given.
	 *
	 * @return boolean
	 */
	public function hasPagination()
	{
		return !is_null($this->pagination);
	}

	/**
	 * Create a new response from an existing Illuminate response.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Response $response
	 * @return \Peakfijn\GetSomeRest\Http\Response
	 */
	public static function makeFromExisting( SymfonyResponse $response )
	{
		if( $response instanceof Response )
		{
			return $response;
		}

		$new = new static(
			($response instanceof IlluminateResponse)? $response->getOriginalContent(): $response->getContent(),
			$response->getStatusCode(),
			$response->headers->all()
		);

		return $new;
	}

	/**
	 * Create a new response from an exception.
	 *
	 * @param  Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception
	 * @return \Peakfijn\GetSomeRest\Http\Response
	 */
	public static function makeFromException( HttpExceptionInterface $exception )
	{
		$response = new static(
			($exception instanceof RestException)? $exception->getContent(): $exception->getMessage(),
			$exception->getStatusCode(),
			$exception->getHeaders()
		);

		$response->setException($exception);

		return $response;
	}

}
