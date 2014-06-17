<?php

use Peakfijn\GetSomeRest\Encoders\YamlEncoder;

class YamlEncoderTest extends EncoderTestCase {
	
	/**
	 * Get a new encoder instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Encoder
	 */
	protected function getEncoder()
	{
		return new YamlEncoder();
	}

}
