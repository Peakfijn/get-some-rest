<?php

use Illuminate\Support\Facades\Validator;

class ResourceValidatingTraitTest extends TestCase {

	/**
	 * Test if the resource can be valid.
	 * 
	 * @return void
	 */
	public function testResourceCanBeValid()
	{
		Validator::shouldReceive('make')
			->once()
			->andReturn(Mockery::mock([
				'passes' => true
			]));

		$this->assertTrue((new ResourceValidatingStub())->validate());
	}

	/**
	 * Test if the resource van be invalid.
	 * 
	 * @return void
	 */
	public function testResourceCanBeInvalid()
	{
		Validator::shouldReceive('make')
			->once()
			->andReturn(Mockery::mock([
				'passes'   => false,
				'messages' => $this->mockMessageBag()
			]));

		$this->assertFalse((new ResourceValidatingStub)->validate());
	}

	/**
	 * Test if the failing validation stores the error messages.
	 * 
	 * @return void
	 */
	public function testValidationFailsStoresErrors()
	{
		Validator::shouldReceive('make')
			->once()
			->andReturn(Mockery::mock([
				'passes'   => false,
				'messages' => $this->mockMessageBag(['test'])
			]));

		$resource = new ResourceValidatingStub();

		$this->assertFalse($resource->validate());
		$this->assertInternalType('array', $resource->getErrors());
		$this->assertEquals('test', $resource->getErrors()[0]);
	}

}