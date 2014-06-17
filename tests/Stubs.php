<?php

use Peakfijn\GetSomeRest\Usables\ResourceValidatingTrait;

class ResourceValidatingStub {

	use ResourceValidatingTrait;

	/**
	 * The model's attributes.
	 *
	 * @var array
	 */
	public $attributes = [
		'id'   => 1,
		'name' => 'Cedric'
	];

	/**
	 * The model's attributes with their validation rules.
	 * 
	 * @var array
	 */
	public $rules = [
		'id'   => 'Numeric',
		'name' => 'Required'
	];

	/**
	 * Get the attributes of the object.
	 * 
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
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
