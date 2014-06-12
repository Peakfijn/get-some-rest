<?php namespace Peakfijn\GetSomeRest\Routing;

use Closure;

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

}