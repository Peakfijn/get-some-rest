<?php

use Peakfijn\GetSomeRest\Mutators\PlainMutator;

class PlainMutatorTest extends MutatorTestCase {

	/**
	 * Get a new mutator instance.
	 * 
	 * @return \Peakfijn\GetSomeRest\Contracts\Mutator
	 */
	protected function getMutator()
	{
		return new PlainMutator();
	}

}