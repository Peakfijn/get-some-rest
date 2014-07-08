<?php

use Peakfijn\GetSomeRest\Usables\ResourceValidatingTrait;
use Peakfijn\GetSomeRest\Usables\ResourceFilteringScopeTrait;

class ResourceStub {

	/**
	 * The model's attributes.
	 *
	 * @var array
	 */
	public $attributes = [
		'id'    => 1,
		'name'  => 'Cedric',
		'email' => 'cedric@peakfijn.nl',
	];

	/**
	 * Set default attributes on instantiating.
	 * 
	 * @param array $attributes (Default: null)
	 */
	public function __construct( array $attributes = null )
	{
		if( !is_null($attributes) )
			$this->attributes = $attributes;
	}

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get( $key )
	{
		if( array_key_exists($key, $this->attributes) )
			return $this->attributes[$key];

		return null;
	}

	/**
	 * Dynamically set attributes on the model.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function __set( $key, $value )
	{
		$this->attributes[$key] = $value;
	}

}

class ResourceQueryStub {

	/**
	 * The mock to use.
	 * 
	 * @var \Mockery\Mock
	 */
	public $mock;

	/**
	 * Allow a Mocked object to be used.
	 * 
	 * @param \Mockery\Mock $mock
	 */
	public function __construct( $mock )
	{
		$this->mock = $mock;
	}

	/**
	 * Fake the whereHas function so the closure is called.
	 * 
	 * @param  string  $attribute
	 * @param  Closure $closure
	 * @return void
	 */
	public function whereHas( $attribute, Closure $closure )
	{
		call_user_func($closure, $this->mock);
	}

}

class ResourceValidatingStub extends ResourceStub {

	use ResourceValidatingTrait;

	/**
	 * The model's attributes with their validation rules.
	 * 
	 * @var array
	 */
	public $rules = [
		'id'    => 'Numeric',
		'name'  => 'Required',
		'email' => 'Required|Email',
	];

}

class ResourceFilteringScopeStub extends ResourceStub {

	use ResourceFilteringScopeTrait;

	/**
	 * Get all arrayable items.
	 * It's actually a replacement for the Eloquent version.
	 *
	 * @param  mixed $value (Default: null)
	 * @return array
	 */
	public function getArrayableAttributes( $value = null )
	{
		return $this->attributes;
	}

	/**
	 * Define a fake relation to check if the relation is allowed.
	 * 
	 * @return void
	 */
	public function fakeRelation()
	{
		return;
	}

}