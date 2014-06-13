<?php namespace Peakfijn\GetSomeRest\Mutators;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Mutator;
use Peakfijn\GetSomeRest\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class MetaMutator extends Mutator {

	/**
	 * Get the mutated content
	 * 
	 * @param  \Peakfijn\GetSomeRest\Http\Response $response
	 * @param  \Illuminate\Http\Request $request
	 * @return array
	 */
	public function getContent( Response $response, Request $request )
	{
		$result  = $this->toArray($response->getOriginalContent());
		$content = $this->getBasics($response->getStatusCode());

		if( $this->isAssociative($result) )
		{
			return array_merge($content, $this->getSingle($result));
		}

		return array_merge($content, $this->getMultiple($result));
	}

	/**
	 * Get the basic meta structure.
	 * 
	 * @param  int  $code
	 * @return array
	 */
	protected function getBasics( $code )
	{
		return [
			'success' => !$this->isErrorCode($code),
			'code'    => $code,
			'message' => strtolower(SymfonyResponse::$statusTexts[$code]),
		];
	}

	/**
	 * Get the meta structure for a single resource/object.
	 * 
	 * @param  array $result
	 * @return array
	 */
	protected function getSingle( array $result )
	{
		return [
			'result' => $result
		];
	}

	/**
	 * Get the meta structure for multiple resources/objects.
	 * 
	 * @param  array  $result
	 * @return array
	 */
	protected function getMultiple( array $result )
	{
		return [
			'results' => $result
		];
	}

	/**
	 * Check if the given array is associative.
	 *
	 * @see http://stackoverflow.com/questions/173400/php-arrays-a-good-way-to-check-if-an-array-is-associative-or-sequential
	 * @param  array  $array
	 * @return boolean
	 */
	protected function isAssociative( array $array )
	{
		return array_keys($array) !== range(0, count($array) - 1);
	}

	/**
	 * Check if the given status code is an error.
	 * 
	 * @param  int  $code
	 * @return boolean
	 */
	protected function isErrorCode( $code )
	{
		return substr((string) $code, 0, 1) != '2';
	}

}