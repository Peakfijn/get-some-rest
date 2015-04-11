<?php namespace Peakfijn\GetSomeRest\Exceptions;

class ResourceUnknownException extends RestException
{
    public function __construct($resource = '', $anatomy = null)
    {
        $message = 'Could not find the requested resource';

        if (!empty($resource)) {
            $message .= ' "' . $resource . '"';
        }

        if (!empty($anatomy)) {
            $message .= ' "' . array_pop($anatomy->segments) . '"';
        }

        parent::__construct(404, $message . '.');
    }
}
