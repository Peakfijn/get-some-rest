<?php namespace Peakfijn\GetSomeRest\Exceptions;

class ResourceRelationUnknownException extends RestException
{
    public function __construct()
    {
        parent::__construct(
            404,
            'Could not find the requested relation from the resource'
        );
    }
}
