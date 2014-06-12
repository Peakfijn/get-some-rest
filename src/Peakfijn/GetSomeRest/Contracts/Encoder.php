<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Contracts\ArrayableInterface;

abstract class Encoder {

	/**
	 * Get the encoded content type.
	 * 
	 * @return string
	 */
	public abstract function getContentType();

	/**
	 * Get the encoded content.
	 *
	 * @param  mixed  $data
	 * @param  \Illuminate\Http\Request $request
	 * @return string
	 */
	public abstract function getContent( $data, Request $request );

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