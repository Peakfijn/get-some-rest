<?php namespace Peakfijn\GetSomeRest\Contracts\Rest;

interface Validatable
{
    /**
     * Get a validator object to validate with, or provide an array defining the rules.
     * Note, it provides a boolean to allow update detection.
     *
     * @param  boolean $updating (default: false)
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function getValidator($updating = false);
}
