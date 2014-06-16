<?php namespace Peakfijn\GetSomeRest\Encoders;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder;

class JsonEncoder extends Encoder {

	/**
	 * Get the encoded content type.
	 * 
	 * @return string
	 */
	public function getContentType()
	{
		return 'application/json';
	}

	/**
	 * Get the encoded content.
	 *
	 * @param  array  $data
	 * @param  \Illuminate\Http\Request $request
	 * @return string
	 */
	public function getContent( array $data, Request $request )
	{
		return json_encode($data);
	}

}
