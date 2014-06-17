<?php namespace Peakfijn\GetSomeRest\Usables;

use Illuminate\Support\Facades\Validator;

trait ResourceValidatingTrait {

	/**
	 * The error poperty contains all validation error messages.
	 * 
	 * @var array
	 */
	private $errors_;

	/**
	 * Validate the resource with the current attributes, getAttributes().
	 * It will try and fetch the $this->rules for the validation rules.
	 * If also that is not supplied, it will always return false.
	 *
	 * @param  array  $rules (default: null)
	 * @return boolean
	 */
	public function validate()
	{
		if( !isset($this->rules) || !is_array($this->rules) || empty($this->rules) )
		{
			return false;
		}

		$validator = Validator::make($this->getAttributes(), $this->rules);

		if( $validator->passes() )
		{
			return true;
		}

		$this->errors_ = $validator->messages()->all();

		return false;
	}

	/**
	 * Get the validation errors.
	 * 
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors_;
	}

}