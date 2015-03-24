<?php namespace Peakfijn\GetSomeRest\Contracts;

use Symfony\Component\HttpFoundation\Request;

abstract class Mutator
{
    /**
     * Mutate the data.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer                  $status
     * @param  mixed                    $data
     * @return mixed
     */
    abstract public function mutate(Request $request, $status, $data);

    /**
     * Check if the status code is an error.
     *
     * @param  integer  $status
     * @return boolean
     */
    public function isErrorStatus($status)
    {
        $status = (string) $status;

        return (int) $status[0] >= 4;
    }
}
