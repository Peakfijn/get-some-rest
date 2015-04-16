<?php namespace Peakfijn\GetSomeRest\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Peakfijn\GetSomeRest\Http\Response;

class ResourceValidationException extends RestException
{
    /**
     * All validation error messages.
     *
     * @var array
     */
    protected $errors;

    /**
     * Create a new resource validation exception.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct(422);
    }

    /**
     * Get a response, from this exception.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return new Response(
            $this->errors,
            $this->getStatusCode()
        );
    }
}
