<?php namespace Peakfijn\GetSomeRest\Exceptions;

class ResourceDestroyException extends RestException
{
    public function __construct()
    {
        parent::__construct(422, 'Unable to save the requested resource.');
    }
}
