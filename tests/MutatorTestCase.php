<?php

abstract class MutatorTestCase extends TestCase {

	/**
	 * Get a new mutator instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Mutator
	 */
	protected abstract function getMutator();

	/**
	 * Get a new response mock instance.
	 * 
	 * @return \Mockery\Mock
	 */
	protected function getResponse()
	{
		$mock = Mockery::mock('\Peakfijn\GetSomeRest\Http\Response');
		$mock
			->shouldReceive('getOriginalContent')
			->andReturn(['test' => true]);

		$mock
			->shouldReceive('getStatusCode')
			->andReturn(200);

		$mock
			->shouldReceive('getStatusText')
			->andReturn('ok');

		$mock
			->shouldReceive('hasException')
			->andReturn(false);

		return $mock;
	}

	/**
	 * Get a new request mock instance.
	 * 
	 * @return \Mockery\Mock
	 */
	protected function getRequest()
	{
		return Mockery::mock('\Illuminate\Http\Request');
	}


	/**
	 * Each mutator must return any response to an array.
	 * This can be an Eloquent model or a simple array.
	 * 
	 * @return void
	 */
	public function testMutatorReturnsContent()
	{
		$this->assertInternalType(
			'array',
			$this->getMutator()->getContent(
				$this->getResponse(),
				$this->getRequest()
			)
		);
	}

}
