<?php namespace Peakfijn\GetSomeRest\Exceptions;

class ResourceUnknownException extends RestException
{
    public function __construct($resource)
    {
        parent::__construct(
            404, 'Could not find the requested resource "'. $resource .'".'
        );
    }
}
