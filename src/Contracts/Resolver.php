<?php namespace Peakfijn\GetSomeRest\Contracts;

interface Resolver
{
    /**
     * Get the resolved value from the resolver.
     *
     * @param  string $resolvable
     * @return object|null
     */
    public function resolve($resolvable);
}
