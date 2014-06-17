<?php

use Peakfijn\GetSomeRest\Encoders\JsonpEncoder;

class JsonpEncoderTest extends EncoderTestCase {
	
	/**
	 * Get a new encoder instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Encoder
	 */
	protected function getEncoder()
	{
		return new JsonpEncoder();
	}

	/**
	 * Get a new request mock instance.
	 * And mock the input function, so the JsonpEncoder
	 * can request the callback parameter.
	 * 
	 * @return \Mockery\Mock
	 */
	protected function getRequest()
	{
		$mock = parent::getRequest();

		$mock
			->shouldReceive('input')
			->once()
			->andReturn('test');

		return $mock;
	}

}
