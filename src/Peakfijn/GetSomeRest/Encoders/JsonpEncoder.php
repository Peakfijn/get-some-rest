<?php namespace Peakfijn\GetSomeRest\Encoders;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder;

class JsonpEncoder extends Encoder {

	/**
	 * Get the encoded content type.
	 * 
	 * @return string
	 */
	public function getContentType()
	{
		return 'application/javascript';
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
		$callback = $request->input('callback', 'callback');

		return $callback .'('. json_encode($data) .');';
	}

}
