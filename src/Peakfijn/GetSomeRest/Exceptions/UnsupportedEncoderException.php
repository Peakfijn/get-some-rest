<?php namespace Peakfijn\GetSomeRest\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnsupportedEncoderException extends HttpException {

	/**
	 * This exception is thrown when an unsupported response format was requested.
	 *
	 * @param string     $message  The internal exception message
	 */
	public function __construct( $requested = null )
	{
		if( !empty($requested) )
		{
			$requested = 'Unsupported format "'. $requested .'" requested.';
		}

		parent::__construct(406, $requested, null, array(), 0);
	}

}
