<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Http\Request;

interface Factory
{
    /**
     * Create an instance, for which the factory was designed for.
     * You should provide a request so the factory knows what to spawn.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function make(Request $request);
}
