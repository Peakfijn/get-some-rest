<?php namespace Peakfijn\GetSomeRest\Exceptions;

use Peakfijn\GetSomeRest\Contracts\RestException;

class NotFoundResourceException extends RestException {

	/**
	 * This exception should be thrown when a resource could not be processed
	 * due to invalid attributes.
	 * 
	 * @param  string $resource
	 * @param  mixed  $identifier  (default: null)
	 */
	public function __construct( $resource, $identifier = '' )
	{
		$message = 'Nothing was found for resource ['. $resource .']';

		if( !empty($identifier) )
		{
			$message .= ' using "'. $identifier .'" as identifier.';
		}
		else
		{
			$message .= '.';
		}

		parent::__construct(404, $message);
	}

}
