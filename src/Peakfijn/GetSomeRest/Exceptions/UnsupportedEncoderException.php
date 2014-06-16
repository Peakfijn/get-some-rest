<?php namespace Peakfijn\GetSomeRest\Exceptions;

use Peakfijn\GetSomeRest\Contracts\RestException;

class UnsupportedEncoderException extends RestException {

	/**
	 * This exception is thrown when an unsupported response format was requested.
	 *
	 * @param string $encoder (default: null)
	 */
	public function __construct( $encoder = null )
	{
		if( !empty($encoder) )
		{
			$encoder = 'Unsupported format "'. $encoder .'" requested.';
		}

		parent::__construct(406, $encoder);
	}

}
