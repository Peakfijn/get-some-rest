<?php namespace Peakfijn\GetSomeRest\Encoders;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder;
use Symfony\Component\Yaml\Yaml;

class YamlEncoder extends Encoder {

	/**
	 * Get the encoded content type.
	 * 
	 * @return string
	 */
	public function getContentType()
	{
		return 'text/x-yaml';
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
		return Yaml::dump($data);
	}

}