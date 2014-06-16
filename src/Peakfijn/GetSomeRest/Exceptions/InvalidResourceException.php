<?php namespace Peakfijn\GetSomeRest\Exceptions;

use Peakfijn\GetSomeRest\Contracts\RestException;

class InvalidResourceException extends RestException {

	/**
	 * This exception should be thrown when a resource could not be processed
	 * due to invalid attributes.
	 * 
	 * @param string $resource
	 * @param array  $errors
	 */
	public function __construct( $resource, $errors )
	{
		parent::__construct(406, 'Invalid attributes given for ['. $resource .'].', $errors);
	}

	/**
	 * Create the invalid resource exception for the given resource.
	 * Not that errors must be available trought getErrors() as an array.
	 * 
	 * @param  mixed $resource
	 * @return \Peakfijn\GetSomeRest\Exceptions\InvalidResourceException
	 */
	public static function make( $resource )
	{
		$errors = [];

		if( method_exists($resource, 'getErrors') )
		{
			$errors = $resource->getErrors();
		}

		return new static(get_class($resource), $resource->getErrors());
	}

}
