<?php namespace Peakfijn\GetSomeRest\Contracts;

interface Method
{
    /**
     * Execute the method with the provided resource and method value.
     *
     * @param  mixed $resource
     * @param  mixed $resource
     * @return mixed
     */
    public function execute($value, $resource);
}
