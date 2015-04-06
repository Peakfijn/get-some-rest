<?php namespace Peakfijn\GetSomeRest\Mutators;

use Illuminate\Contracts\Support\Arrayable;
use Peakfijn\GetSomeRest\Contracts\Mutator as MutatorContract;

abstract class Mutator implements MutatorContract
{
    /**
     * Try and convert the provided data to array.
     *
     * @param  mixed $data
     * @return array
     */
    public function castToArray($data)
    {
        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        return (array)$data;
    }

    /**
     * Check if the status code is an error.
     *
     * @param  integer $status
     * @return boolean
     */
    public function isErrorStatus($status)
    {
        $status = (string)$status;

        return (int)$status[0] >= 4;
    }
}
