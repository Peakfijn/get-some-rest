<?php namespace Peakfijn\GetSomeRest\Contracts;

interface Operator
{
    /**
     * Execute the methods on the provided query.
     *
     * @param  mixed $resource
     * @return mixed
     */
    public function execute($resource);
}
