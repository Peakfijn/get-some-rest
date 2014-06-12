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
	 * @param  mixed  $data
	 * @param  \Illuminate\Http\Request $request
	 * @return string
	 */
	public function getContent( $data, Request $request )
	{
		$data = $this->toArray($data);
		
		return json_encode($data);
	}

}