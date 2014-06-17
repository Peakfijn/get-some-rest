<?php

use Peakfijn\GetSomeRest\Mutators\MetaMutator;

class MetaMutatorTest extends MutatorTestCase {

	/**
	 * Get a new mutator instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Mutator
	 */
	protected function getMutator()
	{
		return new MetaMutator();
	}

	/**
	 * 
	 * Check if the basic meta function returns an array.
	 * 
	 * @return void
	 */
	public function testBasicMetaBuilderReturnsArray()
	{
		$mutator = $this->getMutator();
		$response = $this->getResponse();

		$this->assertInternalType('array', $this->invokeMethod($mutator, 'getBasics', [$response]));
	}

	/**
	 * Check if the single meta function returns an array.
	 * 
	 * @return void
	 */
	public function testSingleMetaBuilderReturnsArray()
	{
		$mutator = $this->getMutator();

		$this->assertInternalType('array', $this->invokeMethod($mutator, 'getSingle', [['test' => true]]));
	}

	/**
	 * Check if the multiple meta function returns an array.
	 * 
	 * @return void
	 */
	public function testMultipleMetaBuilderReturnsArray()
	{
		$mutator = $this->getMutator();

		$this->assertInternalType('array', $this->invokeMethod($mutator, 'getMultiple', [['test' => true]]));
	}

	/**
	 * The isAssociativeArray function should detect the correct type of array.
	 * It is important because result object name is based on this.
	 * 
	 * @return void
	 */
	public function testAssociativeArrayCheckReturnsTrueOnAssociativeArray()
	{
		$mutator = $this->getMutator();

		$array = ['this' => 'is', 'an' => 'associative', 'array' => true];

		$this->assertTrue($this->invokeMethod($mutator, 'isAssociativeArray', [$array]));
	}

	/**
	 * The isAssociativeArray function should detect the correct type of array.
	 * It is important because result object name is based on this.
	 * 
	 * @return void
	 */
	public function testAssociativeArrayCheckReturnsFalseOnNonAssociativeArray()
	{
		$mutator = $this->getMutator();

		$array = ['this', 'is', 'an', 'non', 'associative', 'array'];

		$this->assertFalse($this->invokeMethod($mutator, 'isAssociativeArray', [$array]));
	}

	/**
	 * The isErrorCode function should detect the correct status codes.
	 * All the tested status codes are from the symfony response object.
	 * 
	 * @return void
	 */
	public function testErrorCodeCheckReturnsTrueOnErrorStatusCodes()
	{
		$mutator = $this->getMutator();

		$error_codes = [
			300, 301, 302, 303, 304, 305, 306, 307, 308, 400,
			401, 402, 403, 404, 405, 406, 407, 408, 409, 410,
			411, 412, 413, 414, 415, 416, 417, 418, 422, 423,
			424, 425, 426, 428, 429, 431, 500, 501, 502, 503,
			504, 505, 506, 507, 508, 510, 511
		];

		foreach( $error_codes as $code )
		{
			$this->assertTrue($this->invokeMethod($mutator, 'isErrorCode', [$code]));
		}
	}

	/**
	 * The isErrorCode function should detect the correct status codes.
	 * All the tested status codes are from the symfony response object.
	 * 
	 * @return void
	 */
	public function testErrorCodeCheckReturnsFalseOnNonErrorStatusCodes()
	{
		$mutator = $this->getMutator();

		$error_codes = [200, 201, 202, 203, 204, 205, 206, 207, 208, 226];

		foreach( $error_codes as $code )
		{
			$this->assertFalse($this->invokeMethod($mutator, 'isErrorCode', [$code]));
		}		
	}

}