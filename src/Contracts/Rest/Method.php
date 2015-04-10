<?php namespace Peakfijn\GetSomeRest\Contracts\Rest;

interface Method
{
    /**
     * Execute the method with the provided resource and method value.
     *
     * @param  mixed $value
     * @param  mixed $resource
     * @return mixed
     */
    public function execute($value, $resource);
}
