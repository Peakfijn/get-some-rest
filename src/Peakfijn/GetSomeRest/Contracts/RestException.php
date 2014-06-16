<?php namespace Peakfijn\GetSomeRest\Contracts;

use RuntimeException;
use Illuminate\Support\Contracts\ArrayableInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

abstract class RestException extends RuntimeException implements HttpExceptionInterface {

	/**
	 * The status code of this exception.
	 * 
	 * @var int
	 */
	private $statusCode_;

	/**
	 * The headers of this exception.
	 * 
	 * @var array
	 */
	private $headers_;

	/**
	 * The content of this exception.
	 * 
	 * @var array
	 */
	private $content_;

	public function __construct( $statusCode, $message = null, array $content = array(), \Exception $previous = null, array $headers = array(), $code = 0 )
	{
		$this->statusCode_ = $statusCode;
		$this->content_ = $content;
		$this->headers_ = $headers;

		parent::__construct($message, $code, $previous);
	}

	/**
	 * Get the status code of this exception.
	 * 
	 * @return int
	 */
	public function getStatusCode()
	{
		return $this->statusCode_;
	}

	/**
	 * Get the headers of this exception.
	 * 
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers_;
	}

	/**
	 * Get the content of the exception.
	 * This is not the message, but e.g. the validation errors.
	 * 
	 * @return array
	 */
	public function getContent()
	{
		return $this->content_;
	}

}
