<?php namespace Peakfijn\GetSomeRest;

use Illuminate\Support\ServiceProvider;
use Peakfijn\GetSomeRest\Routing\Router;
use Peakfijn\GetSomeRest\Http\Response;

class GetSomeRestServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the service provider.
	 * 
	 * @return void
	 */
	public function boot()
	{
		$this->package('peakfijn/get-some-rest');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// due to a strange bug, the config is loaded after this function.
		// so lets force-load it :)
		$this->boot();

		// now load every part of the package
		$this->registerRouter();
		$this->registerEncoders();
		$this->registerMutators();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	/**
	 * Register the customized router.
	 * 
	 * @return void
	 */
	protected function registerRouter()
	{
		$this->app['router'] = $this->app->share(function( $app )
		{
			$router = new Router($app['events'], $app);

			$router->pattern('apiExtensions', $app['config']->get('get-some-rest::extension'));

			if( $app['env'] == 'testing' )
			{
				$router->disableFilters();
			}

			return $router;
		});
	}

	/**
	 * Register the encoders.
	 * 
	 * @return void
	 */
	protected function registerEncoders()
	{
		$router = $this->app['router'];
		$config = $this->app['config'];

		$router->encoders = $config->get('get-some-rest::encoders');
		$router->extensionAliases = $config->get('get-some-rest::extensions');
		$router->failUnsupportedEncoder = !!$config->get('get-some-rest::fail_on_unknown_encoder');
	}

	/**
	 * Register the mutators.
	 * 
	 * @return void
	 */
	protected function registerMutators()
	{
		$router = $this->app['router'];
		$config = $this->app['config'];

		$router->mutators = $config->get('get-some-rest::mutators');
	}

}
