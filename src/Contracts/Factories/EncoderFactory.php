<?php namespace Peakfijn\GetSomeRest\Contracts\Factories;

use Illuminate\Http\Request;

interface EncoderFactory extends Factory
{
    /**
     * Get a new mutator from the factory, by request.
     * It tries and fetch a mutator, by the accept header.
     * If nothing was found, it returns the default mutator.
     *
     * The encoder is extracted using the following syntax:
     *   - application/json            => json
     *   - application/xml             => xml
     *   - application/vnd.api+json    => json
     *   - application/vnd.api.v1+json => json
     *   - application/vnd.api+yml     => yml
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Encoders\Encoder|null
     */
    public function makeFromRequest(Request $request);
}
