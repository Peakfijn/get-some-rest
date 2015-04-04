<?php namespace Peakfijn\GetSomeRest\Mutators;

use Illuminate\Http\Request;

class ArrayMutator extends Mutator
{
    /**
     * Mutate the data, returning an array that represents the response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer                  $status
     * @param  mixed                    $data
     * @return array
     */
    public function mutate(Request $request, $status, $data)
    {
        $data = $this->castToArray($data);

        if ($this->isErrorStatus($status)) {
            return ['errors' => $data];
        }

        return $data;
    }
}
