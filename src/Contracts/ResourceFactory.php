<?php namespace Peakfijn\GetSomeRest\Contracts;

interface ResourceFactory extends Factory
{
    /**
     * Try to resolve a resource class from the provided name.
     *
     * @param  string $name
     * @return boolean
     */
    public function resolve($name);

    /**
     * Get a cleaned class name from the provided string.
     *
     * @param  string $name
     * @return string
     */
    public function getClassName($name);
}
