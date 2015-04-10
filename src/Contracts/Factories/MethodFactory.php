<?php namespace Peakfijn\GetSomeRest\Contracts\Factories;

interface MethodFactory extends Factory
{
    /**
     * Get the method prefix.
     *
     * @return string
     */
    public function getPrefix();

    /**
     * Set the method prefix for retrieving methods from the request.
     *
     * @param string $prefix
     */
    public function setPrefix($prefix);
}
