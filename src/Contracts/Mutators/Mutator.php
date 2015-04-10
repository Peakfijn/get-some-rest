<?php namespace Peakfijn\GetSomeRest\Contracts\Mutators;

use Illuminate\Http\Request;

interface Mutator
{
    /**
     * Mutate the data, returning an array that represents the response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $status
     * @param  mixed $data
     * @return array
     */
    public function mutate(Request $request, $status, $data);
}
