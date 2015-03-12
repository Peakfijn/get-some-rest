<?php namespace Peakfijn\GetSomeRest\Mutators;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Peakfijn\GetSomeRest\Contracts\Mutator;

class ArrayMutator implements Mutator
{
    /**
     * Get the mutated content
     *
     * @param  \Illuminate\Http\Response $response
     * @return array
     */
    public function mutate(Request $request, Response $response)
    {
        $data = $response->getOriginalContent();

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $response->setContent((array)$data);

        return $response;
    }
}
