<?php namespace Peakfijn\GetSomeRest\Http\Exceptions;

class ResourceDestroyException extends RestException
{
    public function __construct()
    {
        parent::__construct(422, 'Unprocessable Entity');
    }
}
