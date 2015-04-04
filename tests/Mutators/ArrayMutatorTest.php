<?php namespace Peakfijn\GetSomeRest\Tests\Mutators;

use Peakfijn\GetSomeRest\Mutators\ArrayMutator;

class ArrayMutatorTest extends MutatorTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Mutators\ArrayMutator
     */
    protected function getInstance()
    {
        return new ArrayMutator();
    }
}
