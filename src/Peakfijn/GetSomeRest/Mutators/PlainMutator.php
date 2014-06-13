<?php namespace Peakfijn\GetSomeRest\Mutators;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Mutator;
use Peakfijn\GetSomeRest\Http\Response;

class PlainMutator extends Mutator {

	/**
	 * Get the mutated content
	 * 
	 * @param  \Peakfijn\GetSomeRest\Http\Response $response
	 * @param  \Illuminate\Http\Request $request
	 * @return array
	 */
	public function getContent( Response $response, Request $request )
	{
		return $this->toArray($response->getOriginalContent());
	}

}