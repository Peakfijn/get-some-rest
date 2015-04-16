<?php namespace Peakfijn\GetSomeRest\Contracts\Rest;

interface Validator
{
    /**
     * Validate a validatable object.
     *
     * @throws \RuntimeException
     * @throws \Peakfijn\GetSomeRest\Exceptions\ResourceValidationException
     * @param  Validatable $validatable
     * @param  boolean $updating (default: false)
     * @return void
     */
    public function validate(array $input, Validatable $validatable);
}
