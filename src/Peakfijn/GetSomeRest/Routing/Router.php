<?php namespace Peakfijn\GetSomeRest\Routing;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Router extends \Illuminate\Routing\Router {

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
			$response = parent::dispatch($request);
		}
		// Only catch HttpExceptions,
		// other exceptions should still be thrown.
		// Like the RuntimeException for example.
		catch( HttpExceptionInterface $exception )
		{
			$response = new Response('error');
		}

		return $response;
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

}