<?php namespace Peakfijn\GetSomeRest\Rest;

use RuntimeException;
use Validator as IlluminateValidator;
use Illuminate\Contracts\Validation\Validator as IlluminateValidatorContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Validatable;
use Peakfijn\GetSomeRest\Contracts\Rest\Validator as ValidatorContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceValidationException;

class Validator implements ValidatorContract
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
    public function validate(array $input, Validatable $validatable, $updating = false)
    {
        $validator = $validatable->getValidator($updating);

        if (is_array($validator)) {
            $validator = IlluminateValidator::make($input, $validator);
        }

        if (!$validator instanceof IlluminateValidatorContract) {
            throw new RuntimeException('Validator could not validate the validatable.');
        }

        if ($validator->fails()) {
            throw new ResourceValidationException($validator->messages()->all());
        }
    }
}
