<?php namespace Peakfijn\GetSomeRest\Exceptions;

class ResourceUnknownException extends RestException
{
    public function __construct($resource = '')
    {
        $message = 'Could not find the requested resource';

        if (!empty($resource)) {
            $message .= ' "' . $resource . '"';
        }

        parent::__construct(404, $message . '.');
    }
}
