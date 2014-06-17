<?php

use Peakfijn\GetSomeRest\Encoders\XmlEncoder;

class XmlEncoderTest extends EncoderTestCase {
	
	/**
	 * Get a new encoder instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Encoder
	 */
	protected function getEncoder()
	{
		return new XmlEncoder();
	}

}
