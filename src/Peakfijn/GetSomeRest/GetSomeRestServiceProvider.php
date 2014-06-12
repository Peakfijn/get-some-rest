<?php namespace Peakfijn\GetSomeRest;

use Illuminate\Support\ServiceProvider;
use Peakfijn\GetSomeRest\Routing\Router;

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
		$this->registerRouter();
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

			if( $app['env'] == 'testing' )
			{
				$router->disableFilters();
			}

			return $router;
		});
	}

}
