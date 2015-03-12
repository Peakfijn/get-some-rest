<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

interface Mutator
{
    /**
     * Modify the provided response, so the content will be mutate in the desired mutation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
     */
    public function mutate(Request $request, Response $response);
}
