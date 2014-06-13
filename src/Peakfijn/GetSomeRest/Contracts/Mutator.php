<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Contracts\ArrayableInterface;
use Peakfijn\GetSomeRest\Http\Response;

abstract class Mutator {

	/**
	 * Get the mutated content
	 * 
	 * @param  \Peakfijn\GetSomeRest\Http\Response $data
	 * @param  \Illuminate\Http\Request $request
	 * @return array
	 */
	public abstract function getContent( Response $data, Request $request );

	/**
	 * Try to convert the given data to an array.
	 * 
	 * @param  mixed $data
	 * @return array
	 */
	protected function toArray( $data )
	{
		if( is_array($data) )
			return $data;

		if( $data instanceof ArrayableInterface )
			return $data->toArray();

		return (array) $data;
	}

}