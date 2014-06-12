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
		return 'application/yaml';
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
		
		return Yaml::dump($data);
	}

}