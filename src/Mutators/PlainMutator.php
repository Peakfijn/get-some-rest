<?php namespace Peakfijn\GetSomeRest\Mutators;

use Peakfijn\GetSomeRest\Contracts\Mutator;
use Peakfijn\GetSomeRest\Http\Response;

class PlainMutator extends Mutator {

    /**
     * Get the mutated content
     *
     * @param  \Peakfijn\GetSomeRest\Http\Response $response
     * @return array
     */
    public function getContent(Response $response)
    {
        return $this->toArray($response->getOriginalContent());
    }

}