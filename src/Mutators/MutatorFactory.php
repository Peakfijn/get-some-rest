<?php namespace Peakfijn\GetSomeRest\Mutators;

class MutatorFactory {

    function __construct()
    {
        $this->mutator = config('get-some-rest.mutator');
    }

    /**
     * Make a new mutator.
     *
     * @return mixed
     */
    public function make()
    {
        return new $this->mutator;
    }

}