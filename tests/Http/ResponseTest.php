<?php

use Illuminate\Http\Response as IlluminateResponse;
use Peakfijn\GetSomeRest\Http\Response;
use Peakfijn\GetSomeRest\Exceptions\NotFoundResourceException;

class ResponseTest extends TestCase {

	/**
	 * When an object or an array is returned, the Router makes a Illuminate response from it.
	 * Then it tries to create a GetSomeRest response from the existing one,
	 * so it can be mutated and encoded.
	 * 
	 * @return void
	 */
	public function testCreatesInstanceFromExistingResponse()
	{
		$existing = IlluminateResponse::create('created', 201);
		$response = Response::makeFromExisting($existing);

		$this->assertEquals($existing->getStatusCode(), $response->getStatusCode());
	}

	/**
	 * When an exception is thrown, the Router will try and make a response from it.
	 * If it's an HttpExceptionInterface exception it will be handled by the 
	 * Response::makeFromException function.
	 * 
	 * @return void
	 */
	public function testCreatesInstanceFromExistingException()
	{
		$exception = new NotFoundResourceException('Test');
		$response  = Response::makeFromException($exception);

		$this->assertEquals($exception->getStatusCode(), $response->getStatusCode());
	}

	/**
	 * When an exception is thrown, the Router will try and make a response from it.
	 * It should also be attached to the response itself.
	 * 
	 * @return void
	 */
	public function testCreatesInstanceFromExistingExceptionAlsoAddsExceptionToResponse()
	{
		$exception = new NotFoundResourceException('Test');
		$response  = Response::makeFromException($exception);

		$this->assertTrue($response->hasException());
	}

	/**
	 * When using the GetSomeRest package, the output can be encoded into multiple syntaxes.
	 * Also the output can be mutated to the prefered structure.
	 * Both of these uses the Response::finalize function to apply itself to the response.
	 * 
	 * @return void
	 */
	public function testResponseCanBeFinalizedWithMutatorAndEncoder()
	{
		$encoder = Mockery::mock('Peakfijn\GetSomeRest\Encoders\JsonEncoder');
		$mutator = Mockery::mock('Peakfijn\GetSomeRest\Mutators\PlainMutator');

		$mutator
			->shouldReceive('getContent')
			->once()
			->andReturn([]);

		$encoder
			->shouldReceive('getContent')
			->once()
			->andReturn('test');

		$encoder
			->shouldReceive('getContentType')
			->once()
			->andReturn('test');

		$request = Mockery::mock('Illuminate\Http\Request');
		
		(new Response())->finalize($mutator, $encoder, $request);
	}

}
