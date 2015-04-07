<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Http\Request;

interface Dissector
{
    /**
     * Dissect the REST information from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function anatomy(Request $request);
}
