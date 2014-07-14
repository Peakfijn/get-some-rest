<?php namespace Peakfijn\GetSomeRest\Routing;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Http\Response;
use Peakfijn\GetSomeRest\Exceptions\UnsupportedEncoderException;
use Peakfijn\GetSomeRest\Exceptions\NotFoundResourceException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Router extends \Illuminate\Routing\Router {

	/**
	 * Holds the available mutators.
	 * 
	 * @var array
	 */
	public $mutators = [];

	/**
	 * Holds the available encoders.
	 * 
	 * @var array
	 */
	public $encoders = [];

	/**
	 * Holds the aliases for response formats.
	 * 
	 * @var array
	 */
	public $extensionAliases = [];

	/**
	 * Fail when an extension was supplied with an unsupported format.
	 *
	 * @var boolean
	 */
	public $failUnsupportedEncoder = false;

	/**
	 * The default actions for an api resourceful controller.
	 * 
	 * @var array
	 */
	protected $resourceApiDefaults = ['index', 'store', 'show', 'update', 'destroy'];

	/**
	 * These are some of the basic status code, related to the http method used.
	 * 
	 * @var array
	 */
	public static $verbs_status_codes = [
		'POST'   => 201, /* Always used for creating */
		'PUT'    => 204, /* Update something */
		'PATCH'  => 204, /* Same as PUT */
		'DELETE' => 204, /* Delete something */
	];

	/**
	 * Create a group of API routes.
	 * The routes will be prefixed with the given version.
	 * Please do not use an API group within another API group.
	 * 
	 * @param  array|string $settings  Should match pattern /v[0-9]+/
	 * @param  Closure      $callback
	 * @return coid
	 */
	public function api( $settings, Closure $callback )
	{
		if( is_string($settings) )
		{
			$settings = ['version' => $settings];
		}

		if( isset($settings['prefix']) && !empty($settings['prefix']) )
		{
			$settings['prefix'] = $settings['prefix'] .'/'. $settings['version'];
		}
		else
		{
			$settings['prefix'] = $settings['version'];
		}

		$settings['api'] = true;

		return $this->group($settings, $callback);
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
		// Also catch the FindOrFail exception for Eloquent by default.
		catch( ModelNotFoundException $exception )
		{
			$response = Response::makeFromException(new NotFoundResourceException($exception->getModel()));
		}

		return $response->finalize(
			$this->getMutator($request),
			$this->getEncoder($request),
			$request
		);
	}

	/**
	 * Create a new route instance.
	 * 
	 * @param  array|string $methods
	 * @param  string       $uri
	 * @param  mixed        $action
	 * @return \Illuminate\Routing\Route
	 */
	public function createRoute( $methods, $uri, $action )
	{
		if( $this->isApiLastGroup() )
		{
			$uri = $this->suffix($uri);
		}

		return parent::createRoute($methods, $uri, $action);
	}

	/**
	 * Create a response instance from the given value.
	 * It also tries to apply the correct status code based on the method.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @param  mixed  $response
	 * @return \Illuminate\Http\Response
	 */
	protected function prepareResponse( $request, $response )
	{
		if( !$response instanceof SymfonyResponse )
		{
			$status_code = 200;

			if( array_key_exists($request->getMethod(), static::$verbs_status_codes) )
			{
				$status_code = static::$verbs_status_codes[$request->getMethod()];
			}

			$response = new Response($response, $status_code);
		}

		return $response->prepare($request);
	}

	/**
	 * Get the applicable resource methods.
	 * If it's an API route, the 'create' & 'edit' methods will be removed by default.
	 *
	 * @param  array  $defaults
	 * @param  array  $options
	 * @return array
	 */
	protected function getResourceMethods( $defaults, $options )
	{
		$methods = parent::getResourceMethods($defaults, $options);

		if( $this->isApiLastGroup() )
		{
			$methods = array_intersect($methods, $this->resourceApiDefaults);
		}

		return $methods;
	}

	/**
	 * Suffix the given URI with the possible API formats.
	 * Copied, and adjusted, from the "prefix" function.
	 *  
	 * @param  string $uri
	 * @return string
	 */
	protected function suffix( $uri )
	{
		return trim(trim($uri, '/') . '{apiExtensions?}', '/') ?: '/';
	}


	/**
	 * Detect if the given request is meant for an API route.
	 * There can be a prefix defined...
	 * 
	 * @param  \Illuminate\Http\Request $request
	 * @return boolean
	 */
	protected function isApiRequest( Request $request )
	{
		return !!preg_match('/v[0-9]+/', $request->segment(1)) || !!preg_match('/v[0-9]+/', $request->segment(2));
	}

	/**
	 * Check if the last group was an API group.
	 * Copied, and adjusted, from the "getLastGropPrefix" function.
	 * 
	 * @return boolean
	 */
	protected function isApiLastGroup()
	{
		if( count($this->groupStack) > 0 )
		{
			return array_get(last($this->groupStack), 'api', false);
		}

		return false;
	}

	/**
	 * Get the requested encoder.
	 * 
	 * @param  \Illuminate\Http\Request $request
	 * @return \Peakfijn\GetSomeRest\Contracts\Encoder
	 */
	protected function getEncoder( Request $request )
	{
		$extension = pathinfo(last($request->segments()), PATHINFO_EXTENSION);

		if( array_key_exists($extension, $this->extensionAliases) )
		{
			$extension = $this->extensionAliases[$extension];
		}

		if( array_key_exists($extension, $this->encoders) )
		{
			return new $this->encoders[$extension];
		}

		if( !empty($extension) && $this->failUnsupportedEncoder )
		{
			throw new UnsupportedEncoderException($extension);
		}

		$encoder = array_values($this->encoders)[0];

		return new $encoder;
	}

	/**
	 * Get the requested mutator.
	 * 
	 * @param  \Illuminate\Http\Request $request
	 * @return \Peakfijn\GetSomeRest\Contracts\Mutator
	 */
	protected function getMutator( Request $request )
	{
		$mutator = array_values($this->mutators)[0];

		return new $mutator;
	}

}