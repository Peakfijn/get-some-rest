<?php namespace Peakfijn\GetSomeRest\Http\Exceptions;

class ResourceUnknownException extends RestException
{
    public function __construct()
    {
        parent::__construct(404, 'Could not find the requested resource.');
    }
}
