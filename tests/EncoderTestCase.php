<?php

abstract class EncoderTestCase extends TestCase {
		
	/**
	 * Get a new encoder instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Encoder
	 */
	protected abstract function getEncoder();

	/**
	 * Get a new request mock instance.
	 * 
	 * @return \Mockery\Mock
	 */
	protected function getRequest()
	{
		return Mockery::mock('Illuminate\Http\Request');
	}

	/**
	 * Each encoder must specify it's content by returning a valid Content Type.
	 * It is used as Content Type header value by the response.
	 * 
	 * @return void
	 */
	public function testEncoderReturnsContentType()
	{
		$this->assertInternalType(
			'string',
			$this->getEncoder()->getContentType()
		);
	}

	/**
	 * Each encoder must encode it's content to a string.
	 * That string can contain every syntax the encoders are build for.
	 * 
	 * @return void
	 */
	public function testEncoderReturnsContent()
	{
		$this->assertInternalType(
			'string',
			$this->getEncoder()->getContent(
				['test' => true],
				$this->getRequest()
			)
		);
	}

}
