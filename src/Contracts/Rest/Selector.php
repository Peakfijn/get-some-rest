<?php namespace Peakfijn\GetSomeRest\Contracts\Rest;

interface Selector
{
    /**
     * Execute the filter on the provided query.
     *
     * @param  mixed $resource
     * @return mixed
     */
    public function filter($resource);

    /**
     * Check if the requested filter is filterable.
     *
     * @param  mixed  $resource
     * @param  string $attribute
     * @return boolean
     */
    public function isFilterableAttribute($resource, $attribute);
}
