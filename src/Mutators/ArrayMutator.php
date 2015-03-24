<?php namespace Peakfijn\GetSomeRest\Mutators;

use Illuminate\Contracts\Support\Arrayable;
use Peakfijn\GetSomeRest\Contracts\Mutator;
use Symfony\Component\HttpFoundation\Request;

class ArrayMutator extends Mutator
{
    /**
     * Get the mutated content
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @param  integer                                   $status
     * @param  mixed                                     $data
     * @return array
     */
    public function mutate(Request $request, $status, $data)
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $data = (array) $data;

        if ($this->isErrorStatus($status)) {
            return ['errors' => $data];
        }

        return $data;
    }
}
