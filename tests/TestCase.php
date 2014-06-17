<?php

abstract class TestCase extends PHPUnit_Framework_TestCase {
	
	/**
	 * Mockery should always be closed when a test is teared down.
	 * 
	 * @return void
	 */
	public function tearDown()
	{
		Mockery::close();
	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @see https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 * @return mixed Method return.
	 */
	public function invokeMethod( &$object, $methodName, array $parameters = array() )
	{
		$reflection = new ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
	}

	/**
	 * Get a pre-set mocked message bag.
	 * 
	 * @param  array  $messages
	 * @return \Mockery\Mock
	 */
	public function mockMessageBag( array $messages = array() )
	{
		$bag = Mockery::mock('Illuminate\Support\MessageBag');
		$bag
			->shouldReceive('all')
			->andReturn($messages);

		return $bag;
	}

}
