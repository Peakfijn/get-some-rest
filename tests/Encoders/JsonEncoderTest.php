<?php

use Peakfijn\GetSomeRest\Encoders\JsonEncoder;

class JsonEncoderTest extends EncoderTestCase {
	
	/**
	 * Get a new encoder instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Encoder
	 */
	protected function getEncoder()
	{
		return new JsonEncoder();
	}

}
