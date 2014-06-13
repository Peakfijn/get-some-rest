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
	 * @param  array  $data
	 * @param  \Illuminate\Http\Request $request
	 * @return string
	 */
	public abstract function getContent( array $data, Request $request );

}