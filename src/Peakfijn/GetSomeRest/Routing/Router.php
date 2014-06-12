<?php namespace Peakfijn\GetSomeRest\Routing;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Peakfijn\GetSomeRest\Http\Response;
use Peakfijn\GetSomeRest\Encoders\JsonEncoder;

class Router extends \Illuminate\Routing\Router {

	/**
	 * Holds the available encoders.
	 * 
	 * @var array
	 */
	public $encoders = [];

	/**
	 * Create a group of API routes.
	 * The routes will be prefixed with the given version.
	 * Please do not use an API group within another API group.
	 * 
	 * @param  string  $version  Should match pattern /v[0-9]+/
	 * @param  Closure $callback
	 * @return coid
	 */
	public function api( $version, Closure $callback )
	{
		return $this->group(['api' => true, 'prefix' => $version], $callback);
	}

	/**
	 * Try and execute the requested route.
	 * If the requested route is an API route,
	 * catch all HttpExceptions and make it a nice response.
	 *
	 * @param  Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function dispatch( Request $request )
	{
		// If the route is not an API route,
		// dont modify the response
		if( !$this->isApiRequest($request) )
		{
			return parent::dispatch($request);
		}

		try
		{
			$response = Response::makeFromExisting(parent::dispatch($request));
		}
		// Only catch HttpExceptions,
		// other exceptions should still be thrown.
		// Like the RuntimeException for example.
		catch( HttpExceptionInterface $exception )
		{
			$response = Response::makeFromException($exception);
		}

		return $response->finalize($this->getEncoder($request), $request);
	}


	/**
	 * Detect if the given request is meant for an API route.
	 * 
	 * @param  \Illuminate\Http\Request $request
	 * @return boolean
	 */
	protected function isApiRequest( Request $request )
	{
		return !!preg_match('/v[0-9]+/', $request->segment(1));
	}

	/**
	 * Get the requested encoder.
	 * 
	 * @param  \Illuminate\Http\Request $request
	 * @return \Peakfijn\GetSomeRest\Contracts\Encoder
	 */
	protected function getEncoder( Request $request )
	{
		$encoder = array_values($this->encoders)[0];

		return new $encoder;
	}

}