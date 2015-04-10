<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Http\Request;

interface MutatorFactory extends Factory
{
    /**
     * Get a new mutator from the factory, by request.
     * It tries and fetch a mutator, by the accept header.
     * If nothing was found, it returns the default mutator.
     *
     * The mutator is extracted using the following syntax:
     *   - application/json                  =>
     *   - application/vnd.api+json          =>
     *   - application/vnd.api.v1+xml        =>
     *   - application/vnd.api.v4.plain+json => plain
     *   - application/vnd.api.v7.plain      => plain
     *   - application/vnd.api.v9.meta+yml   => meta
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Mutator|null
     */
    public function makeFromRequest(Request $request);
}
